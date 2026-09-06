<?php
/**
 * טופס חיפוש נגיש.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$beckwealth_search_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $beckwealth_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'חיפוש באתר', 'beckwealth' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $beckwealth_search_id ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'מה תרצו לחפש?', 'beckwealth' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
	<button type="submit" class="bw-btn"><?php esc_html_e( 'חיפוש', 'beckwealth' ); ?></button>
</form>
