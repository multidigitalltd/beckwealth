<?php
/**
 * ארכיון (קטגוריות, תגיות, תאריכים).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-header">
	<div class="page-header__inner">
		<?php beckwealth_breadcrumbs(); ?>
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</div>
</div>
<div class="container content-with-sidebar">
	<div class="content-area">
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
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
