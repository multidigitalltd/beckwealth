<?php
/**
 * Fallback לתפריטים כשלא הוגדר תפריט.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * מציג רשימת עמודים כתפריט כשלא שויך תפריט למיקום.
 *
 * @param array $args ארגומנטים של wp_nav_menu.
 */
function beckwealth_menu_fallback( array $args ): void {
	$menu_class = $args['menu_class'] ?? 'menu';
	$menu_id    = ! empty( $args['menu_id'] ) ? ' id="' . esc_attr( $args['menu_id'] ) . '"' : '';
	$home       = is_front_page() ? '' : home_url( '/' );
	$links      = array(
		$home . '#about'      => __( 'מי אנחנו', 'beckwealth' ),
		$home . '#advantage'  => __( 'היתרון השוויצרי', 'beckwealth' ),
		$home . '#dep-assets' => __( 'ניהול הון משפחתי', 'beckwealth' ),
		$home . '#dep-tax'    => __( 'ניהול השקעות ותכנון מס', 'beckwealth' ),
		$home . '#dep-trust'  => __( 'נאמנות', 'beckwealth' ),
		$home . '#blog'       => __( 'פרסומים', 'beckwealth' ),
		$home . '#contact'    => __( 'יצירת קשר', 'beckwealth' ),
	);
	echo '<ul' . $menu_id . ' class="' . esc_attr( $menu_class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	foreach ( $links as $url => $label ) {
		echo '<li class="menu-item"><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * תפריט פוטר חלופי: עוגני דף הבית כמו בעיצוב.
 *
 * @param array $args ארגומנטים.
 */
function beckwealth_footer_menu_fallback( array $args ): void {
	$home  = home_url( '/' );
	$links = array(
		$home . '#about'       => __( 'מי אנחנו', 'beckwealth' ),
		$home . '#departments' => __( 'מחלקות', 'beckwealth' ),
		$home . '#blog'        => __( 'פרסומים', 'beckwealth' ),
		$home . '#team'        => __( 'צוות', 'beckwealth' ),
		$home . '#contact'     => __( 'יצירת קשר', 'beckwealth' ),
	);
	echo '<ul class="' . esc_attr( $args['menu_class'] ?? 'menu' ) . '">';
	foreach ( $links as $url => $label ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
