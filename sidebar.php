<?php
/**
 * סרגל צד לבלוג.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?>
<aside id="secondary" class="widget-area" aria-label="<?php esc_attr_e( 'סרגל צד', 'beckwealth' ); ?>">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside>
