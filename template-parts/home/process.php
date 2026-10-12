<?php
/**
 * איך זה עובד: ארבעה שלבים + שורת קריאה לפעולה.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_steps = array_slice( beckwealth_mod_pairs( 'proc_steps' ), 0, 4 );
$bw_phone = beckwealth_contact_details()['phone'];
?>
<section class="process" aria-labelledby="process-title">
	<div class="process__wrap">
		<div class="bw-sec-head" data-reveal>
			<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>
			<p class="bw-kicker"><?php echo esc_html( beckwealth_mod( 'proc_kicker' ) ); ?></p>
			<h2 id="process-title" class="bw-h2"><?php echo esc_html( beckwealth_mod( 'proc_title' ) ); ?></h2>
		</div>
		<ol class="process__grid">
			<span class="process__line" aria-hidden="true"></span>
			<?php foreach ( $bw_steps as $bw_i => [ $bw_title, $bw_text ] ) : ?>
				<li class="step" data-reveal>
					<span class="bw-dia bw-dia--9 step__dia" data-diamond aria-hidden="true"></span>
					<div class="step__photo-wrap">
						<div class="bw-photo step__photo" data-gs="1" data-zoom><?php beckwealth_slot_image( beckwealth_mod( 'proc_image_' . ( $bw_i + 1 ) ) ?: beckwealth_default_image( 'step-' . ( $bw_i + 1 ) ), $bw_title . ' · 4:3', 'medium_large', array(), $bw_title ); ?></div>
						<span class="bw-frame bw-frame--8" aria-hidden="true"></span>
					</div>
					<span class="step__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
					<h3 class="step__title"><?php echo esc_html( $bw_title ); ?></h3>
					<p class="step__text"><?php echo esc_html( $bw_text ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="process__cta" data-reveal>
			<p class="process__cta-title"><?php echo esc_html( beckwealth_mod( 'proc_cta_title' ) ); ?></p>
			<div class="process__cta-actions">
				<?php if ( $bw_phone ) : ?>
					<a class="bw-link bw-link--2" href="<?php echo esc_attr( beckwealth_tel_href( $bw_phone ) ); ?>"><span dir="ltr"><?php echo esc_html( $bw_phone ); ?></span> · <?php echo esc_html( beckwealth_mod( 'proc_cta_phone' ) ); ?></a>
				<?php endif; ?>
				<?php beckwealth_cta( (string) beckwealth_mod( 'proc_cta_btn' ), beckwealth_contact_url(), 'inv' ); ?>
			</div>
		</div>
	</div>
</section>
