<?php
/**
 * אין תוצאות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="no-results">
	<h2><?php esc_html_e( 'לא נמצא תוכן', 'beckwealth' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'לא נמצאו תוצאות לחיפוש. נסו מילים אחרות.', 'beckwealth' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'עדיין אין כאן תוכן. חזרו בקרוב.', 'beckwealth' ); ?></p>
	<?php endif; ?>
</div>
