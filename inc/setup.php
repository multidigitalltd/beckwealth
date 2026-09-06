<?php
/**
 * הגדרות בסיס של התבנית.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * הגדרת תמיכות התבנית, תפריטים וגדלי תמונות.
 */
function beckwealth_setup(): void {
	load_theme_textdomain( 'beckwealth', BECKWEALTH_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-spacing' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus(
		array(
			'primary' => __( 'תפריט ראשי', 'beckwealth' ),
			'footer'  => __( 'תפריט פוטר', 'beckwealth' ),
			'legal'   => __( 'תפריט משפטי (תחתית הפוטר)', 'beckwealth' ),
		)
	);

	set_post_thumbnail_size( 1200, 675, true );
	add_image_size( 'beckwealth-card', 640, 400, true );
	add_image_size( 'beckwealth-portrait', 480, 560, true );
	add_image_size( 'beckwealth-hero', 1920, 1080, true );

	// רוחב תוכן ברירת מחדל להטמעות.
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'beckwealth_setup' );

/**
 * שמות ידידותיים לגדלי תמונות בממשק המדיה.
 *
 * @param array $sizes גדלים קיימים.
 * @return array
 */
function beckwealth_image_size_names( array $sizes ): array {
	return array_merge(
		$sizes,
		array(
			'beckwealth-card'     => __( 'כרטיס (640×400)', 'beckwealth' ),
			'beckwealth-portrait' => __( 'פורטרט צוות (480×560)', 'beckwealth' ),
			'beckwealth-hero'     => __( 'הירו (1920×1080)', 'beckwealth' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'beckwealth_image_size_names' );

/**
 * הוספת מחלקות ל-body לפי הקשר.
 *
 * @param array $classes מחלקות קיימות.
 * @return array
 */
function beckwealth_body_classes( array $classes ): array {
	if ( ! is_singular() || ! has_post_thumbnail() ) {
		$classes[] = 'no-thumbnail';
	}
	if ( is_front_page() ) {
		$classes[] = 'is-front';
	}
	if ( beckwealth_mod( 'grayscale_photos' ) ) {
		$classes[] = 'bw-gs';
	}
	if ( beckwealth_mod( 'motion' ) ) {
		$classes[] = 'bw-motion';
	}
	return $classes;
}
add_filter( 'body_class', 'beckwealth_body_classes' );

/**
 * קטע הקישור לתוכן מלא ("קרא עוד") במקום [...].
 *
 * @return string
 */
function beckwealth_excerpt_more(): string {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'beckwealth_excerpt_more' );

/**
 * אורך תקציר קצר יותר לכרטיסים.
 *
 * @return int
 */
function beckwealth_excerpt_length(): int {
	return 24;
}
add_filter( 'excerpt_length', 'beckwealth_excerpt_length' );

/**
 * הוספת קישור "דלג לתוכן" מיד אחרי פתיחת ה-body.
 */
function beckwealth_skip_link(): void {
	echo '<a class="skip-link screen-reader-text" href="#main">' . esc_html__( 'דלג לתוכן הראשי', 'beckwealth' ) . '</a>';
}
add_action( 'wp_body_open', 'beckwealth_skip_link' );
