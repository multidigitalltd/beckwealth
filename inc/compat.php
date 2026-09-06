<?php
/**
 * תאימות לתוספים: Elementor, WooCommerce, LiteSpeed Cache.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * Elementor Pro – רישום כל מיקומי התבנית (header/footer/single/archive).
 *
 * @param object $elementor_theme_manager מנהל המיקומים.
 */
function beckwealth_elementor_locations( $elementor_theme_manager ): void {
	if ( is_object( $elementor_theme_manager ) && method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
		$elementor_theme_manager->register_all_core_location();
	}
}
add_action( 'elementor/theme/register_locations', 'beckwealth_elementor_locations' );

/**
 * תמיכה ב-WooCommerce (תצוגה בסיסית תקינה אם התוסף יופעל).
 */
function beckwealth_woocommerce_support(): void {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'beckwealth_woocommerce_support' );

/**
 * LiteSpeed Cache – ה-nonce של טופס יצירת הקשר מוגש דרך ESI כדי שלא יתיישן בעמודים במטמון.
 */
function beckwealth_litespeed_nonces(): void {
	if ( ! defined( 'LSCWP_V' ) ) {
		return;
	}
	do_action( 'litespeed_nonce', 'beckwealth_contact' );
}
add_action( 'init', 'beckwealth_litespeed_nonces' );

/**
 * מניעת שמירה במטמון של תגובת ההפניה אחרי שליחת טופס (Cloudflare/LiteSpeed/WP Rocket).
 */
function beckwealth_nocache_on_form_status(): void {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only flag.
	if ( isset( $_GET['contact'] ) ) {
		nocache_headers();
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
	}
}
add_action( 'template_redirect', 'beckwealth_nocache_on_form_status', 0 );
