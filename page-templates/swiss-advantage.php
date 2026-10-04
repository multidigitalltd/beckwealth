<?php
/**
 * Template Name: היתרון השוויצרי
 * Template Post Type: page
 *
 * עמוד "היתרון השוויצרי" לפי קובץ העיצוב BeckWealth-Swiss-Advantage.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'ip-page ip-page--swiss' ); ?>>

		<section id="top" class="ip-hero ip-hero--swiss" aria-labelledby="page-title">
			<div class="ip-hero__wrap">
				<?php beckwealth_ip_breadcrumbs( array( array( get_the_title(), '' ) ) ); ?>
				<div class="ip-hero__grid">
					<div class="ip-hero__content">
						<div class="ip-hero__kicker-row">
							<span class="bw-dia bw-dia--7" aria-hidden="true"></span>
							<p class="ip-hero__kicker"><?php echo esc_html( beckwealth_mod( 'sw_kicker' ) ); ?></p>
							<span class="ip-hero__rule" aria-hidden="true"></span>
						</div>
						<h1 id="page-title" class="ip-hero__title"><?php echo beckwealth_highlight( (string) beckwealth_mod( 'sw_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
						<p class="ip-hero__text ip-hero__text--19"><?php echo beckwealth_strong( (string) beckwealth_mod( 'sw_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
						<div class="ip-hero__actions">
							<?php beckwealth_cta( (string) beckwealth_mod( 'ip_cta_btn' ), beckwealth_contact_url(), 'lg' ); ?>
							<?php if ( beckwealth_mod( 'sw_link' ) ) : ?>
								<a class="bw-link" href="#advantages"><?php echo esc_html( beckwealth_mod( 'sw_link' ) ); ?></a>
							<?php endif; ?>
						</div>
					</div>
					<div class="ip-hero__media">
						<?php beckwealth_ip_photo( beckwealth_mod( 'sw_image' ) ?: beckwealth_default_image( 'zurich-window' ), __( 'ציריך · 3:4', 'beckwealth' ), __( 'ציריך', 'beckwealth' ), 'ip-hero__photo sw-hero__photo', 'bw-frame--50', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
						<div class="sw-hero__tag">
							<svg viewBox="0 0 16 16" width="18" height="18" role="img" aria-label="<?php esc_attr_e( 'דגל שוויץ', 'beckwealth' ); ?>"><rect width="16" height="16" fill="#D52B1E"></rect><rect x="6.7" y="3.2" width="2.6" height="9.6" fill="#fff"></rect><rect x="3.2" y="6.7" width="9.6" height="2.6" fill="#fff"></rect></svg>
							<span dir="ltr"><?php echo esc_html( beckwealth_mod( 'sw_tag' ) ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="sw-open" aria-label="<?php esc_attr_e( 'פתיחה', 'beckwealth' ); ?>">
			<div class="sw-open__box" data-reveal>
				<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>
				<p class="sw-open__text"><?php echo beckwealth_strong( (string) beckwealth_mod( 'sw_open_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				<p class="sw-open__q"><?php echo esc_html( beckwealth_mod( 'sw_open_q' ) ); ?></p>
			</div>
		</section>

		<section id="history" class="ip-band-alt sw-hist" aria-labelledby="history-title">
			<div class="ip-band-alt__wrap">
				<div class="sw-hist__head">
					<?php beckwealth_ip_head( (string) beckwealth_mod( 'sw_hist_kicker' ), (string) beckwealth_mod( 'sw_hist_title' ), 'diamond', 'ip-h2--44', false, 'history-title' ); ?>
					<p class="sw-hist__lead" data-reveal><?php echo beckwealth_strong( (string) beckwealth_mod( 'sw_hist_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				</div>
				<div class="sw-hist__grid">
					<?php foreach ( beckwealth_mod_tuples( 'sw_hist_items', 3 ) as [ $bw_y, $bw_t, $bw_text ] ) : ?>
						<div class="sw-hist__card" data-reveal>
							<div class="sw-hist__year-row"><span class="sw-hist__year" dir="ltr"><?php echo esc_html( $bw_y ); ?></span><span class="sw-hist__rule" aria-hidden="true"></span></div>
							<h3 class="sw-hist__title"><?php echo esc_html( $bw_t ); ?></h3>
							<p class="sw-hist__text"><?php echo esc_html( $bw_text ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( beckwealth_mod( 'sw_hist_note' ) ) : ?>
					<div class="sw-hist__note" data-reveal>
						<span class="sw-hist__tag" dir="ltr"><?php echo esc_html( beckwealth_mod( 'sw_hist_tag' ) ); ?></span>
						<p><?php echo esc_html( beckwealth_mod( 'sw_hist_note' ) ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<section id="advantages" class="ip-sec sw-adv" aria-labelledby="advantages-title">
			<div class="sw-adv__head" data-reveal>
				<?php beckwealth_ip_head( (string) beckwealth_mod( 'sw_adv_kicker' ), (string) beckwealth_mod( 'sw_adv_title' ), 'diamond', 'ip-h2--44', false, 'advantages-title', false ); ?>
				<p class="sw-adv__lead"><?php echo esc_html( beckwealth_mod( 'sw_adv_text' ) ); ?></p>
			</div>
			<div class="sw-adv__grid">
				<?php foreach ( beckwealth_mod_tuples( 'sw_adv_items', 3 ) as $bw_i => [ $bw_stat, $bw_t, $bw_text ] ) : ?>
					<div class="sw-adv__card" data-reveal data-card>
						<div class="sw-adv__row">
							<span class="sw-adv__num" data-num dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
							<?php if ( $bw_stat ) : ?>
								<span class="ip-tag" dir="ltr"><?php echo esc_html( $bw_stat ); ?></span>
							<?php endif; ?>
						</div>
						<h3 class="sw-adv__title"><?php echo esc_html( $bw_t ); ?></h3>
						<p class="sw-adv__text"><?php echo esc_html( $bw_text ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="ip-band sw-sum" aria-labelledby="sum-title">
			<div class="ip-band__bg" data-gs="1">
				<?php beckwealth_slot_image( beckwealth_mod( 'sw_sum_image' ) ?: beckwealth_default_image( 'hero-zurich' ), '', 'large', array(), __( 'ציריך', 'beckwealth' ) ); ?>
			</div>
			<span class="ip-band__shade" aria-hidden="true"></span>
			<span class="ip-band__frame" aria-hidden="true"></span>
			<div class="ip-band__content ip-band__content--end" data-reveal>
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'sw_sum_kicker' ), 'diamond', true ); ?>
				<h2 id="sum-title" class="screen-reader-text"><?php echo esc_html( beckwealth_mod( 'sw_sum_kicker' ) ); ?></h2>
				<p class="sw-sum__text"><?php echo esc_html( beckwealth_mod( 'sw_sum_text' ) ); ?></p>
			</div>
		</section>

		<section class="ip-sec sw-fo" aria-labelledby="fo-title">
			<div class="ip-split ip-split--rule" data-reveal>
				<?php beckwealth_ip_head( (string) beckwealth_mod( 'sw_fo_kicker' ), (string) beckwealth_mod( 'sw_fo_title' ), 'diamond', 'ip-h2--38', false, 'fo-title', false ); ?>
				<p class="ip-split__text"><?php echo esc_html( beckwealth_mod( 'sw_fo_text' ) ); ?></p>
			</div>
		</section>

		<?php beckwealth_ip_faq( 'sw_faq_items', (string) beckwealth_mod( 'ip_faq_kicker' ), (string) beckwealth_mod( 'sw_faq_title' ), 'diamond', 'faq' ); ?>

		<?php beckwealth_ip_cta( true ); ?>

		<?php if ( trim( get_the_content() ) ) : ?>
			<section class="ip-sec ip-content"><div class="entry-content"><?php the_content(); ?></div></section>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
