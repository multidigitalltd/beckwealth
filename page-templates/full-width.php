<?php
/**
 * Template Name: רוחב מלא (ללא כותרת עמוד)
 * Template Post Type: page
 *
 * מיועד לעמודי נחיתה שנבנים בעורך הבלוקים.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'full-width-page' ); ?>>
		<div class="entry-content entry-content--full">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
