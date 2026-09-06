<?php
/**
 * עמוד 404.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section error-404">
	<div class="container container--narrow">
		<p class="error-code" aria-hidden="true">404</p>
		<h1 class="page-title"><?php esc_html_e( 'העמוד שחיפשתם לא נמצא', 'beckwealth' ); ?></h1>
		<p class="page-subtitle"><?php esc_html_e( 'ייתכן שהכתובת השתנתה או שהעמוד הוסר. נסו לחפש או לחזור לדף הבית.', 'beckwealth' ); ?></p>
		<?php get_search_form(); ?>
		<?php beckwealth_cta( __( 'חזרה לדף הבית', 'beckwealth' ), home_url( '/' ) ); ?>
	</div>
</section>
<?php
get_footer();
