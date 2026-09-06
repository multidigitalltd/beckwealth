<?php
/**
 * סגירה וטופס יצירת קשר (רקע כהה).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="contact" class="contact" aria-labelledby="contact-title">
	<div class="contact__wrap">
		<div class="contact__intro" data-reveal>
			<div class="bw-sec-head__k">
				<span class="bw-dia bw-dia--7" data-diamond aria-hidden="true"></span>
				<p class="bw-kicker bw-kicker--tight bw-kicker--dark"><?php echo esc_html( beckwealth_mod( 'contact_kicker' ) ); ?></p>
			</div>
			<h2 id="contact-title" class="contact__title"><?php echo esc_html( beckwealth_mod( 'contact_title' ) ); ?></h2>
			<p class="contact__text"><?php echo esc_html( beckwealth_mod( 'contact_text' ) ); ?></p>
			<div class="contact__photo-wrap">
				<div class="bw-photo contact__photo" data-gs="1" data-zoom>
					<?php beckwealth_slot_image( beckwealth_mod( 'contact_image' ) ?: beckwealth_default_image( 'contact-photo' ), __( 'תמונה · פגישה או משרד · 16:10', 'beckwealth' ), 'large', array(), (string) beckwealth_mod( 'contact_title' ) ); ?>
					<span class="bw-frame bw-frame--10" aria-hidden="true"></span>
				</div>
			</div>
		</div>
		<div class="contact__form" data-reveal>
			<?php beckwealth_contact_form(); ?>
		</div>
	</div>
</section>
