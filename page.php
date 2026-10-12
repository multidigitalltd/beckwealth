<?php
/**
 * עמוד רגיל.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-header">
		<div class="page-header__inner">
			<?php beckwealth_breadcrumbs(); ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
		</div>
	</div>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
		<div class="container container--narrow">
			<?php beckwealth_post_thumbnail( 'post-thumbnail' ); ?>
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'עמודים:', 'beckwealth' ), 'after' => '</div>' ) );
				?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
