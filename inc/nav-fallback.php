<?php
/**
 * Fallback לתפריטים כשלא הוגדר תפריט.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * תפריט ראשי חלופי כשלא שויך תפריט: העמודים הפנימיים האמיתיים (מי אנחנו, היתרון השוויצרי,
 * שלוש המחלקות, בלוג, יצירת קשר) עם סימון העמוד הנוכחי.
 *
 * @param array $args ארגומנטים של wp_nav_menu.
 */
function beckwealth_menu_fallback( array $args ): void {
	$menu_class = $args['menu_class'] ?? 'menu';
	$menu_id    = ! empty( $args['menu_id'] ) ? ' id="' . esc_attr( $args['menu_id'] ) . '"' : '';
	$services   = beckwealth_service_links();
	$blog_page  = (int) get_option( 'page_for_posts' );
	$links      = array(
		array( beckwealth_about_url(), __( 'מי אנחנו', 'beckwealth' ) ),
		array( beckwealth_swiss_url(), __( 'היתרון השוויצרי', 'beckwealth' ) ),
	);
	foreach ( $services as $service ) {
		$links[] = array( $service['url'], $service['title'] );
	}
	$links[] = array( $blog_page ? (string) get_permalink( $blog_page ) : home_url( '/#blog' ), __( 'פרסומים', 'beckwealth' ) );
	$links[] = array( beckwealth_contact_url(), __( 'יצירת קשר', 'beckwealth' ) );

	$current = beckwealth_current_url();
	echo '<ul' . $menu_id . ' class="' . esc_attr( $menu_class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	foreach ( $links as [ $url, $label ] ) {
		$is_current = '' !== $current && ! str_contains( $url, '#' ) && untrailingslashit( $url ) === $current;
		echo '<li class="menu-item' . ( $is_current ? ' current-menu-item' : '' ) . '"><a href="' . esc_url( $url ) . '"' . ( $is_current ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * כתובת העמוד הנוכחי (ללא פרמטרים) להשוואה עם פריטי התפריט החלופי.
 *
 * @return string
 */
function beckwealth_current_url(): string {
	if ( is_singular() ) {
		return untrailingslashit( (string) get_permalink() );
	}
	if ( is_home() && get_option( 'page_for_posts' ) ) {
		return untrailingslashit( (string) get_permalink( (int) get_option( 'page_for_posts' ) ) );
	}
	return '';
}

/**
 * תפריט פוטר חלופי: עוגני דף הבית כמו בעיצוב.
 *
 * @param array $args ארגומנטים.
 */
function beckwealth_footer_menu_fallback( array $args ): void {
	$home  = home_url( '/' );
	$links = array(
		array( beckwealth_about_url(), __( 'מי אנחנו', 'beckwealth' ) ),
		array( $home . '#departments', __( 'מחלקות', 'beckwealth' ) ),
		array( $home . '#blog', __( 'פרסומים', 'beckwealth' ) ),
		array( $home . '#team', __( 'צוות', 'beckwealth' ) ),
		array( beckwealth_contact_url(), __( 'יצירת קשר', 'beckwealth' ) ),
	);
	echo '<ul class="' . esc_attr( $args['menu_class'] ?? 'menu' ) . '">';
	foreach ( $links as [ $url, $label ] ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
