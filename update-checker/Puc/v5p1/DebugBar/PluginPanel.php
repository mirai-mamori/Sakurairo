<?php
namespace YahnisElsts\PluginUpdateChecker\v5p1\DebugBar;

use YahnisElsts\PluginUpdateChecker\v5p1\Plugin\UpdateChecker;

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
			$buttonId = $this->updateChecker->getUniqueName('request-info-button');
			if ( function_exists('get_submit_button') ) {
				$requestInfoButton = get_submit_button(
					'Request Info',
					'secondary',
					'puc-request-info-button',
					false,
					array('id' => $buttonId)
				);
			} else {
				$requestInfoButton = sprintf(
					'<input type="button" name="puc-request-info-button" id="%1$s" value="%2$s" class="button button-secondary" />',
					esc_attr($buttonId),
					esc_attr('Request Info')
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
