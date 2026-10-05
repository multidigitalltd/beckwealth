<?php
/**
 * אופטימיזציית ביצועים.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

// הסרת סקריפטים וסגנונות של אימוג'י.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );

// הסרת קישורי oEmbed discovery ו-REST link מה-head (נשאר זמין, רק לא מפורסם).
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * ביטול jQuery Migrate בצד הלקוח (לא נדרש לתבנית).
 *
 * @param WP_Scripts $scripts אובייקט הסקריפטים.
 */
function beckwealth_remove_jquery_migrate( WP_Scripts $scripts ): void {
	if ( is_admin() || ! isset( $scripts->registered['jquery'] ) ) {
		return;
	}
	$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
}
add_action( 'wp_default_scripts', 'beckwealth_remove_jquery_migrate' );

/**
 * הסרת סגנונות מיותרים בצד הלקוח (סגנונות בלוקים נטענים רק כשבשימוש).
 */
function beckwealth_dequeue_styles(): void {
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', 'beckwealth_dequeue_styles', 100 );

// טעינת CSS של בלוקים רק לבלוקים שבשימוש בעמוד.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * הפחתת תדירות Heartbeat והשבתה בצד הלקוח.
 */
function beckwealth_heartbeat(): void {
	if ( ! is_admin() ) {
		wp_deregister_script( 'heartbeat' );
	}
}
add_action( 'init', 'beckwealth_heartbeat', 1 );

/**
 * הוספת decoding="async" ו-fetchpriority לתמונות הירו.
 *
 * @param array $attr תכונות.
 * @param WP_Post $attachment קובץ.
 * @param string|array $size גודל.
 * @return array
 */
function beckwealth_image_attrs( array $attr, WP_Post $attachment, $size ): array {
	if ( 'beckwealth-hero' === $size ) {
		$attr['loading']       = 'eager';
		$attr['fetchpriority'] = 'high';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'beckwealth_image_attrs', 10, 3 );

/**
 * הגבלת רוויזיות ברמת התבנית אם לא הוגדר ב-wp-config.
 *
 * @return int
 */
function beckwealth_revisions_limit(): int {
	return 10;
}
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	add_filter( 'wp_revisions_to_keep', 'beckwealth_revisions_limit' );
}
