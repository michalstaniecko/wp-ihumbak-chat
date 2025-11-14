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

		ob_start();
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title><?php echo esc_html__( 'New Message', 'ihumbak-chat' ); ?></title>
		</head>
		<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
			<div style="background-color: #f8f9fa; border-radius: 8px; padding: 30px; margin-bottom: 20px;">
				<h1 style="color: #007bff; margin: 0 0 20px 0; font-size: 24px;">
					<?php echo esc_html__( 'iHumbak Chat - New Message', 'ihumbak-chat' ); ?>
				</h1>
				<p style="margin: 0; color: #6c757d;">
					<?php echo esc_html__( 'You have received a new message from your website.', 'ihumbak-chat' ); ?>
				</p>
			</div>

			<div style="background-color: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 30px; margin-bottom: 20px;">
				<h2 style="color: #495057; margin: 0 0 15px 0; font-size: 18px; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
					<?php echo esc_html__( 'Message Details', 'ihumbak-chat' ); ?>
				</h2>

				<div style="margin-bottom: 15px;">
					<strong style="color: #495057;"><?php echo esc_html__( 'From:', 'ihumbak-chat' ); ?></strong>
					<p style="margin: 5px 0 0 0; color: #007bff;">
						<a href="mailto:<?php echo esc_attr( $email ); ?>" style="color: #007bff; text-decoration: none;">
							<?php echo esc_html( $email ); ?>
						</a>
					</p>
				</div>

				<div style="margin-bottom: 15px;">
					<strong style="color: #495057;"><?php echo esc_html__( 'Message:', 'ihumbak-chat' ); ?></strong>
					<div style="margin: 10px 0 0 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #007bff; border-radius: 4px;">
						<?php echo nl2br( esc_html( $message ) ); ?>
					</div>
				</div>

				<?php if ( $ip ) : ?>
				<div style="margin-bottom: 15px;">
					<strong style="color: #495057;"><?php echo esc_html__( 'IP Address:', 'ihumbak-chat' ); ?></strong>
					<p style="margin: 5px 0 0 0; color: #6c757d; font-family: monospace;">
						<?php echo esc_html( $ip ); ?>
					</p>
				</div>
				<?php endif; ?>

				<?php if ( $user_agent ) : ?>
				<div style="margin-bottom: 15px;">
					<strong style="color: #495057;"><?php echo esc_html__( 'User Agent:', 'ihumbak-chat' ); ?></strong>
					<p style="margin: 5px 0 0 0; color: #6c757d; font-size: 12px; word-break: break-all;">
						<?php echo esc_html( $user_agent ); ?>
					</p>
				</div>
				<?php endif; ?>

				<?php if ( $referer ) : ?>
				<div style="margin-bottom: 15px;">
					<strong style="color: #495057;"><?php echo esc_html__( 'From Page:', 'ihumbak-chat' ); ?></strong>
					<p style="margin: 5px 0 0 0;">
						<a href="<?php echo esc_url( $referer ); ?>" style="color: #007bff; text-decoration: none; word-break: break-all;">
							<?php echo esc_html( $referer ); ?>
						</a>
					</p>
				</div>
				<?php endif; ?>
			</div>

			<div style="text-align: center; margin-bottom: 20px;">
				<a href="<?php echo esc_url( $admin_url ); ?>" style="display: inline-block; background-color: #007bff; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
					<?php echo esc_html__( 'View in Dashboard', 'ihumbak-chat' ); ?>
				</a>
			</div>

			<div style="text-align: center; padding: 20px; border-top: 1px solid #dee2e6; color: #6c757d; font-size: 12px;">
				<p style="margin: 0 0 10px 0;">
					<?php echo esc_html__( 'This email was sent by iHumbak Chat plugin.', 'ihumbak-chat' ); ?>
				</p>
				<p style="margin: 0;">
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=ihumbak-chat-settings' ) ); ?>" style="color: #6c757d; text-decoration: underline;">
						<?php echo esc_html__( 'Manage Settings', 'ihumbak-chat' ); ?>
					</a>
				</p>
			</div>
		</body>
		</html>
		<?php
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
