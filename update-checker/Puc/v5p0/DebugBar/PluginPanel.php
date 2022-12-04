<?php
namespace YahnisElsts\PluginUpdateChecker\v5p0\DebugBar;

use YahnisElsts\PluginUpdateChecker\v5p0\Plugin\UpdateChecker;

if ( !class_exists(PluginPanel::class, false) ):

	class PluginPanel extends Panel {
		/**
		 * @var UpdateChecker
		 */
		protected $updateChecker;

		protected function displayConfigHeader() {
			$this->row('Plugin file', htmlentities($this->updateChecker->pluginFile));
			parent::displayConfigHeader();
		}

		protected function getMetadataButton() {
			$requestInfoButton = '';
			if ( function_exists('get_submit_button') ) {
				$requestInfoButton = get_submit_button(
					'Request Info',
					'secondary',
					'puc-request-info-button',
					false,
					array('id' => $this->updateChecker->getUniqueName('request-info-button'))
				);
			}
			return $requestInfoButton;
		}

		protected function getUpdateFields() {
			return array_merge(
				parent::getUpdateFields(),
				array('homepage', 'upgrade_notice', 'tested',)
			);
		}
	}

endif;
