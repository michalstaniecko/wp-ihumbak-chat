<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Handles rendering the widget and enqueuing public assets.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name    The name of the plugin.
	 * @param    string $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			$this->plugin_name,
			IHUMBAK_CHAT_PLUGIN_URL . 'public/css/ihumbak-widget.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			IHUMBAK_CHAT_PLUGIN_URL . 'public/js/ihumbak-widget.js',
			array(),
			$this->version,
			true
		);
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * @since    1.0.0
	 */
	public function render_widget() {
		// Get widget template.
		$template_path = IHUMBAK_CHAT_PLUGIN_DIR . 'public/templates/widget.html';

		if ( ! file_exists( $template_path ) ) {
			return;
		}

		// Load template using WordPress filesystem API.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;
		
		if ( ! $wp_filesystem ) {
			return;
		}

		$template = $wp_filesystem->get_contents( $template_path );

		if ( false === $template ) {
			return;
		}

		// Output template.
		echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
