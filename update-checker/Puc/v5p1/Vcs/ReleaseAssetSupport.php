<?php

namespace YahnisElsts\PluginUpdateChecker\v5p1\Vcs;

if ( !trait_exists(ReleaseAssetSupport::class, false) ) :

	trait ReleaseAssetSupport {
		/**
		 * @var bool Whether to download release assets instead of the auto-generated
		 *           source code archives.
		 */
		protected $releaseAssetsEnabled = false;

		/**
		 * @var string|null Regular expression that's used to filter release assets
		 *                  by file name or URL. Optional.
		 */
		protected $assetFilterRegex = null;

		/**
		 * How to handle releases that don't have any matching release assets.
		 *
		 * @var int
		 */
		protected $releaseAssetPreference = Api::PREFER_RELEASE_ASSETS;

		/**
		 * Enable updating via release assets.
		 *
		 * If the latest release contains no usable assets, the update checker
		 * will fall back to using the automatically generated ZIP archive.
		 *
		 * @param string|null $nameRegex Optional. Use only those assets where
		 *                               the file name or URL matches this regex.
		 * @param int $preference Optional. How to handle releases that don't have
		 *                        any matching release assets.
		 */
		public function enableReleaseAssets($nameRegex = null, $preference = Api::PREFER_RELEASE_ASSETS) {
			$this->releaseAssetsEnabled = true;
			$this->assetFilterRegex = $nameRegex;
			$this->releaseAssetPreference = $preference;
		}

		/**
		 * Disable release assets.
		 *
		 * @return void
		 * @noinspection PhpUnused -- Public API
		 */
		public function disableReleaseAssets() {
			$this->releaseAssetsEnabled = false;
			$this->assetFilterRegex = null;
		}

		/**
		 * Does the specified asset match the name regex?
		 *
		 * @param mixed $releaseAsset Data type and structure depend on the host/API.
		 * @return bool
		 */
		protected function matchesAssetFilter($releaseAsset) {
			if ( $this->assetFilterRegex === null ) {
				//The default is to accept all assets.
				return true;
			}

			$name = $this->getFilterableAssetName($releaseAsset);
			if ( !is_string($name) ) {
				return false;
			}
			return (bool)preg_match($this->assetFilterRegex, $releaseAsset->name);
		}

		/**
		 * Get the part of asset data that will be checked against the filter regex.
		 *
		 * @param mixed $releaseAsset
		 * @return string|null
		 */
		abstract protected function getFilterableAssetName($releaseAsset);
	}

endif;