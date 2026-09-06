<?php
/**
 * מהבלוג: כתבה גדולה + שתי שורות + ניוזלטר.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_posts = beckwealth_get_items( 'post', 3, array( 'orderby' => 'date', 'order' => 'DESC' ) );
if ( ! $bw_posts->have_posts() ) {
	return;
}
$bw_list      = $bw_posts->posts;
$bw_main      = array_shift( $bw_list );
$bw_blog_page = (int) get_option( 'page_for_posts' );
$bw_blog_url  = $bw_blog_page ? get_permalink( $bw_blog_page ) : home_url( '/' );

/**
 * מטא של כתבה: קטגוריה ראשונה, תאריך וזמן קריאה.
 *
 * @param WP_Post $post כתבה.
 * @return array{cat:string,date:string}
 */
$bw_meta = static function ( WP_Post $post ): array {
	$cats = get_the_category( $post->ID );
	return array(
		'cat'  => $cats ? $cats[0]->name : '',
		'date' => get_the_date( 'F Y', $post ) . ' · ' . sprintf( /* translators: %d: minutes */ __( '%d דק׳ קריאה', 'beckwealth' ), beckwealth_reading_time( $post->ID ) ),
	);
};
?>
<section id="blog" aria-labelledby="blog-title">
	<div class="blog__wrap">
		<div class="blog__head">
			<div class="bw-sec-head bw-sec-head--start" data-reveal>
				<div class="bw-sec-head__k">
					<span class="bw-dia bw-dia--7" data-diamond aria-hidden="true"></span>
					<p class="bw-kicker bw-kicker--tight"><?php echo esc_html( beckwealth_mod( 'blog_kicker' ) ); ?></p>
				</div>
				<h2 id="blog-title" class="bw-h2"><?php echo esc_html( beckwealth_mod( 'blog_title' ) ); ?></h2>
			</div>
			<a class="blog__all" href="<?php echo esc_url( $bw_blog_url ); ?>"><?php echo esc_html( beckwealth_mod( 'blog_all_label' ) ); ?></a>
		</div>
		<div class="blog__grid">
			<?php $bw_m = $bw_meta( $bw_main ); ?>
			<a class="blog__main" href="<?php echo esc_url( get_permalink( $bw_main ) ); ?>" data-reveal>
				<span class="office__photo-wrap">
					<span class="bw-photo blog__main-photo" data-gs="1" data-zoom>
						<?php
						if ( has_post_thumbnail( $bw_main ) ) {
							echo get_the_post_thumbnail( $bw_main, 'beckwealth-hero', array( 'loading' => 'lazy' ) );
						} else {
							beckwealth_slot_image( beckwealth_default_image( 'blog-1' ), __( 'תמונת כתבה · 16:9', 'beckwealth' ), 'large', array(), get_the_title( $bw_main ) );
						}
						?>
					</span>
					<span class="bw-frame bw-frame--30" aria-hidden="true"></span>
				</span>
				<span class="blog__meta-box">
					<?php if ( $bw_m['cat'] ) : ?><span class="blog__cat"><?php echo esc_html( $bw_m['cat'] ); ?></span><?php endif; ?>
					<span class="blog__title"><?php echo esc_html( get_the_title( $bw_main ) ); ?></span>
					<span class="blog__date"><?php echo esc_html( $bw_m['date'] ); ?></span>
				</span>
			</a>
			<div class="blog__side" data-reveal>
				<?php foreach ( $bw_list as $bw_n => $bw_post ) : ?>
					<?php $bw_m = $bw_meta( $bw_post ); ?>
					<a class="blog__item" href="<?php echo esc_url( get_permalink( $bw_post ) ); ?>">
						<span class="bw-photo blog__item-photo" data-gs="1" data-zoom>
							<?php
							if ( has_post_thumbnail( $bw_post ) ) {
								echo get_the_post_thumbnail( $bw_post, 'beckwealth-card', array( 'loading' => 'lazy' ) );
							} else {
								beckwealth_slot_image( beckwealth_default_image( 'blog-' . ( $bw_n + 2 ) ), '', 'medium', array(), get_the_title( $bw_post ) );
							}
							?>
						</span>
						<span class="blog__meta-box">
							<?php if ( $bw_m['cat'] ) : ?><span class="blog__cat"><?php echo esc_html( $bw_m['cat'] ); ?></span><?php endif; ?>
							<span class="blog__title"><?php echo esc_html( get_the_title( $bw_post ) ); ?></span>
							<span class="blog__date"><?php echo esc_html( $bw_m['date'] ); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
				<?php beckwealth_newsletter_form(); ?>
			</div>
		</div>
	</div>
</section>
