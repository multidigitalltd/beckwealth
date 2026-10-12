<?php
/**
 * העברה בין-דורית: טקסט דביק + שלוש שאלות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="legacy" aria-labelledby="legacy-title">
	<div class="legacy__wrap">
		<div class="legacy__aside" data-reveal>
			<div class="bw-sec-head__k">
				<span class="bw-dia bw-dia--7" data-diamond aria-hidden="true"></span>
				<p class="bw-kicker bw-kicker--tight"><?php echo esc_html( beckwealth_mod( 'legacy_kicker' ) ); ?></p>
			</div>
			<h2 id="legacy-title" class="legacy__title"><?php echo esc_html( beckwealth_mod( 'legacy_title' ) ); ?></h2>
			<p class="legacy__text"><?php echo esc_html( beckwealth_mod( 'legacy_text' ) ); ?></p>
			<?php beckwealth_cta( (string) beckwealth_mod( 'legacy_btn_label' ), (string) beckwealth_mod( 'legacy_btn_url' ) ?: beckwealth_contact_url(), 'md bw-btn--legacy' ); ?>
		</div>
		<ol class="legacy__list" data-reveal>
			<?php foreach ( beckwealth_mod_lines( 'legacy_questions' ) as $bw_i => $bw_q ) : ?>
				<li class="legacy__item">
					<span class="legacy__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
					<span class="legacy__q"><?php echo esc_html( $bw_q ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
