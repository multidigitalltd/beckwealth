<?php
/**
 * שני משרדים, תיק אחד + שלושה עקרונות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_note = array_pad( explode( '|', (string) beckwealth_mod( 'off_connector_note' ), 2 ), 2, '' );
?>
<section id="offices" aria-labelledby="offices-title">
	<div class="offices__wrap">
		<div class="bw-sec-head" data-reveal>
			<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>
			<p class="bw-kicker"><?php echo esc_html( beckwealth_mod( 'off_kicker' ) ); ?></p>
			<h2 id="offices-title" class="bw-h2"><?php echo esc_html( beckwealth_mod( 'off_title' ) ); ?></h2>
		</div>
		<p class="offices__lead" data-reveal><?php echo esc_html( beckwealth_mod( 'off_text' ) ); ?></p>

		<div class="offices__grid">
			<div class="office" data-reveal>
				<div class="office__photo-wrap">
					<div class="bw-photo office__photo" data-gs="1" data-zoom><?php beckwealth_slot_image( beckwealth_mod( 'off_ch_image' ) ?: beckwealth_default_image( 'office-zurich' ), beckwealth_mod( 'off_ch_title' ) . ' · 16:9', 'large', array(), (string) beckwealth_mod( 'off_ch_title' ) ); ?></div>
					<span class="bw-frame" aria-hidden="true"></span>
				</div>
				<div class="office__body">
					<div class="office__title-row">
						<h3 class="office__title"><?php echo esc_html( beckwealth_mod( 'off_ch_title' ) ); ?></h3>
						<span class="bw-en" dir="ltr">ZÜRICH</span>
					</div>
					<p class="office__text"><?php echo esc_html( beckwealth_mod( 'off_ch_text' ) ); ?></p>
				</div>
			</div>

			<div class="offices__connector" data-reveal aria-hidden="true">
				<span class="offices__connector-line"></span>
				<span class="offices__connector-label"><?php echo esc_html( beckwealth_mod( 'off_connector' ) ); ?></span>
				<span class="bw-dia bw-dia--9" data-diamond></span>
				<span class="offices__connector-note"><?php echo esc_html( trim( $bw_note[0] ) ); ?><br><?php echo esc_html( trim( $bw_note[1] ) ); ?></span>
				<span class="offices__connector-line offices__connector-line--b"></span>
			</div>

			<div class="office office--il" data-reveal>
				<div class="office__photo-wrap">
					<div class="bw-photo office__photo" data-gs="1" data-zoom><?php beckwealth_slot_image( beckwealth_mod( 'off_il_image' ) ?: beckwealth_default_image( 'office-telaviv' ), beckwealth_mod( 'off_il_title' ) . ' · 16:9', 'large', array(), (string) beckwealth_mod( 'off_il_title' ) ); ?></div>
					<span class="bw-frame" aria-hidden="true"></span>
				</div>
				<div class="office__body office__body--il">
					<div class="office__title-row">
						<h3 class="office__title"><?php echo esc_html( beckwealth_mod( 'off_il_title' ) ); ?></h3>
						<span class="bw-en" dir="ltr">TEL AVIV</span>
					</div>
					<p class="office__text"><?php echo esc_html( beckwealth_mod( 'off_il_text' ) ); ?></p>
				</div>
			</div>
		</div>

		<div class="principles" data-reveal>
			<?php foreach ( array_slice( beckwealth_mod_pairs( 'off_principles' ), 0, 3 ) as $bw_i => [ $bw_title, $bw_text ] ) : ?>
				<div class="principle">
					<span class="principle__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
					<h3 class="principle__title"><?php echo esc_html( $bw_title ); ?></h3>
					<p class="principle__text"><?php echo esc_html( $bw_text ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<span id="about" class="screen-reader-text" aria-hidden="true"></span>
