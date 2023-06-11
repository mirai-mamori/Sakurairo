<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1\Vcs;

if ( !trait_exists(ReleaseFilteringFeature::class, false) ) :

	trait ReleaseFilteringFeature {
		/**
		 * @var callable|null
		 */
		protected $releaseFilterCallback = null;
		/**
		 * @var int
		 */
		protected $releaseFilterMaxReleases = 1;
		/**
		 * @var string One of the Api::RELEASE_FILTER_* constants.
		 */
		protected $releaseFilterByType = Api::RELEASE_FILTER_SKIP_PRERELEASE;

		/**
		 * Set a custom release filter.
		 *
		 * Setting a new filter will override the old filter, if any.
		 *
		 * @param callable $callback A callback that accepts a version number and a release
		 *                           object, and returns a boolean.
		 * @param int $releaseTypes  One of the Api::RELEASE_FILTER_* constants.
		 * @param int $maxReleases   Optional. The maximum number of recent releases to examine
		 *                           when trying to find a release that matches the filter. 1 to 100.
		 * @return $this
		 */
		public function setReleaseFilter(
			$callback,
			$releaseTypes = Api::RELEASE_FILTER_SKIP_PRERELEASE,
			$maxReleases = 20
		) {
			if ( $maxReleases > 100 ) {
				throw new \InvalidArgumentException(sprintf(
					'The max number of releases is too high (%d). It must be 100 or less.',
					$maxReleases
				));
			} else if ( $maxReleases < 1 ) {
				throw new \InvalidArgumentException(sprintf(
					'The max number of releases is too low (%d). It must be at least 1.',
					$maxReleases
				));
			}

			$this->releaseFilterCallback = $callback;
			$this->releaseFilterByType = $releaseTypes;
			$this->releaseFilterMaxReleases = $maxReleases;
			return $this;
		}

		/**
		 * Filter releases by their version number.
		 *
		 * @param string $regex A regular expression. The release version number must match this regex.
		 * @param int $releaseTypes
		 * @param int $maxReleasesToExamine
		 * @return $this
		 * @noinspection PhpUnused -- Public API
		 */
		public function setReleaseVersionFilter(
			$regex,
			$releaseTypes = Api::RELEASE_FILTER_SKIP_PRERELEASE,
			$maxReleasesToExamine = 20
		) {
			return $this->setReleaseFilter(
				function ($versionNumber) use ($regex) {
					return (preg_match($regex, $versionNumber) === 1);
				},
				$releaseTypes,
				$maxReleasesToExamine
			);
		}

		/**
		 * @param string $versionNumber The detected release version number.
		 * @param object $releaseObject Varies depending on the host/API.
		 * @return bool
		 */
		protected function matchesCustomReleaseFilter($versionNumber, $releaseObject) {
			if ( !is_callable($this->releaseFilterCallback) ) {
				return true; //No custom filter.
			}
			return call_user_func($this->releaseFilterCallback, $versionNumber, $releaseObject);
		}

		/**
		 * @return bool
		 */
		protected function shouldSkipPreReleases() {
			//Maybe this could be a bitfield in the future, if we need to support
			//more release types.
			return ($this->releaseFilterByType !== Api::RELEASE_FILTER_ALL);
		}

		/**
		 * @return bool
		 */
		protected function hasCustomReleaseFilter() {
			return isset($this->releaseFilterCallback) && is_callable($this->releaseFilterCallback);
		}
	}

endif;