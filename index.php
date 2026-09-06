<?php
/**
 * תבנית ברירת מחדל – רשימת פוסטים (בלוג).
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
			if ( is_home() && (int) get_option( 'page_for_posts' ) ) {
				echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) );
			} else {
				esc_html_e( 'בלוג', 'beckwealth' );
			}
			?>
		</h1>
	</div>
</div>
<div class="container content-with-sidebar">
	<div class="content-area">
		<?php if ( have_posts() ) : ?>
			<div class="cards-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/card', 'post' );
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
