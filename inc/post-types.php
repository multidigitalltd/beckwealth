<?php
/**
 * סוגי תוכן מותאמים: שירותים, צוות, המלצות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * רישום סוגי תוכן וטקסונומיות.
 */
function beckwealth_register_post_types(): void {
	// שירותים.
	register_post_type(
		'service',
		array(
			'labels'       => array(
				'name'               => __( 'שירותים', 'beckwealth' ),
				'singular_name'      => __( 'שירות', 'beckwealth' ),
				'add_new'            => __( 'הוספת שירות', 'beckwealth' ),
				'add_new_item'       => __( 'הוספת שירות חדש', 'beckwealth' ),
				'edit_item'          => __( 'עריכת שירות', 'beckwealth' ),
				'new_item'           => __( 'שירות חדש', 'beckwealth' ),
				'view_item'          => __( 'צפייה בשירות', 'beckwealth' ),
				'search_items'       => __( 'חיפוש שירותים', 'beckwealth' ),
				'not_found'          => __( 'לא נמצאו שירותים', 'beckwealth' ),
				'not_found_in_trash' => __( 'לא נמצאו שירותים בפח', 'beckwealth' ),
				'all_items'          => __( 'כל השירותים', 'beckwealth' ),
				'menu_name'          => __( 'שירותים', 'beckwealth' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'menu_icon'    => 'dashicons-portfolio',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'show_in_rest' => true,
		)
	);

	register_taxonomy(
		'service_category',
		'service',
		array(
			'labels'            => array(
				'name'          => __( 'קטגוריות שירות', 'beckwealth' ),
				'singular_name' => __( 'קטגוריית שירות', 'beckwealth' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'service-category' ),
		)
	);

	// צוות.
	register_post_type(
		'team',
		array(
			'labels'        => array(
				'name'               => __( 'צוות', 'beckwealth' ),
				'singular_name'      => __( 'איש צוות', 'beckwealth' ),
				'add_new'            => __( 'הוספת איש צוות', 'beckwealth' ),
				'add_new_item'       => __( 'הוספת איש צוות', 'beckwealth' ),
				'edit_item'          => __( 'עריכת איש צוות', 'beckwealth' ),
				'view_item'          => __( 'צפייה', 'beckwealth' ),
				'search_items'       => __( 'חיפוש בצוות', 'beckwealth' ),
				'not_found'          => __( 'לא נמצאו אנשי צוות', 'beckwealth' ),
				'all_items'          => __( 'כל הצוות', 'beckwealth' ),
				'menu_name'          => __( 'צוות', 'beckwealth' ),
			),
			'public'        => true,
			'has_archive'   => false,
			'rewrite'       => array(
				'slug'       => 'team',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'show_in_rest'  => true,
		)
	);

	// המלצות.
	register_post_type(
		'testimonial',
		array(
			'labels'        => array(
				'name'               => __( 'המלצות', 'beckwealth' ),
				'singular_name'      => __( 'המלצה', 'beckwealth' ),
				'add_new'            => __( 'הוספת המלצה', 'beckwealth' ),
				'add_new_item'       => __( 'הוספת המלצה', 'beckwealth' ),
				'edit_item'          => __( 'עריכת המלצה', 'beckwealth' ),
				'search_items'       => __( 'חיפוש המלצות', 'beckwealth' ),
				'not_found'          => __( 'לא נמצאו המלצות', 'beckwealth' ),
				'all_items'          => __( 'כל ההמלצות', 'beckwealth' ),
				'menu_name'          => __( 'המלצות', 'beckwealth' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-format-quote',
			'menu_position'       => 22,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ),
			'show_in_rest'        => true,
		)
	);

	// שאלות ותשובות.
	register_post_type(
		'faq',
		array(
			'labels'              => array(
				'name'          => __( 'שאלות ותשובות', 'beckwealth' ),
				'singular_name' => __( 'שאלה', 'beckwealth' ),
				'add_new'       => __( 'הוספת שאלה', 'beckwealth' ),
				'add_new_item'  => __( 'הוספת שאלה', 'beckwealth' ),
				'edit_item'     => __( 'עריכת שאלה', 'beckwealth' ),
				'search_items'  => __( 'חיפוש שאלות', 'beckwealth' ),
				'not_found'     => __( 'לא נמצאו שאלות', 'beckwealth' ),
				'all_items'     => __( 'כל השאלות', 'beckwealth' ),
				'menu_name'     => __( 'שאלות ותשובות', 'beckwealth' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-editor-help',
			'menu_position'       => 23,
			'supports'            => array( 'title', 'editor', 'page-attributes', 'revisions' ),
			'show_in_rest'        => true,
		)
	);
}
add_action( 'init', 'beckwealth_register_post_types' );

/**
 * שדות מטא פשוטים (ללא ACF) – נרשמים ב-REST כדי לעבוד בעורך הבלוקים.
 */
function beckwealth_register_meta(): void {
	$fields = array(
		'team'        => array(
			'_bw_role'     => __( 'תפקיד', 'beckwealth' ),
			'_bw_phone'    => __( 'טלפון', 'beckwealth' ),
			'_bw_email'    => __( 'אימייל', 'beckwealth' ),
			'_bw_linkedin' => __( 'LinkedIn', 'beckwealth' ),
		),
		'testimonial' => array(
			'_bw_role' => __( 'תפקיד / חברה', 'beckwealth' ),
		),
		'service'     => array(
			'_bw_tagline' => __( 'שורת תיאור קצרה', 'beckwealth' ),
			'_bw_bullets' => __( 'נקודות (שורה לכל נקודה)', 'beckwealth' ),
		),
	);

	foreach ( $fields as $post_type => $keys ) {
		foreach ( array_keys( $keys ) as $key ) {
			register_post_meta(
				$post_type,
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => '_bw_bullets' === $key ? 'sanitize_textarea_field' : 'sanitize_text_field',
					'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
				)
			);
		}
	}
}
add_action( 'init', 'beckwealth_register_meta' );

/**
 * הגדרת שדות המטא לתיבת עריכה קלאסית (metabox) – ידידותי לעורך תוכן.
 *
 * @return array<string, array<string, string>>
 */
function beckwealth_meta_fields(): array {
	return array(
		'team'        => array(
			'_bw_role'     => __( 'תפקיד', 'beckwealth' ),
			'_bw_phone'    => __( 'טלפון', 'beckwealth' ),
			'_bw_email'    => __( 'אימייל', 'beckwealth' ),
			'_bw_linkedin' => __( 'קישור LinkedIn', 'beckwealth' ),
		),
		'testimonial' => array(
			'_bw_role' => __( 'תפקיד / חברה', 'beckwealth' ),
		),
		'service'     => array(
			'_bw_tagline' => __( 'תיאור קצר (מוצג בכרטיס בדף הבית)', 'beckwealth' ),
			'_bw_bullets' => __( 'נקודות בכרטיס – שורה לכל נקודה', 'beckwealth' ),
		),
	);
}

/**
 * רישום metabox לפרטים נוספים.
 */
function beckwealth_add_meta_boxes(): void {
	foreach ( array_keys( beckwealth_meta_fields() ) as $post_type ) {
		add_meta_box(
			'beckwealth_details',
			__( 'פרטים נוספים', 'beckwealth' ),
			'beckwealth_render_meta_box',
			$post_type,
			'side'
		);
	}
}
add_action( 'add_meta_boxes', 'beckwealth_add_meta_boxes' );

/**
 * הצגת ה-metabox.
 *
 * @param WP_Post $post הפוסט.
 */
function beckwealth_render_meta_box( WP_Post $post ): void {
	$fields = beckwealth_meta_fields()[ $post->post_type ] ?? array();
	wp_nonce_field( 'beckwealth_save_meta', 'beckwealth_meta_nonce' );
	foreach ( $fields as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		if ( '_bw_bullets' === $key ) {
			printf(
				'<p><label for="%1$s"><strong>%2$s</strong></label><br><textarea class="widefat" rows="4" id="%1$s" name="%1$s">%3$s</textarea></p>',
				esc_attr( $key ),
				esc_html( $label ),
				esc_textarea( $value )
			);
			continue;
		}
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="text" class="widefat" id="%1$s" name="%1$s" value="%3$s"></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}
}

/**
 * שמירת שדות המטא.
 *
 * @param int $post_id מזהה פוסט.
 */
function beckwealth_save_meta( int $post_id ): void {
	if ( ! isset( $_POST['beckwealth_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['beckwealth_meta_nonce'] ), 'beckwealth_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$post_type = get_post_type( $post_id );
	$fields    = beckwealth_meta_fields()[ $post_type ] ?? array();
	foreach ( array_keys( $fields ) as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.
		if ( in_array( $key, array( '_bw_linkedin' ), true ) ) {
			$value = esc_url_raw( $raw );
		} elseif ( '_bw_email' === $key ) {
			$value = sanitize_email( $raw );
		} elseif ( '_bw_bullets' === $key ) {
			$value = sanitize_textarea_field( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'beckwealth_save_meta' );

/**
 * ריענון קישורים קבועים בהפעלת התבנית כדי ש-CPT יעבדו מיד.
 */
function beckwealth_flush_rewrite(): void {
	beckwealth_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'beckwealth_flush_rewrite' );
