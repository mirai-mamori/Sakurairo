<?php
namespace YahnisElsts\PluginUpdateChecker\v5p0\DebugBar;

use YahnisElsts\PluginUpdateChecker\v5p0\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p0\UpdateChecker;

if ( !class_exists(Extension::class, false) ):

	class Extension {
		const RESPONSE_BODY_LENGTH_LIMIT = 4000;

		/** @var UpdateChecker */
		protected $updateChecker;
		protected $panelClass = Panel::class;

		public function __construct($updateChecker, $panelClass = null) {
			$this->updateChecker = $updateChecker;
			if ( isset($panelClass) ) {
				$this->panelClass = $panelClass;
			}

			if ( (strpos($this->panelClass, '\\') === false) ) {
				$this->panelClass = __NAMESPACE__ . '\\' . $this->panelClass;
			}

			add_filter('debug_bar_panels', array($this, 'addDebugBarPanel'));
			add_action('debug_bar_enqueue_scripts', array($this, 'enqueuePanelDependencies'));

			add_action('wp_ajax_puc_v5_debug_check_now', array($this, 'ajaxCheckNow'));
		}

		/**
		 * Register the PUC Debug Bar panel.
		 *
		 * @param array $panels
		 * @return array
		 */
		public function addDebugBarPanel($panels) {
			if ( $this->updateChecker->userCanInstallUpdates() ) {
				$panels[] = new $this->panelClass($this->updateChecker);
			}
			return $panels;
		}

		/**
		 * Enqueue our Debug Bar scripts and styles.
		 */
		public function enqueuePanelDependencies() {
			wp_enqueue_style(
				'puc-debug-bar-style-v5',
				$this->getLibraryUrl("/css/puc-debug-bar.css"),
				array('debug-bar'),
				'20221008'
			);

			wp_enqueue_script(
				'puc-debug-bar-js-v5',
				$this->getLibraryUrl("/js/debug-bar.js"),
				array('jquery'),
				'20221008'
			);
		}

		/**
		 * Run an update check and output the result. Useful for making sure that
		 * the update checking process works as expected.
		 */
		public function ajaxCheckNow() {
			//phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce is checked in preAjaxRequest().
			if ( !isset($_POST['uid']) || ($_POST['uid'] !== $this->updateChecker->getUniqueName('uid')) ) {
				return;
			}
			$this->preAjaxRequest();
			$update = $this->updateChecker->checkForUpdates();
			if ( $update !== null ) {
				echo "An update is available:";
				//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- For debugging output.
				echo '<pre>', esc_html(print_r($update, true)), '</pre>';
			} else {
				echo 'No updates found.';
			}

			$errors = $this->updateChecker->getLastRequestApiErrors();
			if ( !empty($errors) ) {
				printf('<p>The update checker encountered %d API error%s.</p>', count($errors), (count($errors) > 1) ? 's' : '');

				foreach (array_values($errors) as $num => $item) {
					$wpError = $item['error'];
					/** @var \WP_Error $wpError */
					printf('<h4>%d) %s</h4>', intval($num + 1), esc_html($wpError->get_error_message()));

					echo '<dl>';
					printf('<dt>Error code:</dt><dd><code>%s</code></dd>', esc_html($wpError->get_error_code()));

					if ( isset($item['url']) ) {
						printf('<dt>Requested URL:</dt><dd><code>%s</code></dd>', esc_html($item['url']));
					}

					if ( isset($item['httpResponse']) ) {
						if ( is_wp_error($item['httpResponse']) ) {
							$httpError = $item['httpResponse'];
							/** @var \WP_Error $httpError */
							printf(
								'<dt>WordPress HTTP API error:</dt><dd>%s (<code>%s</code>)</dd>',
								esc_html($httpError->get_error_message()),
								esc_html($httpError->get_error_code())
							);
						} else {
							//Status code.
							printf(
								'<dt>HTTP status:</dt><dd><code>%d %s</code></dd>',
								esc_html(wp_remote_retrieve_response_code($item['httpResponse'])),
								esc_html(wp_remote_retrieve_response_message($item['httpResponse']))
							);

							//Headers.
							echo '<dt>Response headers:</dt><dd><pre>';
							foreach (wp_remote_retrieve_headers($item['httpResponse']) as $name => $value) {
								printf("%s: %s\n", esc_html($name), esc_html($value));
							}
							echo '</pre></dd>';

							//Body.
							$body = wp_remote_retrieve_body($item['httpResponse']);
							if ( $body === '' ) {
								$body = '(Empty response.)';
							} else if ( strlen($body) > self::RESPONSE_BODY_LENGTH_LIMIT ) {
								$length = strlen($body);
								$body = substr($body, 0, self::RESPONSE_BODY_LENGTH_LIMIT)
									. sprintf("\n(Long string truncated. Total length: %d bytes.)", $length);
							}

							printf('<dt>Response body:</dt><dd><pre>%s</pre></dd>', esc_html($body));
						}
					}
					echo '<dl>';
				}
			}

			exit;
		}

		/**
		 * Check access permissions and enable error display (for debugging).
		 */
		protected function preAjaxRequest() {
			if ( !$this->updateChecker->userCanInstallUpdates() ) {
				die('Access denied');
			}
			check_ajax_referer('puc-ajax');

			//phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_error_reporting -- Part of a debugging feature.
			error_reporting(E_ALL);
			//phpcs:ignore WordPress.PHP.IniSet.display_errors_Blacklisted
			@ini_set('display_errors', 'On');
		}

		/**
		 * Remove hooks that were added by this extension.
		 */
		public function removeHooks() {
			remove_filter('debug_bar_panels', array($this, 'addDebugBarPanel'));
			remove_action('debug_bar_enqueue_scripts', array($this, 'enqueuePanelDependencies'));
			remove_action('wp_ajax_puc_v5_debug_check_now', array($this, 'ajaxCheckNow'));
		}

		/**
		 * @param string $filePath
		 * @return string
		 */
		private function getLibraryUrl($filePath) {
			$absolutePath = realpath(dirname(__FILE__) . '/../../../' . ltrim($filePath, '/'));

			//Where is the library located inside the WordPress directory structure?
			$absolutePath = PucFactory::normalizePath($absolutePath);

			$pluginDir = PucFactory::normalizePath(WP_PLUGIN_DIR);
			$muPluginDir = PucFactory::normalizePath(WPMU_PLUGIN_DIR);
			$themeDir = PucFactory::normalizePath(get_theme_root());

			if ( (strpos($absolutePath, $pluginDir) === 0) || (strpos($absolutePath, $muPluginDir) === 0) ) {
				//It's part of a plugin.
				return plugins_url(basename($absolutePath), $absolutePath);
			} else if ( strpos($absolutePath, $themeDir) === 0 ) {
				//It's part of a theme.
				$relativePath = substr($absolutePath, strlen($themeDir) + 1);
				$template = substr($relativePath, 0, strpos($relativePath, '/'));
				$baseUrl = get_theme_root_uri($template);

				if ( !empty($baseUrl) && $relativePath ) {
					return $baseUrl . '/' . $relativePath;
				}
			}

			return '';
		}
	}

endif;
