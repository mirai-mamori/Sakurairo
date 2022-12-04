<?php

namespace YahnisElsts\PluginUpdateChecker\v5p0\Theme;

use YahnisElsts\PluginUpdateChecker\v5p0\UpdateChecker as BaseUpdateChecker;
use YahnisElsts\PluginUpdateChecker\v5p0\InstalledPackage;
use YahnisElsts\PluginUpdateChecker\v5p0\Scheduler;
use YahnisElsts\PluginUpdateChecker\v5p0\DebugBar;

if ( !class_exists(UpdateChecker::class, false) ):

	class UpdateChecker extends BaseUpdateChecker {
		protected $filterSuffix = 'theme';
		protected $updateTransient = 'update_themes';
		protected $translationType = 'theme';

		/**
		 * @var string Theme directory name.
		 */
		protected $stylesheet;

		public function __construct($metadataUrl, $stylesheet = null, $customSlug = null, $checkPeriod = 12, $optionName = '') {
			if ( $stylesheet === null ) {
				$stylesheet = get_stylesheet();
			}
			$this->stylesheet = $stylesheet;

			parent::__construct(
				$metadataUrl,
				$stylesheet,
				$customSlug ? $customSlug : $stylesheet,
				$checkPeriod,
				$optionName
			);
		}

		/**
		 * For themes, the update array is indexed by theme directory name.
		 *
		 * @return string
		 */
		protected function getUpdateListKey() {
			return $this->directoryName;
		}

		/**
		 * Retrieve the latest update (if any) from the configured API endpoint.
		 *
		 * @return Update|null An instance of Update, or NULL when no updates are available.
		 */
		public function requestUpdate() {
			list($themeUpdate, $result) = $this->requestMetadata(Update::class, 'request_update');

			if ( $themeUpdate !== null ) {
				/** @var Update $themeUpdate */
				$themeUpdate->slug = $this->slug;
			}

			$themeUpdate = $this->filterUpdateResult($themeUpdate, $result);
			return $themeUpdate;
		}

		protected function getNoUpdateItemFields() {
			return array_merge(
				parent::getNoUpdateItemFields(),
				array(
					'theme'        => $this->directoryName,
					'requires'     => '',
				)
			);
		}

		public function userCanInstallUpdates() {
			return current_user_can('update_themes');
		}

		/**
		 * Create an instance of the scheduler.
		 *
		 * @param int $checkPeriod
		 * @return Scheduler
		 */
		protected function createScheduler($checkPeriod) {
			return new Scheduler($this, $checkPeriod, array('load-themes.php'));
		}

		/**
		 * Is there an update being installed right now for this theme?
		 *
		 * @param \WP_Upgrader|null $upgrader The upgrader that's performing the current update.
		 * @return bool
		 */
		public function isBeingUpgraded($upgrader = null) {
			return $this->upgraderStatus->isThemeBeingUpgraded($this->stylesheet, $upgrader);
		}

		protected function createDebugBarExtension() {
			return new DebugBar\Extension($this, DebugBar\ThemePanel::class);
		}

		/**
		 * Register a callback for filtering query arguments.
		 *
		 * The callback function should take one argument - an associative array of query arguments.
		 * It should return a modified array of query arguments.
		 *
		 * @param callable $callback
		 * @return void
		 */
		public function addQueryArgFilter($callback){
			$this->addFilter('request_update_query_args', $callback);
		}

		/**
		 * Register a callback for filtering arguments passed to wp_remote_get().
		 *
		 * The callback function should take one argument - an associative array of arguments -
		 * and return a modified array or arguments. See the WP documentation on wp_remote_get()
		 * for details on what arguments are available and how they work.
		 *
		 * @uses add_filter() This method is a convenience wrapper for add_filter().
		 *
		 * @param callable $callback
		 * @return void
		 */
		public function addHttpRequestArgFilter($callback) {
			$this->addFilter('request_update_options', $callback);
		}

		/**
		 * Register a callback for filtering theme updates retrieved from the external API.
		 *
		 * The callback function should take two arguments. If the theme update was retrieved
		 * successfully, the first argument passed will be an instance of Theme_Update. Otherwise,
		 * it will be NULL. The second argument will be the corresponding return value of
		 * wp_remote_get (see WP docs for details).
		 *
		 * The callback function should return a new or modified instance of Theme_Update or NULL.
		 *
		 * @uses add_filter() This method is a convenience wrapper for add_filter().
		 *
		 * @param callable $callback
		 * @return void
		 */
		public function addResultFilter($callback) {
			$this->addFilter('request_update_result', $callback, 10, 2);
		}

		/**
		 * Create a package instance that represents this plugin or theme.
		 *
		 * @return InstalledPackage
		 */
		protected function createInstalledPackage() {
			return new Package($this->stylesheet, $this);
		}
	}

endif;
