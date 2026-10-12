<?php
/**
 * תבניות בלוקים (Block Patterns): הקבצים בתיקיית patterns/ נרשמים אוטומטית
 * על-ידי וורדפרס לפי כותרת הקובץ; כאן רק הקטגוריה.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * רישום קטגוריית תבניות.
 */
function beckwealth_register_pattern_category(): void {
	register_block_pattern_category( 'beckwealth', array( 'label' => __( 'Beck Wealth', 'beckwealth' ) ) );
}
add_action( 'init', 'beckwealth_register_pattern_category' );
