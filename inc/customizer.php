<?php
/**
 * קסטומייזר: פרטי קשר, רשתות, ותוכן דף הבית (נבנה מ-home-defaults.php).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * ניקוי צ'קבוקס.
 *
 * @param mixed $value ערך.
 * @return bool
 */
function beckwealth_sanitize_checkbox( $value ): bool {
	return (bool) $value;
}

/**
 * מאפשר כתובת URL מלאה או עוגן פנימי (#contact).
 *
 * @param mixed $value ערך.
 * @return string
 */
function beckwealth_sanitize_url_or_anchor( $value ): string {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( str_starts_with( $value, '#' ) ) {
		return '#' . sanitize_title( substr( $value, 1 ) );
	}
	return esc_url_raw( $value );
}

/**
 * ניקוי מזהה עמוד.
 *
 * @param mixed $value ערך.
 * @return int
 */
function beckwealth_sanitize_select_page( $value ): int {
	$value = absint( $value );
	return ( $value && 'page' === get_post_type( $value ) ) ? $value : 0;
}

/**
 * רישום פאנל, סקשנים והגדרות.
 *
 * @param WP_Customize_Manager $wp_customize מנהל הקסטומייזר.
 */
function beckwealth_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';

	$wp_customize->add_panel(
		'beckwealth_panel',
		array(
			'title'       => __( 'הגדרות Beck Wealth', 'beckwealth' ),
			'description' => __( 'טקסטים, תמונות ופרטי קשר של האתר. שירותים, צוות, המלצות ושאלות מנוהלים בתפריטי התוכן הייעודיים.', 'beckwealth' ),
			'priority'    => 10,
		)
	);

	/* ---------- פרטי קשר ---------- */
	$wp_customize->add_section( 'beckwealth_contact', array( 'title' => __( 'פרטי קשר', 'beckwealth' ), 'panel' => 'beckwealth_panel', 'priority' => 5 ) );

	$contact_fields = array(
		'beckwealth_phone'    => array( __( 'טלפון (ישראל)', 'beckwealth' ), 'sanitize_text_field', '03-000-0000' ),
		'beckwealth_whatsapp' => array( __( 'וואטסאפ (מספר)', 'beckwealth' ), 'sanitize_text_field', '' ),
		'beckwealth_email'    => array( __( 'אימייל ליצירת קשר (מקבל פניות מהטופס)', 'beckwealth' ), 'sanitize_email', get_option( 'admin_email' ) ),
		'beckwealth_address'  => array( __( 'כתובת (ישראל)', 'beckwealth' ), 'sanitize_text_field', '' ),
		'beckwealth_hours'    => array( __( 'שעות פעילות', 'beckwealth' ), 'sanitize_text_field', '' ),
	);
	foreach ( $contact_fields as $id => [ $label, $sanitize, $default ] ) {
		$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => $sanitize ) );
		$wp_customize->add_control( $id, array( 'type' => 'beckwealth_email' === $id ? 'email' : 'text', 'label' => $label, 'section' => 'beckwealth_contact' ) );
	}
	$wp_customize->add_setting( 'beckwealth_contact_page', array( 'default' => 0, 'sanitize_callback' => 'beckwealth_sanitize_select_page' ) );
	$wp_customize->add_control( 'beckwealth_contact_page', array( 'type' => 'dropdown-pages', 'label' => __( 'עמוד יצירת קשר (לכפתורים בעמודים פנימיים)', 'beckwealth' ), 'section' => 'beckwealth_contact' ) );

	/* ---------- רשתות חברתיות ---------- */
	$wp_customize->add_section( 'beckwealth_social', array( 'title' => __( 'רשתות חברתיות', 'beckwealth' ), 'panel' => 'beckwealth_panel', 'priority' => 6 ) );
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'x' => 'X (Twitter)', 'tiktok' => 'TikTok' ) as $key => $label ) {
		$wp_customize->add_setting( 'beckwealth_social_' . $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( 'beckwealth_social_' . $key, array( 'type' => 'url', 'label' => $label, 'section' => 'beckwealth_social' ) );
	}

	/* ---------- תוכן דף הבית – נבנה אוטומטית ---------- */
	$priority = 10;
	foreach ( beckwealth_home_sections() as $section_id => $title ) {
		$wp_customize->add_section( 'beckwealth_' . $section_id, array( 'title' => $title, 'panel' => 'beckwealth_panel', 'priority' => $priority++ ) );
	}

	foreach ( beckwealth_home_fields() as $key => [ $label, $type, $default, $section ] ) {
		$setting_id = 'bw_' . $key;
		$sanitize   = match ( $type ) {
			'textarea' => 'sanitize_textarea_field',
			'url'      => 'beckwealth_sanitize_url_or_anchor',
			'image'    => 'absint',
			'checkbox' => 'beckwealth_sanitize_checkbox',
			'page'     => 'beckwealth_sanitize_select_page',
			default    => 'sanitize_text_field',
		};
		$wp_customize->add_setting( $setting_id, array( 'default' => $default, 'sanitize_callback' => $sanitize ) );

		$args = array( 'label' => $label, 'section' => 'beckwealth_' . $section );
		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $setting_id, $args + array( 'mime_type' => 'image' ) ) );
			continue;
		}
		$args['type'] = match ( $type ) {
			'textarea' => 'textarea',
			'checkbox' => 'checkbox',
			'page'     => 'dropdown-pages',
			default    => 'text',
		};
		$wp_customize->add_control( $setting_id, $args );
	}
}
add_action( 'customize_register', 'beckwealth_customize_register' );

/**
 * כתובת עמוד יצירת הקשר (או עוגן לדף הבית).
 *
 * @return string
 */
function beckwealth_contact_url(): string {
	$page_id = (int) get_theme_mod( 'beckwealth_contact_page', 0 );
	if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
		return (string) get_permalink( $page_id );
	}
	return is_front_page() ? '#contact' : home_url( '/#contact' );
}
