<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1;

use WP_CLI;

/**
 * Triggers an update check when relevant WP-CLI commands are executed.
 *
 * When WP-CLI runs certain commands like "wp plugin status" or "wp theme list", it calls
 * wp_update_plugins() and wp_update_themes() to refresh update information. This class hooks into
 * the same commands and triggers an update check when they are executed.
 *
 * Note that wp_update_plugins() and wp_update_themes() do not perform an update check *every* time
 * they are called. They use a context-dependent delay between update checks. Similarly, this class
 * calls Scheduler::maybeCheckForUpdates(), which also dynamically decides whether to actually
 * run a check. If you want to force an update check, call UpdateChecker::checkForUpdates() instead.
 */
class WpCliCheckTrigger {
	/**
	 * @var Scheduler
	 */
	private $scheduler;

	/**
	 * @var string 'plugin' or 'theme'
	 */
	private $componentType;

	/**
	 * @var bool Whether an update check was already triggered during the current request
	 *           or script execution.
	 */
	private $wasCheckTriggered = false;

	public function __construct($componentType, Scheduler $scheduler) {
		if ( !in_array($componentType, ['plugin', 'theme']) ) {
			throw new \InvalidArgumentException('Invalid component type. Must be "plugin" or "theme".');
		}

		$this->componentType = $componentType;
		$this->scheduler = $scheduler;

		if ( !defined('WP_CLI') || !class_exists(WP_CLI::class, false) ) {
			return; //Nothing to do if WP-CLI is not available.
		}

		/*
		 * We can't hook directly into wp_update_plugins(), but we can hook into the WP-CLI
		 * commands that call it. We'll use the "before_invoke:xyz" hook to trigger update checks.
		 */
		foreach ($this->getRelevantCommands() as $command) {
			WP_CLI::add_hook('before_invoke:' . $command, [$this, 'triggerUpdateCheckOnce']);
		}
	}

	private function getRelevantCommands() {
		$result = [];
		foreach (['status', 'list', 'update'] as $subcommand) {
			$result[] = $this->componentType . ' ' . $subcommand;
		}
		return $result;
	}

	/**
	 * Trigger a potential update check once.
	 *
	 * @param mixed $input
	 * @return mixed The input value, unchanged.
	 * @internal This method is public so that it can be used as a WP-CLI hook callback.
	 *           It should not be called directly.
	 *
	 */
	public function triggerUpdateCheckOnce($input = null) {
		if ( $this->wasCheckTriggered ) {
			return $input;
		}

		$this->wasCheckTriggered = true;
		$this->scheduler->maybeCheckForUpdates();

		return $input;
	}
}