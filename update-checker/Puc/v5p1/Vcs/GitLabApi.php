<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1\Vcs;

if ( !class_exists(GitLabApi::class, false) ):

	class GitLabApi extends Api {
		use ReleaseAssetSupport;
		use ReleaseFilteringFeature;

		/**
		 * @var string GitLab username.
		 */
		protected $userName;

		/**
		 * @var string GitLab server host.
		 */
		protected $repositoryHost;

		/**
		 * @var string Protocol used by this GitLab server: "http" or "https".
		 */
		protected $repositoryProtocol = 'https';

		/**
		 * @var string GitLab repository name.
		 */
		protected $repositoryName;

		/**
		 * @var string GitLab authentication token. Optional.
		 */
		protected $accessToken;

		/**
		 * @deprecated
		 * @var bool No longer used.
		 */
		protected $releasePackageEnabled = false;

		public function __construct($repositoryUrl, $accessToken = null, $subgroup = null) {
			//Parse the repository host to support custom hosts.
			$port = wp_parse_url($repositoryUrl, PHP_URL_PORT);
			if ( !empty($port) ) {
				$port = ':' . $port;
			}
			$this->repositoryHost = wp_parse_url($repositoryUrl, PHP_URL_HOST) . $port;

			if ( $this->repositoryHost !== 'gitlab.com' ) {
				$this->repositoryProtocol = wp_parse_url($repositoryUrl, PHP_URL_SCHEME);
			}

			//Find the repository information
			$path = wp_parse_url($repositoryUrl, PHP_URL_PATH);
			if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
				$this->userName = $matches['username'];
				$this->repositoryName = $matches['repository'];
			} elseif ( ($this->repositoryHost === 'gitlab.com') ) {
				//This is probably a repository in a subgroup, e.g. "/organization/category/repo".
				$parts = explode('/', trim($path, '/'));
				if ( count($parts) < 3 ) {
					throw new \InvalidArgumentException('Invalid GitLab.com repository URL: "' . $repositoryUrl . '"');
				}
				$lastPart = array_pop($parts);
				$this->userName = implode('/', $parts);
				$this->repositoryName = $lastPart;
			} else {
				//There could be subgroups in the URL:  gitlab.domain.com/group/subgroup/subgroup2/repository
				if ( $subgroup !== null ) {
					$path = str_replace(trailingslashit($subgroup), '', $path);
				}

				//This is not a traditional url, it could be gitlab is in a deeper subdirectory.
				//Get the path segments.
				$segments = explode('/', untrailingslashit(ltrim($path, '/')));

				//We need at least /user-name/repository-name/
				if ( count($segments) < 2 ) {
					throw new \InvalidArgumentException('Invalid GitLab repository URL: "' . $repositoryUrl . '"');
				}

				//Get the username and repository name.
				$usernameRepo = array_splice($segments, -2, 2);
				$this->userName = $usernameRepo[0];
				$this->repositoryName = $usernameRepo[1];

				//Append the remaining segments to the host if there are segments left.
				if ( count($segments) > 0 ) {
					$this->repositoryHost = trailingslashit($this->repositoryHost) . implode('/', $segments);
				}

				//Add subgroups to username.
				if ( $subgroup !== null ) {
					$this->userName = $usernameRepo[0] . '/' . untrailingslashit($subgroup);
				}
			}

			parent::__construct($repositoryUrl, $accessToken);
		}

		/**
		 * Get the latest release from GitLab.
		 *
		 * @return Reference|null
		 */
		public function getLatestRelease() {
			$releases = $this->api('/:id/releases', array('per_page' => $this->releaseFilterMaxReleases));
			if ( is_wp_error($releases) || empty($releases) || !is_array($releases) ) {
				return null;
			}

			foreach ($releases as $release) {
				if (
					//Skip invalid/unsupported releases.
					!is_object($release)
					|| !isset($release->tag_name)
					//Skip upcoming releases.
					|| (
						!empty($release->upcoming_release)
						&& $this->shouldSkipPreReleases()
					)
				) {
					continue;
				}

				$versionNumber = ltrim($release->tag_name, 'v'); //Remove the "v" prefix from "v1.2.3".

				//Apply custom filters.
				if ( !$this->matchesCustomReleaseFilter($versionNumber, $release) ) {
					continue;
				}

				$downloadUrl = $this->findReleaseDownloadUrl($release);
				if ( empty($downloadUrl) ) {
					//The latest release doesn't have valid download URL.
					return null;
				}

				if ( !empty($this->accessToken) ) {
					$downloadUrl = add_query_arg('private_token', $this->accessToken, $downloadUrl);
				}

				return new Reference(array(
					'name'        => $release->tag_name,
					'version'     => $versionNumber,
					'downloadUrl' => $downloadUrl,
					'updated'     => $release->released_at,
					'apiResponse' => $release,
				));
			}

			return null;
		}

		/**
		 * @param object $release
		 * @return string|null
		 */
		protected function findReleaseDownloadUrl($release) {
			if ( $this->releaseAssetsEnabled ) {
				if ( isset($release->assets, $release->assets->links) ) {
					//Use the first asset link where the URL matches the filter.
					foreach ($release->assets->links as $link) {
						if ( $this->matchesAssetFilter($link) ) {
							return $link->url;
						}
					}
				}

				if ( $this->releaseAssetPreference === Api::REQUIRE_RELEASE_ASSETS ) {
					//Falling back to source archives is not allowed, so give up.
					return null;
				}
			}

			//Use the first source code archive that's in ZIP format.
			foreach ($release->assets->sources as $source) {
				if ( isset($source->format) && ($source->format === 'zip') ) {
					return $source->url;
				}
			}

			return null;
		}

		/**
		 * Get the tag that looks like the highest version number.
		 *
		 * @return Reference|null
		 */
		public function getLatestTag() {
			$tags = $this->api('/:id/repository/tags');
			if ( is_wp_error($tags) || empty($tags) || !is_array($tags) ) {
				return null;
			}

			$versionTags = $this->sortTagsByVersion($tags);
			if ( empty($versionTags) ) {
				return null;
			}

			$tag = $versionTags[0];
			return new Reference(array(
				'name'        => $tag->name,
				'version'     => ltrim($tag->name, 'v'),
				'downloadUrl' => $this->buildArchiveDownloadUrl($tag->name),
				'apiResponse' => $tag,
			));
		}

		/**
		 * Get a branch by name.
		 *
		 * @param string $branchName
		 * @return null|Reference
		 */
		public function getBranch($branchName) {
			$branch = $this->api('/:id/repository/branches/' . $branchName);
			if ( is_wp_error($branch) || empty($branch) ) {
				return null;
			}

			$reference = new Reference(array(
				'name'        => $branch->name,
				'downloadUrl' => $this->buildArchiveDownloadUrl($branch->name),
				'apiResponse' => $branch,
			));

			if ( isset($branch->commit, $branch->commit->committed_date) ) {
				$reference->updated = $branch->commit->committed_date;
			}

			return $reference;
		}

		/**
		 * Get the timestamp of the latest commit that changed the specified branch or tag.
		 *
		 * @param string $ref Reference name (e.g. branch or tag).
		 * @return string|null
		 */
		public function getLatestCommitTime($ref) {
			$commits = $this->api('/:id/repository/commits/', array('ref_name' => $ref));
			if ( is_wp_error($commits) || !is_array($commits) || !isset($commits[0]) ) {
				return null;
			}

			return $commits[0]->committed_date;
		}

		/**
		 * Perform a GitLab API request.
		 *
		 * @param string $url
		 * @param array $queryParams
		 * @return mixed|\WP_Error
		 */
		protected function api($url, $queryParams = array()) {
			$baseUrl = $url;
			$url = $this->buildApiUrl($url, $queryParams);

			$options = array('timeout' => wp_doing_cron() ? 10 : 3);
			if ( !empty($this->httpFilterName) ) {
				$options = apply_filters($this->httpFilterName, $options);
			}

			$response = wp_remote_get($url, $options);
			if ( is_wp_error($response) ) {
				do_action('puc_api_error', $response, null, $url, $this->slug);
				return $response;
			}

			$code = wp_remote_retrieve_response_code($response);
			$body = wp_remote_retrieve_body($response);
			if ( $code === 200 ) {
				return json_decode($body);
			}

			$error = new \WP_Error(
				'puc-gitlab-http-error',
				sprintf('GitLab API error. URL: "%s",  HTTP status code: %d.', $baseUrl, $code)
			);
			do_action('puc_api_error', $error, $response, $url, $this->slug);

			return $error;
		}

		/**
		 * Build a fully qualified URL for an API request.
		 *
		 * @param string $url
		 * @param array $queryParams
		 * @return string
		 */
		protected function buildApiUrl($url, $queryParams) {
			$variables = array(
				'user' => $this->userName,
				'repo' => $this->repositoryName,
				'id'   => $this->userName . '/' . $this->repositoryName,
			);

			foreach ($variables as $name => $value) {
				$url = str_replace("/:{$name}", '/' . urlencode($value), $url);
			}

			$url = substr($url, 1);
			$url = sprintf('%1$s://%2$s/api/v4/projects/%3$s', $this->repositoryProtocol, $this->repositoryHost, $url);

			if ( !empty($this->accessToken) ) {
				$queryParams['private_token'] = $this->accessToken;
			}

			if ( !empty($queryParams) ) {
				$url = add_query_arg($queryParams, $url);
			}

			return $url;
		}

		/**
		 * Get the contents of a file from a specific branch or tag.
		 *
		 * @param string $path File name.
		 * @param string $ref
		 * @return null|string Either the contents of the file, or null if the file doesn't exist or there's an error.
		 */
		public function getRemoteFile($path, $ref = 'master') {
			$response = $this->api('/:id/repository/files/' . $path, array('ref' => $ref));
			if ( is_wp_error($response) || !isset($response->content) || $response->encoding !== 'base64' ) {
				return null;
			}

			return base64_decode($response->content);
		}

		/**
		 * Generate a URL to download a ZIP archive of the specified branch/tag/etc.
		 *
		 * @param string $ref
		 * @return string
		 */
		public function buildArchiveDownloadUrl($ref = 'master') {
			$url = sprintf(
				'%1$s://%2$s/api/v4/projects/%3$s/repository/archive.zip',
				$this->repositoryProtocol,
				$this->repositoryHost,
				urlencode($this->userName . '/' . $this->repositoryName)
			);
			$url = add_query_arg('sha', urlencode($ref), $url);

			if ( !empty($this->accessToken) ) {
				$url = add_query_arg('private_token', $this->accessToken, $url);
			}

			return $url;
		}

		/**
		 * Get a specific tag.
		 *
		 * @param string $tagName
		 * @return void
		 */
		public function getTag($tagName) {
			throw new \LogicException('The ' . __METHOD__ . ' method is not implemented and should not be used.');
		}

		protected function getUpdateDetectionStrategies($configBranch) {
			$strategies = array();

			if ( ($configBranch === 'main') || ($configBranch === 'master') ) {
				$strategies[self::STRATEGY_LATEST_RELEASE] = array($this, 'getLatestRelease');
				$strategies[self::STRATEGY_LATEST_TAG] = array($this, 'getLatestTag');
			}

			$strategies[self::STRATEGY_BRANCH] = function () use ($configBranch) {
				return $this->getBranch($configBranch);
			};

			return $strategies;
		}

		public function setAuthentication($credentials) {
			parent::setAuthentication($credentials);
			$this->accessToken = is_string($credentials) ? $credentials : null;
		}

		/**
		 * Use release assets that link to GitLab generic packages (e.g. .zip files)
		 * instead of automatically generated source archives.
		 *
		 * This is included for backwards compatibility with older versions of PUC.
		 *
		 * @return void
		 * @deprecated   Use enableReleaseAssets() instead.
		 * @noinspection PhpUnused -- Public API
		 */
		public function enableReleasePackages() {
			$this->enableReleaseAssets(
			/** @lang RegExp */ '/\.zip($|[?&#])/i',
				Api::REQUIRE_RELEASE_ASSETS
			);
		}

		protected function getFilterableAssetName($releaseAsset) {
			if ( isset($releaseAsset->url) ) {
				return $releaseAsset->url;
			}
			return null;
		}
	}

endif;
