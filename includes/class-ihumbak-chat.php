<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Chat {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ihumbak_Chat_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'IHUMBAK_CHAT_VERSION' ) ) {
			$this->version = IHUMBAK_CHAT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ihumbak-chat';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-chat-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-chat-i18n.php';

		/**
		 * The class responsible for settings and options management.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-settings.php';

		/**
		 * The class responsible for security and validation.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-security.php';

		/**
		 * The class responsible for message custom post type.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-messages.php';

		/**
		 * The class responsible for email notifications.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-email.php';

		/**
		 * The class responsible for REST API endpoints.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-rest-api.php';

		/**
		 * The class responsible for public-facing functionality.
		 */
		require_once IHUMBAK_CHAT_PLUGIN_DIR . 'includes/class-ihumbak-public.php';

		$this->loader = new Ihumbak_Chat_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ihumbak_Chat_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Ihumbak_Chat_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		// Register custom post type and meta fields.
		$messages = new Ihumbak_Messages();
		$this->loader->add_action( 'init', $messages, 'register_cpt' );
		$this->loader->add_action( 'init', $messages, 'register_post_meta' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		// Register REST API routes.
		$rest_api = new Ihumbak_REST_API();
		$this->loader->add_action( 'rest_api_init', $rest_api, 'register_routes' );

		// Register public-facing functionality.
		$plugin_public = new Ihumbak_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'render_widget' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ihumbak_Chat_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
