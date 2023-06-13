<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1\Vcs;

use YahnisElsts\PluginUpdateChecker\v5p1\Theme;
use YahnisElsts\PluginUpdateChecker\v5p1\Utils;

if ( !class_exists(ThemeUpdateChecker::class, false) ):

	class ThemeUpdateChecker extends Theme\UpdateChecker implements BaseChecker {
		use VcsCheckerMethods;

		/**
		 * ThemeUpdateChecker constructor.
		 *
		 * @param Api $api
		 * @param null $stylesheet
		 * @param null $customSlug
		 * @param int $checkPeriod
		 * @param string $optionName
		 */
		public function __construct($api, $stylesheet = null, $customSlug = null, $checkPeriod = 12, $optionName = '') {
			$this->api = $api;

			parent::__construct($api->getRepositoryUrl(), $stylesheet, $customSlug, $checkPeriod, $optionName);

			$this->api->setHttpFilterName($this->getUniqueName('request_update_options'));
			$this->api->setStrategyFilterName($this->getUniqueName('vcs_update_detection_strategies'));
			$this->api->setSlug($this->slug);
		}

		public function requestUpdate() {
			$api = $this->api;
			$api->setLocalDirectory($this->package->getAbsoluteDirectoryPath());

			$update = new Theme\Update();
			$update->slug = $this->slug;

			//Figure out which reference (tag or branch) we'll use to get the latest version of the theme.
			$updateSource = $api->chooseReference($this->branch);
			if ( $updateSource ) {
				$ref = $updateSource->name;
				$update->download_url = $updateSource->downloadUrl;
			} else {
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
				$ref = $this->branch;
			}

			//Get headers from the main stylesheet in this branch/tag. Its "Version" header and other metadata
			//are what the WordPress install will actually see after upgrading, so they take precedence over releases/tags.
			$remoteHeader = $this->package->getFileHeader($api->getRemoteFile('style.css', $ref));
			$update->version = Utils::findNotEmpty(array(
				$remoteHeader['Version'],
				Utils::get($updateSource, 'version'),
			));

			//The details URL defaults to the Theme URI header or the repository URL.
			$update->details_url = Utils::findNotEmpty(array(
				$remoteHeader['ThemeURI'],
				$this->package->getHeaderValue('ThemeURI'),
				$this->metadataUrl,
			));

			if ( empty($update->version) ) {
				//It looks like we didn't find a valid update after all.
				$update = null;
			}

			$update = $this->filterUpdateResult($update);
			return $update;
		}
	}

endif;
