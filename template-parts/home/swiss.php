<?php
/**
 * שוויץ: כותרת + טקסט, ארבעה כרטיסי תמונה 4:5 עם כיתוב.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_cards = array_slice( beckwealth_mod_pairs( 'swiss_cards' ), 0, 4 );
?>
<section class="swiss" aria-labelledby="swiss-title">
	<div class="swiss__head">
		<div class="swiss__intro" data-reveal>
			<div class="bw-sec-head__k">
				<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>
				<p class="bw-kicker"><?php echo esc_html( beckwealth_mod( 'swiss_kicker' ) ); ?></p>
			</div>
			<h2 id="swiss-title" class="bw-h2 bw-h2--40"><?php echo esc_html( beckwealth_mod( 'swiss_title' ) ); ?></h2>
		</div>
		<p class="swiss__text" data-reveal><?php echo esc_html( beckwealth_mod( 'swiss_text' ) ); ?></p>
	</div>
	<div class="swiss__grid">
		<?php foreach ( $bw_cards as $bw_i => [ $bw_en, $bw_he ] ) : ?>
			<div class="swiss__card">
				<div class="bw-photo swiss__photo" data-gs="1" data-zoom>
					<?php beckwealth_slot_image( beckwealth_mod( 'swiss_image_' . ( $bw_i + 1 ) ) ?: beckwealth_default_image( 'swiss-' . ( $bw_i + 1 ) ), $bw_en . ' · 4:5', 'medium_large', array(), $bw_he ); ?>
				</div>
				<div class="swiss__cap">
					<span class="swiss__cap-en" dir="ltr"><?php echo esc_html( $bw_en ); ?></span>
					<span class="swiss__cap-he"><?php echo esc_html( $bw_he ); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
