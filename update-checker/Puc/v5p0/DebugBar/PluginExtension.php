<?php

namespace YahnisElsts\PluginUpdateChecker\v5p0\DebugBar;

use YahnisElsts\PluginUpdateChecker\v5p0\Plugin\UpdateChecker;

if ( !class_exists(PluginExtension::class, false) ):

	class PluginExtension extends Extension {
		/** @var UpdateChecker */
		protected $updateChecker;

		public function __construct($updateChecker) {
			parent::__construct($updateChecker, PluginPanel::class);

			add_action('wp_ajax_puc_v5_debug_request_info', array($this, 'ajaxRequestInfo'));
		}

		/**
		 * Request plugin info and output it.
		 */
		public function ajaxRequestInfo() {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is checked in preAjaxRequest().
			if ( !isset($_POST['uid']) || ($_POST['uid'] !== $this->updateChecker->getUniqueName('uid')) ) {
				return;
			}
			$this->preAjaxRequest();
			$info = $this->updateChecker->requestInfo();
			if ( $info !== null ) {
				echo 'Successfully retrieved plugin info from the metadata URL:';
				//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- For debugging output.
				echo '<pre>', esc_html(print_r($info, true)), '</pre>';
			} else {
				echo 'Failed to retrieve plugin info from the metadata URL.';
			}
			exit;
		}
	}

endif;
