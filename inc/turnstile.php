<?php
/**
 * Cloudflare Turnstile – CAPTCHA נגיש לטפסים. פעיל רק כשהוגדרו מפתחות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * האם Turnstile מוגדר?
 *
 * @return bool
 */
function beckwealth_turnstile_enabled(): bool {
	return '' !== (string) beckwealth_get_setting( 'turnstile_site_key', '' ) && '' !== (string) beckwealth_get_setting( 'turnstile_secret', '' );
}

/**
 * הדפסת הווידג'ט בטופס + טעינת הסקריפט (רק כשנדרש).
 */
function beckwealth_turnstile_widget(): void {
	if ( ! beckwealth_turnstile_enabled() ) {
		return;
	}
	if ( ! wp_script_is( 'cf-turnstile', 'enqueued' ) ) {
		wp_enqueue_script( 'cf-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, array( 'strategy' => 'defer', 'in_footer' => true ) ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
	printf(
		'<div class="cf-turnstile form-field" data-sitekey="%s" data-theme="light" data-language="%s"></div>',
		esc_attr( (string) beckwealth_get_setting( 'turnstile_site_key', '' ) ),
		esc_attr( substr( get_locale(), 0, 2 ) )
	);
}

/**
 * אימות התשובה מול Cloudflare בצד השרת.
 *
 * @param string $token  הטוקן מהטופס (cf-turnstile-response).
 * @param string $remote כתובת IP של הגולש.
 * @return bool
 */
function beckwealth_turnstile_verify( string $token, string $remote = '' ): bool {
	if ( ! beckwealth_turnstile_enabled() ) {
		return true;
	}
	if ( '' === $token ) {
		return false;
	}

	$response = wp_remote_post(
		'https://challenges.cloudflare.com/turnstile/v0/siteverify',
		array(
			'timeout' => 8,
			'body'    => array(
				'secret'   => (string) beckwealth_get_setting( 'turnstile_secret', '' ),
				'response' => $token,
				'remoteip' => $remote,
			),
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return false;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	return ! empty( $body['success'] );
}
