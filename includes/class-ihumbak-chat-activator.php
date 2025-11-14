<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Chat_Activator {

	/**
	 * Activate the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Set default options if they don't exist.
		if ( false === get_option( 'ihumbak_admin_email' ) ) {
			add_option( 'ihumbak_admin_email', get_option( 'admin_email' ) );
		}

		if ( false === get_option( 'ihumbak_spam_limit' ) ) {
			add_option( 'ihumbak_spam_limit', 5 );
		}

		if ( false === get_option( 'ihumbak_enable_notifications' ) ) {
			add_option( 'ihumbak_enable_notifications', 1 );
		}

		if ( false === get_option( 'ihumbak_widget_position' ) ) {
			add_option( 'ihumbak_widget_position', 'bottom-right' );
		}

		if ( false === get_option( 'ihumbak_widget_color' ) ) {
			add_option( 'ihumbak_widget_color', '#007bff' );
		}

		if ( false === get_option( 'ihumbak_widget_title' ) ) {
			add_option( 'ihumbak_widget_title', 'Get in Touch' );
		}

		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}
