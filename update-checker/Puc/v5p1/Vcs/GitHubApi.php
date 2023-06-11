<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1\Vcs;

use Parsedown;

if ( !class_exists(GitHubApi::class, false) ):

	class GitHubApi extends Api {
		use ReleaseAssetSupport;
		use ReleaseFilteringFeature;

		/**
		 * @var string GitHub username.
		 */
		protected $userName;
		/**
		 * @var string GitHub repository name.
		 */
		protected $repositoryName;

		/**
		 * @var string Either a fully qualified repository URL, or just "user/repo-name".
		 */
		protected $repositoryUrl;

		/**
		 * @var string GitHub authentication token. Optional.
		 */
		protected $accessToken;

		/**
		 * @var bool
		 */
		private $downloadFilterAdded = false;

		public function __construct($repositoryUrl, $accessToken = null) {
			$path = wp_parse_url($repositoryUrl, PHP_URL_PATH);
			if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
				$this->userName = $matches['username'];
				$this->repositoryName = $matches['repository'];
			} else {
				throw new \InvalidArgumentException('Invalid GitHub repository URL: "' . $repositoryUrl . '"');
			}

			parent::__construct($repositoryUrl, $accessToken);
		}

		/**
		 * Get the latest release from GitHub.
		 *
		 * @return Reference|null
		 */
		public function getLatestRelease() {
			//The "latest release" endpoint returns one release and always skips pre-releases,
			//so we can only use it if that's compatible with the current filter settings.
			if (
				$this->shouldSkipPreReleases()
				&& (
					($this->releaseFilterMaxReleases === 1) || !$this->hasCustomReleaseFilter()
				)
			) {
				//Just get the latest release.
				$release = $this->api('/repos/:user/:repo/releases/latest');
				if ( is_wp_error($release) || !is_object($release) || !isset($release->tag_name) ) {
					return null;
				}
				$foundReleases = array($release);
			} else {
				//Get a list of the most recent releases.
				$foundReleases = $this->api(
					'/repos/:user/:repo/releases',
					array('per_page' => $this->releaseFilterMaxReleases)
				);
				if ( is_wp_error($foundReleases) || !is_array($foundReleases) ) {
					return null;
				}
			}

			foreach ($foundReleases as $release) {
				//Always skip drafts.
				if ( isset($release->draft) && !empty($release->draft) ) {
					continue;
				}

				//Skip pre-releases unless specifically included.
				if (
					$this->shouldSkipPreReleases()
					&& isset($release->prerelease)
					&& !empty($release->prerelease)
				) {
					continue;
				}

				$versionNumber = ltrim($release->tag_name, 'v'); //Remove the "v" prefix from "v1.2.3".

				//Custom release filtering.
				if ( !$this->matchesCustomReleaseFilter($versionNumber, $release) ) {
					continue;
				}

				$reference = new Reference(array(
					'name'        => $release->tag_name,
					'version'     => $versionNumber,
					'downloadUrl' => $release->zipball_url,
					'updated'     => $release->created_at,
					'apiResponse' => $release,
				));

				if ( isset($release->assets[0]) ) {
					$reference->downloadCount = $release->assets[0]->download_count;
				}

				if ( $this->releaseAssetsEnabled ) {
					//Use the first release asset that matches the specified regular expression.
					if ( isset($release->assets, $release->assets[0]) ) {
						$matchingAssets = array_values(array_filter($release->assets, array($this, 'matchesAssetFilter')));
					} else {
						$matchingAssets = array();
					}

					if ( !empty($matchingAssets) ) {
						if ( $this->isAuthenticationEnabled() ) {
							/**
							 * Keep in mind that we'll need to add an "Accept" header to download this asset.
							 *
							 * @see setUpdateDownloadHeaders()
							 */
							$reference->downloadUrl = $matchingAssets[0]->url;
						} else {
							//It seems that browser_download_url only works for public repositories.
							//Using an access_token doesn't help. Maybe OAuth would work?
							$reference->downloadUrl = $matchingAssets[0]->browser_download_url;
						}

						$reference->downloadCount = $matchingAssets[0]->download_count;
					} else if ( $this->releaseAssetPreference === Api::REQUIRE_RELEASE_ASSETS ) {
						//None of the assets match the filter, and we're not allowed
						//to fall back to the auto-generated source ZIP.
						return null;
					}
				}

				if ( !empty($release->body) ) {
					$reference->changelog = Parsedown::instance()->text($release->body);
				}

				return $reference;
			}

			return null;
		}

		/**
		 * Get the tag that looks like the highest version number.
		 *
		 * @return Reference|null
		 */
		public function getLatestTag() {
			$tags = $this->api('/repos/:user/:repo/tags');

			if ( is_wp_error($tags) || !is_array($tags) ) {
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
				'downloadUrl' => $tag->zipball_url,
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
			$branch = $this->api('/repos/:user/:repo/branches/' . $branchName);
			if ( is_wp_error($branch) || empty($branch) ) {
				return null;
			}

			$reference = new Reference(array(
				'name'        => $branch->name,
				'downloadUrl' => $this->buildArchiveDownloadUrl($branch->name),
				'apiResponse' => $branch,
			));

			if ( isset($branch->commit, $branch->commit->commit, $branch->commit->commit->author->date) ) {
				$reference->updated = $branch->commit->commit->author->date;
			}

			return $reference;
		}

		/**
		 * Get the latest commit that changed the specified file.
		 *
		 * @param string $filename
		 * @param string $ref Reference name (e.g. branch or tag).
		 * @return \StdClass|null
		 */
		public function getLatestCommit($filename, $ref = 'master') {
			$commits = $this->api(
				'/repos/:user/:repo/commits',
				array(
					'path' => $filename,
					'sha'  => $ref,
				)
			);
			if ( !is_wp_error($commits) && isset($commits[0]) ) {
				return $commits[0];
			}
			return null;
		}

		/**
		 * Get the timestamp of the latest commit that changed the specified branch or tag.
		 *
		 * @param string $ref Reference name (e.g. branch or tag).
		 * @return string|null
		 */
		public function getLatestCommitTime($ref) {
			$commits = $this->api('/repos/:user/:repo/commits', array('sha' => $ref));
			if ( !is_wp_error($commits) && isset($commits[0]) ) {
				return $commits[0]->commit->author->date;
			}
			return null;
		}

		/**
		 * Perform a GitHub API request.
		 *
		 * @param string $url
		 * @param array $queryParams
		 * @return mixed|\WP_Error
		 */
		protected function api($url, $queryParams = array()) {
			$baseUrl = $url;
			$url = $this->buildApiUrl($url, $queryParams);

			$options = array('timeout' => wp_doing_cron() ? 10 : 3);
			if ( $this->isAuthenticationEnabled() ) {
				$options['headers'] = array('Authorization' => $this->getAuthorizationHeader());
			}

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
				$document = json_decode($body);
				return $document;
			}

			$error = new \WP_Error(
				'puc-github-http-error',
				sprintf('GitHub API error. Base URL: "%s",  HTTP status code: %d.', $baseUrl, $code)
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
			);
			foreach ($variables as $name => $value) {
				$url = str_replace('/:' . $name, '/' . urlencode($value), $url);
			}
			$url = 'https://api.github.com' . $url;

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
			$apiUrl = '/repos/:user/:repo/contents/' . $path;
			$response = $this->api($apiUrl, array('ref' => $ref));

			if ( is_wp_error($response) || !isset($response->content) || ($response->encoding !== 'base64') ) {
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
				'https://api.github.com/repos/%1$s/%2$s/zipball/%3$s',
				urlencode($this->userName),
				urlencode($this->repositoryName),
				urlencode($ref)
			);
			return $url;
		}

		/**
		 * Get a specific tag.
		 *
		 * @param string $tagName
		 * @return void
		 */
		public function getTag($tagName) {
			//The current GitHub update checker doesn't use getTag, so I didn't bother to implement it.
			throw new \LogicException('The ' . __METHOD__ . ' method is not implemented and should not be used.');
		}

		public function setAuthentication($credentials) {
			parent::setAuthentication($credentials);
			$this->accessToken = is_string($credentials) ? $credentials : null;

			//Optimization: Instead of filtering all HTTP requests, let's do it only when
			//WordPress is about to download an update.
			add_filter('upgrader_pre_download', array($this, 'addHttpRequestFilter'), 10, 1); //WP 3.7+
		}

		protected function getUpdateDetectionStrategies($configBranch) {
			$strategies = array();

			if ( $configBranch === 'master' || $configBranch === 'main') {
				//Use the latest release.
				$strategies[self::STRATEGY_LATEST_RELEASE] = array($this, 'getLatestRelease');
				//Failing that, use the tag with the highest version number.
				$strategies[self::STRATEGY_LATEST_TAG] = array($this, 'getLatestTag');
			}

			//Alternatively, just use the branch itself.
			$strategies[self::STRATEGY_BRANCH] = function () use ($configBranch) {
				return $this->getBranch($configBranch);
			};

			return $strategies;
		}

		/**
		 * Get the unchanging part of a release asset URL. Used to identify download attempts.
		 *
		 * @return string
		 */
		protected function getAssetApiBaseUrl() {
			return sprintf(
				'//api.github.com/repos/%1$s/%2$s/releases/assets/',
				$this->userName,
				$this->repositoryName
			);
		}

		protected function getFilterableAssetName($releaseAsset) {
			if ( isset($releaseAsset->name) ) {
				return $releaseAsset->name;
			}
			return null;
		}

		/**
		 * @param bool $result
		 * @return bool
		 * @internal
		 */
		public function addHttpRequestFilter($result) {
			if ( !$this->downloadFilterAdded && $this->isAuthenticationEnabled() ) {
				//phpcs:ignore WordPressVIPMinimum.Hooks.RestrictedHooks.http_request_args -- The callback doesn't change the timeout.
				add_filter('http_request_args', array($this, 'setUpdateDownloadHeaders'), 10, 2);
				add_action('requests-requests.before_redirect', array($this, 'removeAuthHeaderFromRedirects'), 10, 4);
				$this->downloadFilterAdded = true;
			}
			return $result;
		}

		/**
		 * Set the HTTP headers that are necessary to download updates from private repositories.
		 *
		 * See GitHub docs:
		 *
		 * @link https://developer.github.com/v3/repos/releases/#get-a-single-release-asset
		 * @link https://developer.github.com/v3/auth/#basic-authentication
		 *
		 * @internal
		 * @param array $requestArgs
		 * @param string $url
		 * @return array
		 */
		public function setUpdateDownloadHeaders($requestArgs, $url = '') {
			//Is WordPress trying to download one of our release assets?
			if ( $this->releaseAssetsEnabled && (strpos($url, $this->getAssetApiBaseUrl()) !== false) ) {
				$requestArgs['headers']['Accept'] = 'application/octet-stream';
			}
			//Use Basic authentication, but only if the download is from our repository.
			$repoApiBaseUrl = $this->buildApiUrl('/repos/:user/:repo/', array());
			if ( $this->isAuthenticationEnabled() && (strpos($url, $repoApiBaseUrl)) === 0 ) {
				$requestArgs['headers']['Authorization'] = $this->getAuthorizationHeader();
			}
			return $requestArgs;
		}

		/**
		 * When following a redirect, the Requests library will automatically forward
		 * the authorization header to other hosts. We don't want that because it breaks
		 * AWS downloads and can leak authorization information.
		 *
		 * @param string $location
		 * @param array $headers
		 * @internal
		 */
		public function removeAuthHeaderFromRedirects(&$location, &$headers) {
			$repoApiBaseUrl = $this->buildApiUrl('/repos/:user/:repo/', array());
			if ( strpos($location, $repoApiBaseUrl) === 0 ) {
				return; //This request is going to GitHub, so it's fine.
			}
			//Remove the header.
			if ( isset($headers['Authorization']) ) {
				unset($headers['Authorization']);
			}
		}

		/**
		 * Generate the value of the "Authorization" header.
		 *
		 * @return string
		 */
		protected function getAuthorizationHeader() {
			return 'Basic ' . base64_encode($this->userName . ':' . $this->accessToken);
		}
	}

endif;
