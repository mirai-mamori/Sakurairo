<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1\Vcs;

use YahnisElsts\PluginUpdateChecker\v5p1\Plugin;

if ( !class_exists(PluginUpdateChecker::class, false) ):

	class PluginUpdateChecker extends Plugin\UpdateChecker implements BaseChecker {
		use VcsCheckerMethods;

		/**
		 * PluginUpdateChecker constructor.
		 *
		 * @param Api $api
		 * @param string $pluginFile
		 * @param string $slug
		 * @param int $checkPeriod
		 * @param string $optionName
		 * @param string $muPluginFile
		 */
		public function __construct($api, $pluginFile, $slug = '', $checkPeriod = 12, $optionName = '', $muPluginFile = '') {
			$this->api = $api;

			parent::__construct($api->getRepositoryUrl(), $pluginFile, $slug, $checkPeriod, $optionName, $muPluginFile);

			$this->api->setHttpFilterName($this->getUniqueName('request_info_options'));
			$this->api->setStrategyFilterName($this->getUniqueName('vcs_update_detection_strategies'));
			$this->api->setSlug($this->slug);
		}

		public function requestInfo($unusedParameter = null) {
			//We have to make several remote API requests to gather all the necessary info
			//which can take a while on slow networks.
			if ( function_exists('set_time_limit') ) {
				@set_time_limit(60);
			}

			$api = $this->api;
			$api->setLocalDirectory($this->package->getAbsoluteDirectoryPath());

			$info = new Plugin\PluginInfo();
			$info->filename = $this->pluginFile;
			$info->slug = $this->slug;

			$this->setInfoFromHeader($this->package->getPluginHeader(), $info);
			$this->setIconsFromLocalAssets($info);
			$this->setBannersFromLocalAssets($info);

			//Pick a branch or tag.
			$updateSource = $api->chooseReference($this->branch);
			if ( $updateSource ) {
				$ref = $updateSource->name;
				$info->version = $updateSource->version;
				$info->last_updated = $updateSource->updated;
				$info->download_url = $updateSource->downloadUrl;

				if ( !empty($updateSource->changelog) ) {
					$info->sections['changelog'] = $updateSource->changelog;
				}
				if ( isset($updateSource->downloadCount) ) {
					$info->downloaded = $updateSource->downloadCount;
				}
			} else {
				//There's probably a network problem or an authentication error.
				do_action(
					'puc_api_error',
					new \WP_Error(
						'puc-no-update-source',
						'Could not retrieve version information from the repository. '
						. 'This usually means that the update checker either can\'t connect '
						. 'to the repository or it\'s configured incorrectly.'
					),
					null, null, $this->slug
				);
				return null;
			}

			//Get headers from the main plugin file in this branch/tag. Its "Version" header and other metadata
			//are what the WordPress install will actually see after upgrading, so they take precedence over releases/tags.
			$mainPluginFile = basename($this->pluginFile);
			$remotePlugin = $api->getRemoteFile($mainPluginFile, $ref);
			if ( !empty($remotePlugin) ) {
				$remoteHeader = $this->package->getFileHeader($remotePlugin);
				$this->setInfoFromHeader($remoteHeader, $info);
			}

			//Try parsing readme.txt. If it's formatted according to WordPress.org standards, it will contain
			//a lot of useful information like the required/tested WP version, changelog, and so on.
			if ( $this->readmeTxtExistsLocally() ) {
				$this->setInfoFromRemoteReadme($ref, $info);
			}

			//The changelog might be in a separate file.
			if ( empty($info->sections['changelog']) ) {
				$info->sections['changelog'] = $api->getRemoteChangelog($ref, $this->package->getAbsoluteDirectoryPath());
				if ( empty($info->sections['changelog']) ) {
					$info->sections['changelog'] = __('There is no changelog available.', 'plugin-update-checker');
				}
			}

			if ( empty($info->last_updated) ) {
				//Fetch the latest commit that changed the tag or branch and use it as the "last_updated" date.
				$latestCommitTime = $api->getLatestCommitTime($ref);
				if ( $latestCommitTime !== null ) {
					$info->last_updated = $latestCommitTime;
				}
			}

			$info = apply_filters($this->getUniqueName('request_info_result'), $info, null);
			return $info;
		}

		/**
		 * Check if the currently installed version has a readme.txt file.
		 *
		 * @return bool
		 */
		protected function readmeTxtExistsLocally() {
			return $this->package->fileExists($this->api->getLocalReadmeName());
		}

		/**
		 * Copy plugin metadata from a file header to a Plugin Info object.
		 *
		 * @param array $fileHeader
		 * @param Plugin\PluginInfo $pluginInfo
		 */
		protected function setInfoFromHeader($fileHeader, $pluginInfo) {
			$headerToPropertyMap = array(
				'Version' => 'version',
				'Name' => 'name',
				'PluginURI' => 'homepage',
				'Author' => 'author',
				'AuthorName' => 'author',
				'AuthorURI' => 'author_homepage',

				'Requires WP' => 'requires',
				'Tested WP' => 'tested',
				'Requires at least' => 'requires',
				'Tested up to' => 'tested',

				'Requires PHP' => 'requires_php',
			);
			foreach ($headerToPropertyMap as $headerName => $property) {
				if ( isset($fileHeader[$headerName]) && !empty($fileHeader[$headerName]) ) {
					$pluginInfo->$property = $fileHeader[$headerName];
				}
			}

			if ( !empty($fileHeader['Description']) ) {
				$pluginInfo->sections['description'] = $fileHeader['Description'];
			}
		}

		/**
		 * Copy plugin metadata from the remote readme.txt file.
		 *
		 * @param string $ref GitHub tag or branch where to look for the readme.
		 * @param Plugin\PluginInfo $pluginInfo
		 */
		protected function setInfoFromRemoteReadme($ref, $pluginInfo) {
			$readme = $this->api->getRemoteReadme($ref);
			if ( empty($readme) ) {
				return;
			}

			if ( isset($readme['sections']) ) {
				$pluginInfo->sections = array_merge($pluginInfo->sections, $readme['sections']);
			}
			if ( !empty($readme['tested_up_to']) ) {
				$pluginInfo->tested = $readme['tested_up_to'];
			}
			if ( !empty($readme['requires_at_least']) ) {
				$pluginInfo->requires = $readme['requires_at_least'];
			}
			if ( !empty($readme['requires_php']) ) {
				$pluginInfo->requires_php = $readme['requires_php'];
			}

			if ( isset($readme['upgrade_notice'], $readme['upgrade_notice'][$pluginInfo->version]) ) {
				$pluginInfo->upgrade_notice = $readme['upgrade_notice'][$pluginInfo->version];
			}
		}

		/**
		 * Add icons from the currently installed version to a Plugin Info object.
		 *
		 * The icons should be in a subdirectory named "assets". Supported image formats
		 * and file names are described here:
		 * @link https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/#plugin-icons
		 *
		 * @param Plugin\PluginInfo $pluginInfo
		 */
		protected function setIconsFromLocalAssets($pluginInfo) {
			$icons = $this->getLocalAssetUrls(array(
				'icon.svg'         => 'svg',
				'icon-256x256.png' => '2x',
				'icon-256x256.jpg' => '2x',
				'icon-128x128.png' => '1x',
				'icon-128x128.jpg' => '1x',
			));

			if ( !empty($icons) ) {
				//The "default" key seems to be used only as last-resort fallback in WP core (5.8/5.9),
				//but we'll set it anyway in case some code somewhere needs it.
				reset($icons);
				$firstKey = key($icons);
				$icons['default'] = $icons[$firstKey];

				$pluginInfo->icons = $icons;
			}
		}

		/**
		 * Add banners from the currently installed version to a Plugin Info object.
		 *
		 * The banners should be in a subdirectory named "assets". Supported image formats
		 * and file names are described here:
		 * @link https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/#plugin-headers
		 *
		 * @param Plugin\PluginInfo $pluginInfo
		 */
		protected function setBannersFromLocalAssets($pluginInfo) {
			$banners = $this->getLocalAssetUrls(array(
				'banner-772x250.png' => 'high',
				'banner-772x250.jpg' => 'high',
				'banner-1544x500.png' => 'low',
				'banner-1544x500.jpg' => 'low',
			));

			if ( !empty($banners) ) {
				$pluginInfo->banners = $banners;
			}
		}

		/**
		 * @param array<string, string> $filesToKeys
		 * @return array<string, string>
		 */
		protected function getLocalAssetUrls($filesToKeys) {
			$assetDirectory = $this->package->getAbsoluteDirectoryPath() . DIRECTORY_SEPARATOR . 'assets';
			if ( !is_dir($assetDirectory) ) {
				return array();
			}
			$assetBaseUrl = trailingslashit(plugins_url('', $assetDirectory . '/imaginary.file'));

			$foundAssets = array();
			foreach ($filesToKeys as $fileName => $key) {
				$fullBannerPath = $assetDirectory . DIRECTORY_SEPARATOR . $fileName;
				if ( !isset($icons[$key]) && is_file($fullBannerPath) ) {
					$foundAssets[$key] = $assetBaseUrl . $fileName;
				}
			}

			return $foundAssets;
		}
	}

endif;
