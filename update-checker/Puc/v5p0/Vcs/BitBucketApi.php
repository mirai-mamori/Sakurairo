<?php

namespace YahnisElsts\PluginUpdateChecker\v5p0\Vcs;

use YahnisElsts\PluginUpdateChecker\v5p0\OAuthSignature;
use YahnisElsts\PluginUpdateChecker\v5p0\Utils;

if ( !class_exists(BitBucketApi::class, false) ):

	class BitBucketApi extends Api {
		/**
		 * @var OAuthSignature
		 */
		private $oauth = null;

		/**
		 * @var string
		 */
		private $username;

		/**
		 * @var string
		 */
		private $repository;

		public function __construct($repositoryUrl, $credentials = array()) {
			$path = wp_parse_url($repositoryUrl, PHP_URL_PATH);
			if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
				$this->username = $matches['username'];
				$this->repository = $matches['repository'];
			} else {
				throw new \InvalidArgumentException('Invalid BitBucket repository URL: "' . $repositoryUrl . '"');
			}

			parent::__construct($repositoryUrl, $credentials);
		}

		protected function getUpdateDetectionStrategies($configBranch) {
			$strategies = array(
				self::STRATEGY_STABLE_TAG => function () use ($configBranch) {
					return $this->getStableTag($configBranch);
				},
			);

			if ( ($configBranch === 'master' || $configBranch === 'main') ) {
				$strategies[self::STRATEGY_LATEST_TAG] = array($this, 'getLatestTag');
			}

			$strategies[self::STRATEGY_BRANCH] = function () use ($configBranch) {
				return $this->getBranch($configBranch);
			};
			return $strategies;
		}

		public function getBranch($branchName) {
			$branch = $this->api('/refs/branches/' . $branchName);
			if ( is_wp_error($branch) || empty($branch) ) {
				return null;
			}

			//The "/src/{stuff}/{path}" endpoint doesn't seem to handle branch names that contain slashes.
			//If we don't encode the slash, we get a 404. If we encode it as "%2F", we get a 401.
			//To avoid issues, if the branch name is not URL-safe, let's use the commit hash instead.
			$ref = $branch->name;
			if ((urlencode($ref) !== $ref) && isset($branch->target->hash)) {
				$ref = $branch->target->hash;
			}

			return new Reference(array(
				'name' => $ref,
				'updated' => $branch->target->date,
				'downloadUrl' => $this->getDownloadUrl($branch->name),
			));
		}

		/**
		 * Get a specific tag.
		 *
		 * @param string $tagName
		 * @return Reference|null
		 */
		public function getTag($tagName) {
			$tag = $this->api('/refs/tags/' . $tagName);
			if ( is_wp_error($tag) || empty($tag) ) {
				return null;
			}

			return new Reference(array(
				'name' => $tag->name,
				'version' => ltrim($tag->name, 'v'),
				'updated' => $tag->target->date,
				'downloadUrl' => $this->getDownloadUrl($tag->name),
			));
		}

		/**
		 * Get the tag that looks like the highest version number.
		 *
		 * @return Reference|null
		 */
		public function getLatestTag() {
			$tags = $this->api('/refs/tags?sort=-target.date');
			if ( !isset($tags, $tags->values) || !is_array($tags->values) ) {
				return null;
			}

			//Filter and sort the list of tags.
			$versionTags = $this->sortTagsByVersion($tags->values);

			//Return the first result.
			if ( !empty($versionTags) ) {
				$tag = $versionTags[0];
				return new Reference(array(
					'name' => $tag->name,
					'version' => ltrim($tag->name, 'v'),
					'updated' => $tag->target->date,
					'downloadUrl' => $this->getDownloadUrl($tag->name),
				));
			}
			return null;
		}

		/**
		 * Get the tag/ref specified by the "Stable tag" header in the readme.txt of a given branch.
		 *
		 * @param string $branch
		 * @return null|Reference
		 */
		protected function getStableTag($branch) {
			$remoteReadme = $this->getRemoteReadme($branch);
			if ( !empty($remoteReadme['stable_tag']) ) {
				$tag = $remoteReadme['stable_tag'];

				//You can explicitly opt out of using tags by setting "Stable tag" to
				//"trunk" or the name of the current branch.
				if ( ($tag === $branch) || ($tag === 'trunk') ) {
					return $this->getBranch($branch);
				}

				return $this->getTag($tag);
			}

			return null;
		}

		/**
		 * @param string $ref
		 * @return string
		 */
		protected function getDownloadUrl($ref) {
			return sprintf(
				'https://bitbucket.org/%s/%s/get/%s.zip',
				$this->username,
				$this->repository,
				$ref
			);
		}

		/**
		 * Get the contents of a file from a specific branch or tag.
		 *
		 * @param string $path File name.
		 * @param string $ref
		 * @return null|string Either the contents of the file, or null if the file doesn't exist or there's an error.
		 */
		public function getRemoteFile($path, $ref = 'master') {
			$response = $this->api('src/' . $ref . '/' . ltrim($path));
			if ( is_wp_error($response) || !is_string($response) ) {
				return null;
			}
			return $response;
		}

		/**
		 * Get the timestamp of the latest commit that changed the specified branch or tag.
		 *
		 * @param string $ref Reference name (e.g. branch or tag).
		 * @return string|null
		 */
		public function getLatestCommitTime($ref) {
			$response = $this->api('commits/' . $ref);
			if ( isset($response->values, $response->values[0], $response->values[0]->date) ) {
				return $response->values[0]->date;
			}
			return null;
		}

		/**
		 * Perform a BitBucket API 2.0 request.
		 *
		 * @param string $url
		 * @param string $version
		 * @return mixed|\WP_Error
		 */
		public function api($url, $version = '2.0') {
			$url = ltrim($url, '/');
			$isSrcResource = Utils::startsWith($url, 'src/');

			$url = implode('/', array(
				'https://api.bitbucket.org',
				$version,
				'repositories',
				$this->username,
				$this->repository,
				$url
			));
			$baseUrl = $url;

			if ( $this->oauth ) {
				$url = $this->oauth->sign($url,'GET');
			}

			$options = array('timeout' => 10);
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
				if ( $isSrcResource ) {
					//Most responses are JSON-encoded, but src resources just
					//return raw file contents.
					$document = $body;
				} else {
					$document = json_decode($body);
				}
				return $document;
			}

			$error = new \WP_Error(
				'puc-bitbucket-http-error',
				sprintf('BitBucket API error. Base URL: "%s",  HTTP status code: %d.', $baseUrl, $code)
			);
			do_action('puc_api_error', $error, $response, $url, $this->slug);

			return $error;
		}

		/**
		 * @param array $credentials
		 */
		public function setAuthentication($credentials) {
			parent::setAuthentication($credentials);

			if ( !empty($credentials) && !empty($credentials['consumer_key']) ) {
				$this->oauth = new OAuthSignature(
					$credentials['consumer_key'],
					$credentials['consumer_secret']
				);
			} else {
				$this->oauth = null;
			}
		}

		public function signDownloadUrl($url) {
			//Add authentication data to download URLs. Since OAuth signatures incorporate
			//timestamps, we have to do this immediately before inserting the update. Otherwise,
			//authentication could fail due to a stale timestamp.
			if ( $this->oauth ) {
				$url = $this->oauth->sign($url);
			}
			return $url;
		}
	}

endif;
