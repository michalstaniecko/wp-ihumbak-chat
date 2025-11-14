<?php
/**
 * Custom post type for messages.
 *
 * Handles registration and management of message custom post type.
 *
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */

/**
 * Custom post type for messages.
 *
 * @since      1.0.0
 * @package    IhumbakChat
 * @subpackage IhumbakChat/includes
 */
class Ihumbak_Messages {

	/**
	 * Register custom post type.
	 *
	 * @since    1.0.0
	 */
	public function register_cpt() {
		$labels = array(
			'name'               => _x( 'Messages', 'Post type general name', 'ihumbak-chat' ),
			'singular_name'      => _x( 'Message', 'Post type singular name', 'ihumbak-chat' ),
			'menu_name'          => _x( 'Chat Messages', 'Admin Menu text', 'ihumbak-chat' ),
			'name_admin_bar'     => _x( 'Message', 'Add New on Toolbar', 'ihumbak-chat' ),
			'add_new'            => __( 'Add New', 'ihumbak-chat' ),
			'add_new_item'       => __( 'Add New Message', 'ihumbak-chat' ),
			'new_item'           => __( 'New Message', 'ihumbak-chat' ),
			'edit_item'          => __( 'Edit Message', 'ihumbak-chat' ),
			'view_item'          => __( 'View Message', 'ihumbak-chat' ),
			'all_items'          => __( 'All Messages', 'ihumbak-chat' ),
			'search_items'       => __( 'Search Messages', 'ihumbak-chat' ),
			'not_found'          => __( 'No messages found.', 'ihumbak-chat' ),
			'not_found_in_trash' => __( 'No messages found in Trash.', 'ihumbak-chat' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-email',
			'supports'           => array( 'title', 'editor' ),
			'show_in_rest'       => false,
		);

		register_post_type( 'ihumbak_message', $args );
	}

	/**
	 * Register post meta fields.
	 *
	 * @since    1.0.0
	 */
	public function register_post_meta() {
		register_post_meta(
			'ihumbak_message',
			'ihumbak_email',
			array(
				'type'              => 'string',
				'description'       => __( 'Sender email address', 'ihumbak-chat' ),
				'single'            => true,
				'sanitize_callback' => 'sanitize_email',
				'show_in_rest'      => false,
			)
		);

		register_post_meta(
			'ihumbak_message',
			'ihumbak_ip',
			array(
				'type'              => 'string',
				'description'       => __( 'Sender IP address', 'ihumbak-chat' ),
				'single'            => true,
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => false,
			)
		);

		register_post_meta(
			'ihumbak_message',
			'ihumbak_user_agent',
			array(
				'type'              => 'string',
				'description'       => __( 'Sender user agent', 'ihumbak-chat' ),
				'single'            => true,
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => false,
			)
		);

		register_post_meta(
			'ihumbak_message',
			'ihumbak_referer',
			array(
				'type'              => 'string',
				'description'       => __( 'Page referer URL', 'ihumbak-chat' ),
				'single'            => true,
				'sanitize_callback' => 'esc_url_raw',
				'show_in_rest'      => false,
			)
		);

		register_post_meta(
			'ihumbak_message',
			'ihumbak_read',
			array(
				'type'         => 'boolean',
				'description'  => __( 'Message read status', 'ihumbak-chat' ),
				'single'       => true,
				'default'      => false,
				'show_in_rest' => false,
			)
		);

		register_post_meta(
			'ihumbak_message',
			'ihumbak_responded',
			array(
				'type'         => 'boolean',
				'description'  => __( 'Message responded status', 'ihumbak-chat' ),
				'single'       => true,
				'default'      => false,
				'show_in_rest' => false,
			)
		);
	}

	/**
	 * Create a new message.
	 *
	 * @since    1.0.0
	 * @param    array $data    Message data array.
	 * @return   int|WP_Error   Post ID on success, WP_Error on failure.
	 */
	public function create_message( $data ) {
		$required_fields = array( 'email', 'message' );

		foreach ( $required_fields as $field ) {
			if ( empty( $data[ $field ] ) ) {
				/* translators: %s: Field name */
				return new WP_Error( 'missing_field', sprintf( __( 'Missing required field: %s', 'ihumbak-chat' ), $field ) );
			}
		}

		$post_data = array(
			'post_type'    => 'ihumbak_message',
			/* translators: %s: Sender email address */
			'post_title'   => sprintf( __( 'Message from %s', 'ihumbak-chat' ), $data['email'] ),
			'post_content' => $data['message'],
			'post_status'  => 'publish',
		);

		$post_id = wp_insert_post( $post_data );

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		// Save meta data.
		update_post_meta( $post_id, 'ihumbak_email', $data['email'] );

		if ( ! empty( $data['ip'] ) ) {
			update_post_meta( $post_id, 'ihumbak_ip', $data['ip'] );
		}

		if ( ! empty( $data['user_agent'] ) ) {
			update_post_meta( $post_id, 'ihumbak_user_agent', $data['user_agent'] );
		}

		if ( ! empty( $data['referer'] ) ) {
			update_post_meta( $post_id, 'ihumbak_referer', $data['referer'] );
		}

		update_post_meta( $post_id, 'ihumbak_read', false );
		update_post_meta( $post_id, 'ihumbak_responded', false );

		return $post_id;
	}

	/**
	 * Get messages with filtering.
	 *
	 * @since    1.0.0
	 * @param    array $args    Query arguments.
	 * @return   WP_Query       Query results.
	 */
	public function get_messages( $args = array() ) {
		$defaults = array(
			'post_type'      => 'ihumbak_message',
			'post_status'    => 'publish',
			'posts_per_page' => 20,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$args = wp_parse_args( $args, $defaults );

		return new WP_Query( $args );
	}

	/**
	 * Mark message as read.
	 *
	 * @since    1.0.0
	 * @param    int $post_id    Message post ID.
	 * @return   bool            True on success, false on failure.
	 */
	public function mark_as_read( $post_id ) {
		if ( 'ihumbak_message' !== get_post_type( $post_id ) ) {
			return false;
		}

		return update_post_meta( $post_id, 'ihumbak_read', true );
	}
}
