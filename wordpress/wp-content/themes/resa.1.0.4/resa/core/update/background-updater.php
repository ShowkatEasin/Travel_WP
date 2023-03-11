<?php
/**
 * Theme Batch Update
 *
 */

if (!class_exists('Resa_Theme_Background_Updater')) {

	/**
	 * Resa_Theme_Background_Updater Class.
	 */
	class Resa_Theme_Background_Updater
	{

		/**
		 * Background update class.
		 *
		 * @var object
		 */
		private static $background_updater;

		/**
		 * DB updates and callbacks that need to be run per version.
		 *
		 * @var array
		 */
		private static $db_updates = array(

			'1.0.3' => array(
				'resa_103_site_identity_update',
			),
		);

		/**
		 *  Constructor
		 */
		public function __construct()
		{

			// Theme Updates.
			if (is_admin()) {
				add_action('admin_init', array($this, 'install_actions'));
			} else {
				add_action('wp', array($this, 'install_actions'));
			}

			//Helper functions
			require_once RESA_THEME_DIR . '/core/update/update-helpers.php';
			// Core Helpers - Batch Processing.
			require_once RESA_THEME_DIR . '/core/third-party/batch/wp-async-request.php';
			require_once RESA_THEME_DIR . '/core/third-party/batch/wp-background-process.php';
			require_once RESA_THEME_DIR . '/core/update/background-process.php';

			self::$background_updater = new Resa_Theme_WP_Background_Process();

		}

		/**
		 * Check Cron Status
		 *
		 * Gets the current cron status by performing a test spawn. Cached for one hour when all is well.
		 *
		 * @return bool true if there is a problem spawning a call to WP-Cron system, else false.
		 * @since 1.0.3
		 *
		 */
		public function test_cron()
		{

			global $wp_version;

			if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON) {
				return true;
			}

			if (defined('ALTERNATE_WP_CRON') && ALTERNATE_WP_CRON) {
				return true;
			}

			$cached_status = get_transient('resa_theme_is_cron_test_ok');

			if ($cached_status) {
				return false;
			}

			$sslverify = version_compare($wp_version, 4.0, '<');
			$doing_wp_cron = sprintf('%.22F', microtime(true));

			$cron_request = apply_filters(
				'cron_request', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				array(
					'url' => site_url('wp-cron.php?doing_wp_cron=' . $doing_wp_cron),
					'args' => array(
						'timeout' => 3,
						'blocking' => true,
						'sslverify' => apply_filters('https_local_ssl_verify', $sslverify), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
					),
				)
			);

			$result = wp_remote_post($cron_request['url'], $cron_request['args']);

			if (wp_remote_retrieve_response_code($result) >= 300) {
				return true;
			} else {
				set_transient('resa_theme_is_cron_test_ok', 1, 3600);
				return false;
			}

			return $migration_fallback;
		}

		/**
		 * Install actions when a update button is clicked within the admin area.
		 *
		 * This function is hooked into admin_init to affect admin and wp to affect the frontend.
		 */
		public function install_actions()
		{

			do_action('resa_update_initiated', self::$background_updater);

			if (true === $this->is_new_install()) {
				self::update_db_version();
				return;
			}

			$fallback = $this->test_cron();
			$db_migrated = $this->check_if_data_migrated();
			$is_queue_running = resa()->options->get('resa_theme_is_queue_running', false);

			$fallback = ($db_migrated) ? $db_migrated : $fallback;

			if ($this->needs_db_update() && !$is_queue_running) {
				$this->update($fallback);
			} else {
				if (!$is_queue_running) {
					self::update_db_version();
				}
			}
		}

		/**
		 * Is this a brand new theme install?
		 *
		 * @return boolean
		 * @since 2.1.3
		 */
		public function is_new_install()
		{
			$saved_version = resa()->options->get('resa_theme_auto_version', false);

			if (false === $saved_version) {
				return true;
			}

			return false;
		}

		/**
		 * Is a DB update needed?
		 *
		 * @return boolean
		 * @since 2.1.3
		 */
		private function needs_db_update()
		{
			$current_theme_version = resa()->options->get('resa_theme_auto_version', null);
			$updates = $this->get_db_update_callbacks();

			if (empty($updates)) {
				return false;
			}

			return !is_null($current_theme_version) && version_compare($current_theme_version, max(array_keys($updates)), '<');
		}

		/**
		 * Get list of DB update callbacks.
		 *
		 * @return array
		 * @since 2.1.3
		 */
		public function get_db_update_callbacks()
		{
			return self::$db_updates;
		}

		/**
		 * Check if database is migrated
		 *
		 * @return true If the database migration should not be run through CRON.
		 * @since 2.3.1
		 *
		 */
		public function check_if_data_migrated()
		{

			$fallback = false;

			$is_db_version_updated = $this->is_db_version_updated();

			if (!$is_db_version_updated) {

				$db_migrated = get_transient('resa_theme_db_migrated');

				if (!$db_migrated) {
					$db_migrated = array();
				}

				array_push($db_migrated, $is_db_version_updated);
				set_transient('resa_theme_db_migrated', $db_migrated, 3600);

				$db_migrate_count = count($db_migrated);
				if ($db_migrate_count >= 5) {
					resa()->options->delete('resa_theme_is_queue_running');
					$fallback = true;
				}
			}
			return $fallback;
		}

		public function is_db_version_updated()
		{
			// Get auto saved version number.
			$saved_version = resa()->options->get('resa_theme_auto_version', false);

			return version_compare($saved_version, RESA_THEME_VERSION, '=');
		}


		/**
		 * Push all needed DB updates to the queue for processing.
		 *
		 * @param bool $fallback Fallback migration.
		 *
		 * @return void
		 */
		private function update($fallback)
		{
			$current_db_version = resa()->options->get('resa_theme_auto_version');

			if (count($this->get_db_update_callbacks()) > 0) {
				foreach ($this->get_db_update_callbacks() as $version => $update_callbacks) {
					if (version_compare($current_db_version, $version, '<')) {
						foreach ($update_callbacks as $update_callback) {
							if ($fallback) {
								call_user_func($update_callback);
							} else {
								self::$background_updater->push_to_queue($update_callback);
							}
						}
					}
				}
				if ($fallback) {
					self::update_db_version();
				} else {
					resa()->options->set('resa_theme_is_queue_running', true);
					self::$background_updater->push_to_queue('update_db_version');
				}
			} else {
				self::$background_updater->push_to_queue('update_db_version');
			}
			self::$background_updater->save()->dispatch();
		}

		/**
		 * Update DB version to current.
		 *
		 * @param string|null $version New Resa theme version or null.
		 */
		public static function update_db_version($version = null)
		{

			do_action('resa_theme_update_before');

			$saved_version = resa()->options->get('resa_theme_auto_version', false);

			if (false === $saved_version) {

				$saved_version = RESA_THEME_VERSION;

				// Update auto saved version number.
				resa()->options->set('resa_theme_auto_version', RESA_THEME_VERSION);
			}

			// If equals then return.
			if (version_compare($saved_version, RESA_THEME_VERSION, '=')) {
				resa()->options->set('resa_theme_is_queue_running', false);
				return;
			}

			// Get latest version.
			$theme_version = RESA_THEME_VERSION;

			resa()->options->set('resa_theme_auto_version', $theme_version);

			resa()->options->set('resa_theme_is_queue_running', false);

			// Update variables.
			resa()->options->refresh();

			delete_transient('resa-addon-db-migrated');

			do_action('resa_theme_update_after');
		}
	}
}


/**
 * Kicking this off by creating a new instance
 */
new Resa_Theme_Background_Updater();
