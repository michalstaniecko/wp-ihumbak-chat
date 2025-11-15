<?php
/**
 * REST API endpoints class.
 *
 * Handles registration and management of REST API endpoints.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * REST API endpoints class.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_REST_API {

	/**
	 * API namespace.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	private $namespace = 'ihumbak-chat/v1';

	/**
	 * Register REST API routes.
	 *
	 * @since    1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/send-message',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'send_message' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'email'           => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_email',
						'validate_callback' => 'is_email',
					),
					'message'         => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_textarea_field',
					),
					'recaptcha_token' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'nonce'           => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Handle send message endpoint.
	 *
	 * @since    1.0.0
	 * @param    WP_REST_Request $request    Request object.
	 * @return   WP_REST_Response|WP_Error   Response object.
	 */
	public function send_message( $request ) {
		$security      = new Ihumbak_Security();
		$messages      = new Ihumbak_Messages();
		$email_handler = new Ihumbak_Email();

		// Verify nonce.
		$nonce = $request->get_param( 'nonce' );
		if ( ! $security->verify_nonce( $nonce ) ) {
			return new WP_Error(
				'invalid_nonce',
				__( 'Invalid security token.', 'ihumbak-chat' ),
				array( 'status' => 400 )
			);
		}

		// Get client IP.
		$client_ip = $security->get_client_ip();

		// Check rate limit.
		if ( ! $security->check_rate_limit( $client_ip ) ) {
			return new WP_Error(
				'rate_limit_exceeded',
				__( 'Too many requests. Please try again later.', 'ihumbak-chat' ),
				array( 'status' => 429 )
			);
		}

		// Get and validate parameters.
		$email           = $request->get_param( 'email' );
		$message         = $request->get_param( 'message' );
		$recaptcha_token = $request->get_param( 'recaptcha_token' );

		// Validate email.
		if ( ! $security->validate_email( $email ) ) {
			return new WP_Error(
				'invalid_email',
				__( 'Invalid email address.', 'ihumbak-chat' ),
				array( 'status' => 400 )
			);
		}

		// Validate message length.
		if ( empty( $message ) || strlen( $message ) < 10 ) {
			return new WP_Error(
				'invalid_message',
				__( 'Message must be at least 10 characters long.', 'ihumbak-chat' ),
				array( 'status' => 400 )
			);
		}

		if ( strlen( $message ) > 5000 ) {
			return new WP_Error(
				'message_too_long',
				__( 'Message is too long. Maximum 5000 characters.', 'ihumbak-chat' ),
				array( 'status' => 400 )
			);
		}

		// Verify reCAPTCHA.
		$recaptcha_result = $security->verify_recaptcha( $recaptcha_token );
		if ( is_wp_error( $recaptcha_result ) ) {
			return new WP_Error(
				'recaptcha_failed',
				__( 'Security verification failed. Please try again.', 'ihumbak-chat' ),
				array( 'status' => 400 )
			);
		}

		// Sanitize message.
		$sanitized_message = $security->sanitize_message( $message );

		// Prepare message data.
		$message_data = array(
			'email'      => $email,
			'message'    => $sanitized_message,
			'ip'         => $client_ip,
			'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
			'referer'    => isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '',
		);

		// Create message post.
		$post_id = $messages->create_message( $message_data );

		if ( is_wp_error( $post_id ) ) {
			return new WP_Error(
				'save_failed',
				__( 'Failed to save message. Please try again.', 'ihumbak-chat' ),
				array( 'status' => 500 )
			);
		}

		// Add post ID to message data for email.
		$message_data['post_id'] = $post_id;

		// Send email notification.
		$email_handler->send_admin_notification( $message_data );

		// Return success response.
		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Your message has been sent successfully. We will get back to you soon!', 'ihumbak-chat' ),
			),
			200
		);
	}

	/**
	 * Handle get settings endpoint.
	 *
	 * @since    1.0.0
	 * @param    WP_REST_Request $request    Request object.
	 * @return   WP_REST_Response            Response object.
	 */
	public function get_settings( $request ) {
		$settings = new Ihumbak_Settings();
		$security = new Ihumbak_Security();

		// Only return public settings.
		$public_settings = array(
			'recaptcha_site_key' => $settings->get_option( 'ihumbak_recaptcha_site_key', '' ),
			'widget_color'       => $settings->get_option( 'ihumbak_widget_color', '#007bff' ),
			'widget_position'    => $settings->get_option( 'ihumbak_widget_position', 'bottom-right' ),
			'widget_title'       => $settings->get_option( 'ihumbak_widget_title', __( 'Get in Touch', 'ihumbak-chat' ) ),
			'nonce'              => $security->generate_nonce(),
		);

		return new WP_REST_Response( $public_settings, 200 );
	}
}
