<?php
/**
 * Class to setup Kirki admin notices.
 *
 * @package Kirki
 */

namespace Kirki\Settings;

/**
 * Class to setup Kirki admin notices.
 */
class Notice {

	/**
	 * Setting up hooks.
	 */
	public function __construct() {

		// Uncomment line below to reset the notices, and don't forget to comment it out again after reloading the browser.
		// delete_option( 'kirki_notices' );

		add_action( 'admin_notices', array( $this, 'discount_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'discount_notice_script' ) );
		add_action( 'wp_ajax_kirki_dismiss_discount_notice', array( $this, 'dismiss_discount_notice' ) );

	}

	/**
	 * Check if we're on the Kirki settings page.
	 *
	 * @return bool
	 */
	public function is_settings_page() {

		$current_screen = get_current_screen();

		return ( 'settings_page_kirki_settings' === $current_screen->id ? true : false );

	}

	/**
	 * Check if we should show the a notice.
	 *
	 * @param string $type The notice type.
	 * @return bool
	 */
	public function should_show_notice( $type = 'discount' ) {

		$notices = get_option( 'kirki_notices', [] );

		// Stop here if notice has been dismissed.
		if ( isset( $notices[ $type . '_notice' ] ) ) {
			return false;
		}

		// Stop here if we're on our settings page.
		if ( $this->is_settings_page() ) {
			return false;
		}

		// Stop here if current user can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Discount notice.
	 */
	public function discount_notice() {

		// Stop here if the notice shouldn't be shown.
		if ( ! $this->should_show_notice( 'discount' ) ) {
			return;
		}
		?>

		<div
			class="notice notice-info kirki-admin-notice kirki-discount-notice is-dismissible"
			data-dismiss-nonce="<?php echo esc_attr( wp_create_nonce( 'Kirki_Dismiss_Discount_Notice' ) ); ?>"
		>

			<div class="notice-body">
				<div class="notice-icon">
					<img src="<?php echo esc_url( KIRKI_PLUGIN_URL ); ?>/assets/images/kirki-logo.jpg">
				</div>
				<div class="notice-content">
					<h2>
						Font Display Issues? Clear the Font Cache in Kirki
					</h2>
					<p>
						<strong>New!</strong> Easily resolve font display issues by clearing the <strong>Font Cache</strong> in Kirki. This one-click solution fixes problems caused by domain name changes or site migrations.
					</p>
					<p>
						<a href="<?php echo admin_url( '/options-general.php?page=kirki_settings' ); ?>" style="font-weight: 700;" class="button button-primary">Learn more</a>
					</p>
				</div>
			</div>

		</div>

		<?php

	}

	/**
	 * Script that handles discount notice dismissal.
	 */
	public function discount_notice_script() {

		// Stop here if the notice shouldn't be shown.
		if ( ! $this->should_show_notice( 'discount' ) ) {
			return;
		}

		wp_enqueue_style( 'kirki-admin-notice', KIRKI_PLUGIN_URL . '/kirki-packages/settings/dist/admin-notice.css', array(), KIRKI_VERSION );
		wp_enqueue_script( 'kirki-discount-notice', KIRKI_PLUGIN_URL . '/kirki-packages/settings/dist/discount-notice.js', array( 'jquery' ), KIRKI_VERSION, true );

	}

	/**
	 * Dismiss discount notice.
	 */
	public function dismiss_discount_notice() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'Kirki_Dismiss_Discount_Notice' ) ) {
			wp_send_json_error( __( 'Invalid nonce', 'kirki' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( "You don't have capability to run this action", 'kirki' ) );
		}

		$notices = get_option( 'kirki_notices', [] );

		$notices['discount_notice'] = 1;

		update_option( 'kirki_notices', $notices );
		wp_send_json_success( __( 'Discount notice has been dismissed', 'kirki' ) );
	}

}
