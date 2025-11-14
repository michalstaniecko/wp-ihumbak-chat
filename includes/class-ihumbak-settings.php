<?php
/**
 * Settings and options management class.
 *
 * Handles plugin settings with validation and default values.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * Settings and options management class.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Settings {

	/**
	 * Get a plugin option.
	 *
	 * @since    1.0.0
	 * @param    string $key        Option key.
	 * @param    mixed  $default    Default value if option doesn't exist.
	 * @return   mixed              Option value.
	 */
	public function get_option( $key, $default = null ) {
		$defaults = $this->get_defaults();

		if ( null === $default && isset( $defaults[ $key ] ) ) {
			$default = $defaults[ $key ];
		}

		return get_option( $key, $default );
	}

	/**
	 * Update a plugin option.
	 *
	 * @since    1.0.0
	 * @param    string $key      Option key.
	 * @param    mixed  $value    Option value.
	 * @return   bool             True if updated, false otherwise.
	 */
	public function update_option( $key, $value ) {
		$validated_value = $this->validate_option( $key, $value );

		if ( is_wp_error( $validated_value ) ) {
			return false;
		}

		return update_option( $key, $validated_value );
	}

	/**
	 * Get all plugin options.
	 *
	 * @since    1.0.0
	 * @return   array    Array of all plugin options.
	 */
	public function get_all_options() {
		$defaults = $this->get_defaults();
		$options  = array();

		foreach ( array_keys( $defaults ) as $key ) {
			$options[ $key ] = $this->get_option( $key );
		}

		return $options;
	}

	/**
	 * Get default option values.
	 *
	 * @since    1.0.0
	 * @return   array    Array of default option values.
	 */
	public function get_defaults() {
		return array(
			'ihumbak_admin_email'          => get_option( 'admin_email' ),
			'ihumbak_recaptcha_site_key'   => '',
			'ihumbak_recaptcha_secret_key' => '',
			'ihumbak_spam_limit'           => 5,
			'ihumbak_enable_notifications' => 1,
			'ihumbak_widget_position'      => 'bottom-right',
			'ihumbak_widget_color'         => '#007bff',
			'ihumbak_widget_title'         => __( 'Get in Touch', 'ihumbak-chat' ),
		);
	}

	/**
	 * Validate an option value.
	 *
	 * @since    1.0.0
	 * @param    string $key      Option key.
	 * @param    mixed  $value    Option value to validate.
	 * @return   mixed|WP_Error   Validated value or WP_Error on failure.
	 */
	private function validate_option( $key, $value ) {
		switch ( $key ) {
			case 'ihumbak_admin_email':
				if ( ! is_email( $value ) ) {
					return new WP_Error( 'invalid_email', __( 'Invalid email address.', 'ihumbak-chat' ) );
				}
				return sanitize_email( $value );

			case 'ihumbak_spam_limit':
				$int_value = intval( $value );
				if ( $int_value < 1 ) {
					return new WP_Error( 'invalid_number', __( 'Spam limit must be a positive integer.', 'ihumbak-chat' ) );
				}
				return $int_value;

			case 'ihumbak_enable_notifications':
				return (int) (bool) $value;

			case 'ihumbak_widget_position':
				$allowed_positions = array( 'bottom-right', 'bottom-left', 'top-right', 'top-left' );
				if ( ! in_array( $value, $allowed_positions, true ) ) {
					return new WP_Error( 'invalid_position', __( 'Invalid widget position.', 'ihumbak-chat' ) );
				}
				return sanitize_text_field( $value );

			case 'ihumbak_widget_color':
				// Validate hex color format.
				if ( ! preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
					return new WP_Error( 'invalid_color', __( 'Invalid color format. Use hex format (e.g., #007bff).', 'ihumbak-chat' ) );
				}
				return sanitize_text_field( $value );

			case 'ihumbak_recaptcha_site_key':
			case 'ihumbak_recaptcha_secret_key':
				return sanitize_text_field( $value );

			case 'ihumbak_widget_title':
				return sanitize_text_field( $value );

			default:
				return sanitize_text_field( $value );
		}
	}
}
