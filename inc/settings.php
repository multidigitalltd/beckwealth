<?php
/**
 * הגדרות התבנית (אופציה אחת קטנה: beckwealth_settings) + גישה אחידה.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * ברירות מחדל להגדרות.
 *
 * @return array<string, string>
 */
function beckwealth_default_settings(): array {
	return array(
		'lead_notify_email'    => '',
		'turnstile_site_key'   => '',
		'turnstile_secret'     => '',
		'privacy_popup'        => '1',
		'privacy_popup_text'   => __( 'אנו משתמשים בעוגיות ובכלי ניתוח כדי לשפר את חוויית הגלישה. המשך השימוש באתר מהווה הסכמה למדיניות הפרטיות שלנו.', 'beckwealth' ),
		'a11y_toolbar'         => '1',
		'a11y_statement_page'  => '0',
		'a11y_coordinator'     => '',
		'a11y_phone'           => '',
		'a11y_email'           => '',
	);
}

/**
 * קריאת הגדרה. מפתחות סודיים (Turnstile secret) ניתנים גם כקבוע ב-wp-config
 * (BECKWEALTH_TURNSTILE_SECRET / BECKWEALTH_TURNSTILE_SITE_KEY) – עדיפות לקבוע.
 *
 * @param string $key     מפתח.
 * @param mixed  $default ברירת מחדל.
 * @return mixed
 */
function beckwealth_get_setting( string $key, $default = null ) {
	static $settings = null;

	$constant = 'BECKWEALTH_' . strtoupper( $key );
	if ( defined( $constant ) ) {
		return constant( $constant );
	}

	if ( null === $settings ) {
		$saved    = get_option( 'beckwealth_settings', array() );
		$settings = wp_parse_args( is_array( $saved ) ? $saved : array(), beckwealth_default_settings() );
	}

	if ( array_key_exists( $key, $settings ) ) {
		return $settings[ $key ];
	}
	return $default;
}

/**
 * ניקוי ההגדרות לפני שמירה (Settings API).
 *
 * @param mixed $input קלט.
 * @return array<string, string>
 */
function beckwealth_sanitize_settings( $input ): array {
	$input   = is_array( $input ) ? $input : array();
	$current = wp_parse_args( (array) get_option( 'beckwealth_settings', array() ), beckwealth_default_settings() );
	$clean   = array();

	$clean['lead_notify_email']   = isset( $input['lead_notify_email'] ) ? sanitize_email( $input['lead_notify_email'] ) : '';
	$clean['turnstile_site_key']  = isset( $input['turnstile_site_key'] ) ? sanitize_text_field( $input['turnstile_site_key'] ) : '';
	// המפתח הסודי לא מוצג בטופס; שדה ריק = לשמור על הקיים.
	$clean['turnstile_secret']    = ! empty( $input['turnstile_secret'] ) ? sanitize_text_field( $input['turnstile_secret'] ) : (string) $current['turnstile_secret'];
	if ( ! empty( $input['turnstile_secret_clear'] ) ) {
		$clean['turnstile_secret'] = '';
	}
	$clean['privacy_popup']       = empty( $input['privacy_popup'] ) ? '0' : '1';
	$clean['privacy_popup_text']  = isset( $input['privacy_popup_text'] ) ? sanitize_textarea_field( $input['privacy_popup_text'] ) : '';
	$clean['a11y_toolbar']        = empty( $input['a11y_toolbar'] ) ? '0' : '1';
	$clean['a11y_statement_page'] = isset( $input['a11y_statement_page'] ) ? (string) absint( $input['a11y_statement_page'] ) : '0';
	$clean['a11y_coordinator']    = isset( $input['a11y_coordinator'] ) ? sanitize_text_field( $input['a11y_coordinator'] ) : '';
	$clean['a11y_phone']          = isset( $input['a11y_phone'] ) ? sanitize_text_field( $input['a11y_phone'] ) : '';
	$clean['a11y_email']          = isset( $input['a11y_email'] ) ? sanitize_email( $input['a11y_email'] ) : '';

	return $clean;
}

/**
 * רישום ההגדרה ב-Settings API.
 */
function beckwealth_register_settings(): void {
	register_setting(
		'beckwealth_settings_group',
		'beckwealth_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'beckwealth_sanitize_settings',
			'default'           => beckwealth_default_settings(),
		)
	);
}
add_action( 'admin_init', 'beckwealth_register_settings' );
