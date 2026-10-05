<?php
/**
 * המלצות: הראשונה גדולה במרכז, שתיים נוספות בשתי עמודות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_items = beckwealth_get_items( 'testimonial', 3 );
if ( ! $bw_items->have_posts() ) {
	return;
}
$bw_list = $bw_items->posts;
$bw_main = array_shift( $bw_list );
?>
<section class="testi" aria-label="<?php esc_attr_e( 'לקוחות מספרים', 'beckwealth' ); ?>">
	<div class="testi__main">
		<figure class="testi__box" data-reveal>
			<span class="bw-dia bw-dia--10" data-diamond aria-hidden="true"></span>
			<blockquote class="testi__quote"><?php echo esc_html( wp_strip_all_tags( $bw_main->post_content ) ); ?></blockquote>
			<figcaption class="testi__author">
				<span class="testi__author-line" aria-hidden="true"></span>
				<span class="testi__author-name"><?php echo esc_html( $bw_main->post_title ); ?></span>
			</figcaption>
		</figure>
	</div>
	<?php if ( $bw_list ) : ?>
		<div class="testi__grid">
			<?php foreach ( $bw_list as $bw_item ) : ?>
				<figure class="testi__card" data-reveal>
					<blockquote class="testi__card-text"><?php echo esc_html( wp_strip_all_tags( $bw_item->post_content ) ); ?></blockquote>
					<figcaption class="testi__author">
						<span class="testi__author-line" aria-hidden="true"></span>
						<span class="testi__author-name"><?php echo esc_html( $bw_item->post_title ); ?></span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
