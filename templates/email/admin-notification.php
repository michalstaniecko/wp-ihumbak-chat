<?php
/**
 * Email Template: Admin Notification
 *
 * This template is used for sending notification emails to administrators
 * when a new message is received through the iHumbak Chat widget.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/templates/email
 * @since      1.0.0
 *
 * Available variables:
 * @var int    $post_id     Message post ID
 * @var string $email       Sender email address
 * @var string $message     Message content
 * @var string $ip          Sender IP address
 * @var string $user_agent  Sender user agent
 * @var string $referer     Page URL where message was sent from
 * @var string $admin_url   URL to view message in admin dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
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
