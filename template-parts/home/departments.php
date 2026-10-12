<?php
/**
 * שלוש המחלקות – מסוג התוכן "שירותים" (שלושת הראשונים לפי סדר).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_services = beckwealth_get_items( 'service', 3 );
if ( ! $bw_services->have_posts() ) {
	return;
}
?>
<section id="departments" aria-labelledby="departments-title">
	<div class="dept__wrap">
		<div class="bw-sec-head" data-reveal>
			<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>
			<p class="bw-kicker"><?php echo esc_html( beckwealth_mod( 'dept_kicker' ) ); ?></p>
			<h2 id="departments-title" class="bw-h2"><?php echo esc_html( beckwealth_mod( 'dept_title' ) ); ?></h2>
		</div>
		<div class="dept__grid">
			<?php
			$bw_i = 0;
			while ( $bw_services->have_posts() ) :
				$bw_services->the_post();
				++$bw_i;
				$bw_tagline = (string) get_post_meta( get_the_ID(), '_bw_tagline', true ) ?: get_the_excerpt();
				$bw_bullets = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) get_post_meta( get_the_ID(), '_bw_bullets', true ) ) ?: array() ) );
				?>
				<article id="dep-<?php echo esc_attr( get_post_field( 'post_name' ) ); ?>" class="dept" data-reveal>
					<div class="bw-photo dept__photo" data-gs="1" data-zoom>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'beckwealth-card', array( 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<?php beckwealth_slot_image( beckwealth_default_image( 'svc-' . get_post_field( 'post_name' ) ), get_the_title() . ' · 16:10', 'large', array(), get_the_title() ); ?>
						<?php endif; ?>
					</div>
					<div class="dept__num-row">
						<span class="dept__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i ) ); ?></span>
						<span class="dept__rule" aria-hidden="true"></span>
					</div>
					<h3 class="dept__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( $bw_tagline ) : ?>
						<p class="dept__text"><?php echo esc_html( $bw_tagline ); ?></p>
					<?php endif; ?>
					<?php if ( $bw_bullets ) : ?>
						<ul class="dept__list">
							<?php foreach ( $bw_bullets as $bw_bullet ) : ?>
								<li><span class="bw-dia bw-dia--4" aria-hidden="true"></span><?php echo esc_html( $bw_bullet ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<div class="dept__spacer"></div>
					<a class="bw-link--plain" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: title */ __( '%s – לעמוד המחלקה', 'beckwealth' ), get_the_title() ) ); ?>"><?php echo esc_html( beckwealth_mod( 'dept_link_label' ) ); ?></a>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
