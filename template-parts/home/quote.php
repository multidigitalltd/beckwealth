<?php
/**
 * ציטוט על תמונת רקע דביקה (פרלקסה).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="quote" aria-label="<?php esc_attr_e( 'ציטוט', 'beckwealth' ); ?>">
	<div class="quote__bg" aria-hidden="true">
		<?php beckwealth_slot_image( beckwealth_mod( 'quote_image' ) ?: beckwealth_default_image( 'quote-bg' ), __( 'תמונת רקע רחבה · 21:9', 'beckwealth' ), 'full' ); ?>
		<div class="quote__overlay"></div>
	</div>
	<div class="quote__inner">
		<figure class="quote__box" data-reveal>
			<span class="bw-dia bw-dia--9" data-diamond aria-hidden="true"></span>
			<blockquote class="quote__text"><?php echo esc_html( beckwealth_mod( 'quote_text' ) ); ?></blockquote>
			<figcaption class="quote__author">
				<span class="quote__author-line" aria-hidden="true"></span>
				<span class="quote__author-name"><?php echo esc_html( beckwealth_mod( 'quote_author' ) ); ?></span>
			</figcaption>
		</figure>
	</div>
</section>
