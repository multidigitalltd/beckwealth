<?php
/**
 * שורת אמון: תמונה, שלושה מונים, משפט על רקע כהה, תג FINMA.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_stats = array_slice( beckwealth_mod_pairs( 'trust_stats' ), 0, 3 );
$bw_image = beckwealth_mod( 'trust_image' ) ?: BECKWEALTH_URI . '/assets/img/zurich-window.webp';
?>
<div class="trust" data-reveal aria-label="<?php esc_attr_e( 'נתונים במספרים', 'beckwealth' ); ?>">
	<div class="trust__grid">
		<div class="bw-photo bw-photo--kb trust__photo" data-gs="1" data-zoom>
			<?php beckwealth_slot_image( $bw_image, 'ZÜRICH', 'medium_large', array(), (string) beckwealth_mod( 'trust_image_en' ) ); ?>
			<span class="trust__photo-en" dir="ltr" aria-hidden="true"><?php echo esc_html( beckwealth_mod( 'trust_image_en' ) ); ?></span>
		</div>
		<?php foreach ( $bw_stats as [ $bw_value, $bw_label ] ) : ?>
			<?php $bw_parsed = beckwealth_parse_stat( $bw_value ); ?>
			<div class="trust__cell">
				<?php if ( $bw_parsed ) : ?>
					<div class="trust__num" dir="ltr" data-count="<?php echo esc_attr( $bw_parsed['number'] ); ?>" data-decimals="<?php echo esc_attr( (string) $bw_parsed['decimals'] ); ?>" data-prefix="<?php echo esc_attr( $bw_parsed['prefix'] ); ?>" data-suffix="<?php echo esc_attr( $bw_parsed['suffix'] ); ?>"><?php echo esc_html( $bw_value ); ?></div>
				<?php else : ?>
					<div class="trust__num" dir="ltr"><?php echo esc_html( $bw_value ); ?></div>
				<?php endif; ?>
				<div class="trust__label"><?php echo esc_html( $bw_label ); ?></div>
			</div>
		<?php endforeach; ?>
		<div class="trust__claim">
			<span class="bw-dia bw-dia--6" aria-hidden="true"></span>
			<p class="trust__claim-text"><?php echo esc_html( beckwealth_mod( 'trust_claim' ) ); ?></p>
		</div>
		<div class="trust__cell">
			<div class="trust__num" dir="ltr"><?php echo esc_html( beckwealth_mod( 'trust_badge' ) ); ?></div>
			<div class="trust__label"><?php echo esc_html( beckwealth_mod( 'trust_badge_label' ) ); ?></div>
		</div>
	</div>
</div>
