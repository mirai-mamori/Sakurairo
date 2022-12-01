<?php

namespace YahnisElsts\PluginUpdateChecker\v5p0\Vcs;

if ( !trait_exists(VcsCheckerMethods::class, false) ) :

	trait VcsCheckerMethods {
		/**
		 * @var string The branch where to look for updates. Defaults to "master".
		 */
		protected $branch = 'master';

		/**
		 * @var Api Repository API client.
		 */
		protected $api = null;

		public function setBranch($branch) {
			$this->branch = $branch;
			return $this;
		}

		/**
		 * Set authentication credentials.
		 *
		 * @param array|string $credentials
		 * @return $this
		 */
		public function setAuthentication($credentials) {
			$this->api->setAuthentication($credentials);
			return $this;
		}

		/**
		 * @return Api
		 */
		public function getVcsApi() {
			return $this->api;
		}

		public function getUpdate() {
			$update = parent::getUpdate();

			if ( isset($update) && !empty($update->download_url) ) {
				$update->download_url = $this->api->signDownloadUrl($update->download_url);
			}

			return $update;
		}

		public function onDisplayConfiguration($panel) {
			parent::onDisplayConfiguration($panel);
			$panel->row('Branch', $this->branch);
			$panel->row('Authentication enabled', $this->api->isAuthenticationEnabled() ? 'Yes' : 'No');
			$panel->row('API client', get_class($this->api));
		}
	}

endif;