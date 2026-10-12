<?php
/**
 * פוסט בודד בבלוג.
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
			<div class="entry-meta">
				<?php beckwealth_posted_on(); ?>
				<span class="reading-time">
					<?php
					printf(
						/* translators: %d: minutes */
						esc_html( _n( 'זמן קריאה: %d דקה', 'זמן קריאה: %d דקות', beckwealth_reading_time(), 'beckwealth' ) ),
						(int) beckwealth_reading_time()
					);
					?>
				</span>
			</div>
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
			<?php the_tags( '<div class="entry-tags">', '', '</div>' ); ?>
			<nav class="post-navigation" aria-label="<?php esc_attr_e( 'ניווט בין פוסטים', 'beckwealth' ); ?>">
				<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '<span class="nav-label">' . esc_html__( 'הפוסט הקודם', 'beckwealth' ) . '</span><span class="nav-title">%title</span>' );
				next_post_link( '<div class="nav-next">%link</div>', '<span class="nav-label">' . esc_html__( 'הפוסט הבא', 'beckwealth' ) . '</span><span class="nav-title">%title</span>' );
				?>
			</nav>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
