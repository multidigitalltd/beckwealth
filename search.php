<?php
/**
 * תוצאות חיפוש.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-header">
	<div class="page-header__inner">
		<?php beckwealth_breadcrumbs(); ?>
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'תוצאות חיפוש עבור: %s', 'beckwealth' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
		<?php get_search_form(); ?>
	</div>
</div>
<div class="container content-area--full">
	<?php if ( have_posts() ) : ?>
		<div class="cards-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/card', 'service' === get_post_type() ? 'service' : 'post' );
			endwhile;
			?>
		</div>
		<?php beckwealth_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
