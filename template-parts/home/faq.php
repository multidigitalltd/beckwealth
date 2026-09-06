<?php
/**
 * שאלות ותשובות – אקורדיון נגיש (button + aria-expanded), פריט אחד פתוח.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_faqs = beckwealth_get_items( 'faq', 12 );
if ( ! $bw_faqs->have_posts() ) {
	return;
}
$bw_open_first = (bool) beckwealth_mod( 'open_first_faq' );
?>
<section class="faq" aria-labelledby="faq-title">
	<div class="faq__wrap">
		<div class="bw-sec-head bw-sec-head--start" data-reveal>
			<div class="bw-sec-head__k">
				<span class="bw-dia bw-dia--7" data-diamond aria-hidden="true"></span>
				<p class="bw-kicker bw-kicker--tight"><?php echo esc_html( beckwealth_mod( 'faq_kicker' ) ); ?></p>
			</div>
			<h2 id="faq-title" class="bw-h2 bw-h2--38"><?php echo esc_html( beckwealth_mod( 'faq_title' ) ); ?></h2>
		</div>
		<div class="faq__list" data-reveal>
			<?php
			$bw_i = 0;
			while ( $bw_faqs->have_posts() ) :
				$bw_faqs->the_post();
				++$bw_i;
				$bw_open = $bw_open_first && 1 === $bw_i;
				$bw_id   = 'faq-' . get_the_ID();
				?>
				<div class="faq__item">
					<h3>
						<button type="button" class="faq__btn" id="<?php echo esc_attr( $bw_id ); ?>-btn" aria-expanded="<?php echo $bw_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $bw_id ); ?>">
							<span class="faq__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i ) ); ?></span>
							<span class="faq__q"><?php the_title(); ?></span>
							<span class="faq__sym" aria-hidden="true"><?php echo $bw_open ? '−' : '+'; ?></span>
						</button>
					</h3>
					<div class="faq__a" id="<?php echo esc_attr( $bw_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $bw_id ); ?>-btn"<?php echo $bw_open ? '' : ' hidden'; ?>>
						<?php echo wp_kses_post( wpautop( get_the_content() ) ); ?>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
