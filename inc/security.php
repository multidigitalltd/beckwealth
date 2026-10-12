<?php
/**
 * הקשחת אבטחה ברמת התבנית.
 *
 * הערה: הקשחה מלאה נעשית גם ברמת השרת/wp-config (ראו README).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

// הסרת גרסת וורדפרס מה-HTML ומה-RSS.
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// הסרת קישורי גילוי מיותרים.
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// כיבוי XML-RPC (וקטור התקפה נפוץ).
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter(
	'wp_headers',
	static function ( array $headers ): array {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
);

// חסימת עריכת קבצים מממשק הניהול.
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * הסרת מספר גרסה מכתובות של קבצי ליבה (מקשה על זיהוי גרסה).
 *
 * @param string $src כתובת.
 * @return string
 */
function beckwealth_remove_core_version( string $src ): string {
	if ( str_contains( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'beckwealth_remove_core_version', 9999 );
add_filter( 'script_loader_src', 'beckwealth_remove_core_version', 9999 );

/**
 * הודעת שגיאה כללית במסך ההתחברות (לא חושפת אם שם המשתמש קיים).
 *
 * @return string
 */
function beckwealth_login_errors(): string {
	return __( 'פרטי ההתחברות שגויים.', 'beckwealth' );
}
add_filter( 'login_errors', 'beckwealth_login_errors' );

/**
 * חסימת רשימת משתמשים ב-REST למשתמשים לא מחוברים.
 *
 * @param array           $endpoints נקודות קצה.
 * @return array
 */
function beckwealth_restrict_rest_users( array $endpoints ): array {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	return $endpoints;
}
add_filter( 'rest_endpoints', 'beckwealth_restrict_rest_users' );

/**
 * מניעת ספירת משתמשים דרך ?author=N.
 */
function beckwealth_block_author_enum(): void {
	if ( is_admin() ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check.
	if ( isset( $_GET['author'] ) && preg_match( '/^\d+$/', (string) wp_unslash( $_GET['author'] ) ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'init', 'beckwealth_block_author_enum' );

/**
 * כותרות אבטחה בסיסיות (אם לא מוגדרות בשרת).
 */
function beckwealth_security_headers(): void {
	if ( headers_sent() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
}
add_action( 'send_headers', 'beckwealth_security_headers' );

/**
 * הסרת סוגי קבצים מסוכנים מהעלאות (למשל SVG לא מסונן).
 *
 * @param array $mimes סוגים מותרים.
 * @return array
 */
function beckwealth_upload_mimes( array $mimes ): array {
	unset( $mimes['exe'], $mimes['swf'] );
	return $mimes;
}
add_filter( 'upload_mimes', 'beckwealth_upload_mimes' );

/**
 * הגנה על טופס התגובות – השבתת תגובות בסוגי תוכן עסקיים.
 *
 * @param bool $open    האם פתוח.
 * @param int  $post_id מזהה.
 * @return bool
 */
function beckwealth_comments_for_business_types( bool $open, int $post_id ): bool {
	if ( in_array( get_post_type( $post_id ), array( 'service', 'team', 'testimonial', 'faq', 'page' ), true ) ) {
		return false;
	}
	return $open;
}
add_filter( 'comments_open', 'beckwealth_comments_for_business_types', 10, 2 );
add_filter( 'pings_open', 'beckwealth_comments_for_business_types', 10, 2 );
