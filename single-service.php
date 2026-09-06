<?php
/**
 * עמוד מחלקה (שירות) בודד.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$beckwealth_tagline = (string) get_post_meta( get_the_ID(), '_bw_tagline', true );
	?>
	<div class="page-header">
		<div class="page-header__inner">
			<?php beckwealth_breadcrumbs(); ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<?php if ( $beckwealth_tagline ) : ?>
				<p class="page-subtitle"><?php echo esc_html( $beckwealth_tagline ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
		<div class="container service-single__grid">
			<div class="service-single__content">
				<?php beckwealth_post_thumbnail( 'post-thumbnail' ); ?>
				<div class="entry-content"><?php the_content(); ?></div>
			</div>
			<aside class="service-single__aside">
				<div class="aside-box">
					<h2 class="aside-box__title"><?php esc_html_e( 'רוצים לשמוע עוד?', 'beckwealth' ); ?></h2>
					<p><?php esc_html_e( 'השאירו פרטים ונחזור אליכם לשיחת היכרות חסויה.', 'beckwealth' ); ?></p>
					<?php beckwealth_contact_form( array( 'light' => true ) ); ?>
				</div>
				<?php
				$beckwealth_others = beckwealth_get_items( 'service', 6, array( 'post__not_in' => array( get_the_ID() ) ) );
				if ( $beckwealth_others->have_posts() ) :
					?>
					<div class="aside-box">
						<h2 class="aside-box__title"><?php esc_html_e( 'מחלקות נוספות', 'beckwealth' ); ?></h2>
						<div class="link-list">
							<?php
							while ( $beckwealth_others->have_posts() ) :
								$beckwealth_others->the_post();
								echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				<?php endif; ?>
			</aside>
		</div>
	</article>
	<?php
endwhile;

get_footer();
