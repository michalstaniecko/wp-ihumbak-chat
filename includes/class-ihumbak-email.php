<?php
/**
 * Email notifications class.
 *
 * Handles sending email notifications to administrators.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * Email notifications class.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Email {

	/**
	 * Send admin notification email.
	 *
	 * @since    1.0.0
	 * @param    array $message_data    Message data array.
	 * @return   bool                   True on success, false on failure.
	 */
	public function send_admin_notification( $message_data ) {
		$settings = new Ihumbak_Settings();

		if ( ! $settings->get_option( 'ihumbak_enable_notifications', 1 ) ) {
			return false;
		}

		$admin_email = $this->get_admin_email();
		/* translators: 1: Site name, 2: Sender email address */
		$subject = sprintf( __( '[%1$s] New message from %2$s', 'ihumbak-chat' ), get_bloginfo( 'name' ), $message_data['email'] );
		$message = $this->get_email_template( $message_data );
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$sent = wp_mail( $admin_email, $subject, $message, $headers );

		if ( $sent ) {
			do_action( 'ihumbak_email_sent', $message_data, $admin_email );
		}

		return $sent;
	}

	/**
	 * Get HTML email template.
	 *
	 * @since    1.0.0
	 * @param    array $data    Message data array.
	 * @return   string         HTML email content.
	 */
	public function get_email_template( $data ) {
		$post_id    = isset( $data['post_id'] ) ? $data['post_id'] : 0;
		$email      = isset( $data['email'] ) ? $data['email'] : '';
		$message    = isset( $data['message'] ) ? $data['message'] : '';
		$ip         = isset( $data['ip'] ) ? $data['ip'] : '';
		$user_agent = isset( $data['user_agent'] ) ? $data['user_agent'] : '';
		$referer    = isset( $data['referer'] ) ? $data['referer'] : '';

		$admin_url = $post_id ? admin_url( 'post.php?post=' . $post_id . '&action=edit' ) : admin_url( 'edit.php?post_type=ihumbak_message' );

		// Allow theme/plugin to override template.
		$template = locate_template( 'ihumbak-chat/email/admin-notification.php' );

		if ( ! $template ) {
			$template = IHUMBAK_CHAT_PLUGIN_DIR . 'templates/email/admin-notification.php';
		}

		ob_start();
		include $template;
		return ob_get_clean();
	}

	/**
	 * Get admin email address.
	 *
	 * @since    1.0.0
	 * @return   string    Admin email address.
	 */
	public function get_admin_email() {
		$settings = new Ihumbak_Settings();
		return $settings->get_option( 'ihumbak_admin_email', get_option( 'admin_email' ) );
	}
}
