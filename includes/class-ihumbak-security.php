<?php
/**
 * Security and validation class.
 *
 * Handles email validation, message sanitization, reCAPTCHA verification,
 * rate limiting, and nonce management.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * Security and validation class.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Security {

	/**
	 * Validate email address format.
	 *
	 * @since    1.0.0
	 * @param    string $email    Email address to validate.
	 * @return   bool             True if valid, false otherwise.
	 */
	public function validate_email( $email ) {
		return is_email( $email );
	}

	/**
	 * Sanitize message content.
	 *
	 * @since    1.0.0
	 * @param    string $message    Message to sanitize.
	 * @return   string             Sanitized message.
	 */
	public function sanitize_message( $message ) {
		return sanitize_textarea_field( $message );
	}

	/**
	 * Verify reCAPTCHA v3 token.
	 *
	 * @since    1.0.0
	 * @param    string $token    reCAPTCHA token to verify.
	 * @return   bool|WP_Error    True if verified, WP_Error on failure.
	 */
	public function verify_recaptcha( $token ) {
		$settings = new Ihumbak_Settings();
		$secret   = $settings->get_option( 'ihumbak_recaptcha_secret_key' );

		if ( empty( $secret ) ) {
			return new WP_Error( 'recaptcha_not_configured', __( 'reCAPTCHA is not configured.', 'ihumbak-chat' ) );
		}

		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'timeout' => 5,
				'body'    => array(
					'secret'   => $secret,
					'response' => $token,
					'remoteip' => $this->get_client_ip(),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'recaptcha_request_failed', __( 'Failed to verify reCAPTCHA.', 'ihumbak-chat' ) );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body );

		if ( ! $data || ! isset( $data->success ) || ! $data->success ) {
			return new WP_Error( 'recaptcha_verification_failed', __( 'reCAPTCHA verification failed.', 'ihumbak-chat' ) );
		}

		// Check score (minimum 0.5 for v3).
		if ( isset( $data->score ) && $data->score < 0.5 ) {
			return new WP_Error( 'recaptcha_low_score', __( 'reCAPTCHA score too low.', 'ihumbak-chat' ) );
		}

		return true;
	}

	/**
	 * Check rate limit for IP address.
	 *
	 * @since    1.0.0
	 * @param    string $ip    IP address to check.
	 * @return   bool          True if within limit, false if exceeded.
	 */
	public function check_rate_limit( $ip ) {
		$settings    = new Ihumbak_Settings();
		$limit       = $settings->get_option( 'ihumbak_spam_limit', 5 );
		$transient   = 'ihumbak_rate_limit_' . md5( $ip );
		$submissions = get_transient( $transient );

		if ( false === $submissions ) {
			// First submission.
			set_transient( $transient, 1, HOUR_IN_SECONDS );
			return true;
		}

		if ( $submissions >= $limit ) {
			// Rate limit exceeded.
			return false;
		}

		// Increment counter.
		set_transient( $transient, $submissions + 1, HOUR_IN_SECONDS );
		return true;
	}

	/**
	 * Get client IP address.
	 *
	 * @since    1.0.0
	 * @return   string    Client IP address.
	 */
	public function get_client_ip() {
		$ip = '';

		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && filter_var( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ), FILTER_VALIDATE_IP ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$forwarded = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
			$ips       = explode( ',', $forwarded );
			$ip        = trim( $ips[0] );
			if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				$ip = '';
			}
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		return $ip;
	}

	/**
	 * Generate a nonce.
	 *
	 * @since    1.0.0
	 * @return   string    Generated nonce.
	 */
	public function generate_nonce() {
		return wp_create_nonce( 'ihumbak_chat_nonce' );
	}

	/**
	 * Verify a nonce.
	 *
	 * @since    1.0.0
	 * @param    string $nonce    Nonce to verify.
	 * @return   bool             True if valid, false otherwise.
	 */
	public function verify_nonce( $nonce ) {
		return (bool) wp_verify_nonce( $nonce, 'ihumbak_chat_nonce' );
	}
}
