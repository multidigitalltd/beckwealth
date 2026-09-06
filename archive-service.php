<?php
/**
 * ארכיון שירותים (מחלקות).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-header">
	<div class="page-header__inner">
		<?php beckwealth_breadcrumbs(); ?>
		<h1 class="page-title"><?php echo esc_html( beckwealth_mod( 'dept_title' ) ); ?></h1>
	</div>
</div>
<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="cards-grid">
				<?php
				$beckwealth_i = 0;
				while ( have_posts() ) :
					the_post();
					++$beckwealth_i;
					get_template_part( 'template-parts/content/card', 'service', array( 'index' => $beckwealth_i ) );
				endwhile;
				?>
			</div>
			<?php beckwealth_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
