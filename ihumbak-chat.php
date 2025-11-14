<?php
/**
 * Plugin Name: iHumbak Chat - Quick Admin Communication
 * Plugin URI: https://github.com/michalstaniecko/wp-ihumbak-chat
 * Description: A floating chat widget that enables quick communication between website visitors and administrators via a simple contact form.
 * Version: 1.0.0
 * Author: Michał Staniecko
 * Author URI: https://github.com/michalstaniecko
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ihumbak-chat
 * Domain Path: /languages
 * Requires at least: 5.9
 * Requires PHP: 7.4
 *
 * @package IhumbakChat
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'IHUMBAK_CHAT_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 */
define( 'IHUMBAK_CHAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'IHUMBAK_CHAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_ihumbak_chat() {
	require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-chat-activator.php';
	Ihumbak_Chat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_ihumbak_chat() {
	require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-chat-deactivator.php';
	Ihumbak_Chat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ihumbak_chat' );
register_deactivation_hook( __FILE__, 'deactivate_ihumbak_chat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-chat.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_ihumbak_chat() {
	$plugin = new Ihumbak_Chat();
	$plugin->run();
}
run_ihumbak_chat();
