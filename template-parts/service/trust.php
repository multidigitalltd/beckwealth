<?php
/**
 * עמוד מחלקה: נאמנות (BeckWealth-Trust).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_num     = beckwealth_service_links()[ get_post_field( 'post_name' ) ]['num'] ?? '03';
$bw_archive = (string) get_post_type_archive_link( 'service' );
$bw_nodes   = beckwealth_mod_tuples( 'trust_what_nodes', 3 );
$bw_steps   = beckwealth_mod_lines( 'trust_steps' );
$bw_regions = beckwealth_mod_tuples( 'trust_regions', 3 );
$bw_step_ph = array( __( 'תחקיר · 16:10', 'beckwealth' ), __( 'אסטרטגיה · 16:10', 'beckwealth' ), __( 'ביצוע · 16:10', 'beckwealth' ) );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ip-page ip-page--service ip-page--trust' ); ?>>

	<section id="top" class="ip-hero" aria-labelledby="page-title">
		<div class="ip-hero__wrap">
			<?php beckwealth_ip_breadcrumbs( array( array( __( 'מחלקות', 'beckwealth' ), $bw_archive ), array( get_the_title(), '' ) ), true ); ?>
			<div class="ip-hero__grid">
				<div class="ip-hero__content">
					<div class="ip-hero__kicker-row">
						<span class="ip-hero__num" dir="ltr"><?php echo esc_html( $bw_num ); ?></span>
						<p class="ip-hero__kicker"><?php echo esc_html( beckwealth_mod( 'trust_kicker' ) ?: get_the_title() ); ?></p>
						<span class="ip-hero__rule" aria-hidden="true"></span>
					</div>
					<h1 id="page-title" class="ip-hero__title"><?php echo esc_html( beckwealth_mod( 'trust_title' ) ?: get_the_title() ); ?><?php if ( beckwealth_mod( 'trust_sub' ) ) : ?> <span class="ip-hero__title-sub ip-hero__title-sub--60"><?php echo esc_html( beckwealth_mod( 'trust_sub' ) ); ?></span><?php endif; ?></h1>
					<p class="ip-hero__text"><?php echo beckwealth_strong( (string) beckwealth_mod( 'trust_intro' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
					<div class="ip-hero__actions">
						<?php beckwealth_cta( (string) beckwealth_mod( 'ip_cta_btn' ), '#form', 'lg' ); ?>
						<?php if ( beckwealth_mod( 'trust_link' ) ) : ?>
							<a class="bw-link" href="#what"><?php echo esc_html( beckwealth_mod( 'trust_link' ) ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="ip-hero__media">
					<?php beckwealth_ip_photo( beckwealth_mod( 'trust_hero_image' ) ?: ( has_post_thumbnail() ? (int) get_post_thumbnail_id() : beckwealth_default_image( 'hero-zurich' ) ), get_the_title() . ' · 3:4', get_the_title(), 'ip-hero__photo', 'bw-frame--50', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<section id="what" class="ip-sec tr-what" aria-labelledby="what-title">
		<?php beckwealth_ip_head( (string) beckwealth_mod( 'trust_what_kicker' ), (string) beckwealth_mod( 'trust_what_title' ), 'dash', 'ip-h2--44', true, 'what-title' ); ?>
		<p class="tr-what__lead" data-reveal><?php echo beckwealth_strong( (string) beckwealth_mod( 'trust_what_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
		<?php if ( $bw_nodes ) : ?>
			<div class="tr-raster" data-reveal role="list">
				<?php foreach ( $bw_nodes as $bw_i => [ $bw_en, $bw_t, $bw_d ] ) : ?>
					<?php if ( $bw_i ) : ?>
						<span class="tr-raster__arrow" aria-hidden="true">←</span>
					<?php endif; ?>
					<div class="tr-raster__node<?php echo 1 === $bw_i ? ' tr-raster__node--dark' : ''; ?>" role="listitem">
						<span class="tr-raster__en" dir="ltr"><?php echo esc_html( $bw_en ); ?></span>
						<span class="tr-raster__title"><?php echo esc_html( $bw_t ); ?></span>
						<?php if ( $bw_d ) : ?>
							<span class="tr-raster__desc"><?php echo esc_html( $bw_d ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="tr-what__more" data-reveal>
			<p class="ip-text-18"><?php echo beckwealth_strong( (string) beckwealth_mod( 'trust_what_text2' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
			<?php if ( beckwealth_mod( 'trust_lic_text' ) ) : ?>
				<div class="tr-lic">
					<span class="tr-lic__en" dir="ltr"><?php echo esc_html( beckwealth_mod( 'trust_lic_tag' ) ); ?></span>
					<p class="tr-lic__text"><?php echo esc_html( beckwealth_mod( 'trust_lic_text' ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="ip-band-alt tr-adv" aria-labelledby="adv-title">
		<div class="ip-band-alt__wrap">
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'trust_adv_kicker' ), (string) beckwealth_mod( 'trust_adv_title' ), 'dash', 'ip-h2--44', true, 'adv-title' ); ?>
			<div class="tr-adv__grid">
				<?php foreach ( beckwealth_mod_pairs( 'trust_adv_items' ) as $bw_i => [ $bw_t, $bw_text ] ) : ?>
					<div class="tr-adv__card" data-reveal data-card>
						<span class="tr-adv__num" data-num dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
						<h3 class="tr-adv__title"><?php echo esc_html( $bw_t ); ?></h3>
						<p class="tr-adv__text"><?php echo esc_html( $bw_text ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="tr-band" aria-label="<?php esc_attr_e( 'ציריך', 'beckwealth' ); ?>">
		<div class="ip-band__bg" data-gs="1">
			<?php beckwealth_slot_image( beckwealth_mod( 'trust_band_image' ) ?: beckwealth_default_image( 'zurich-lake' ), '', 'large', array( 'class' => 'tr-band__img' ), __( 'ציריך', 'beckwealth' ) ); ?>
		</div>
		<span class="ip-band__frame ip-band__frame--white" aria-hidden="true"></span>
	</section>

	<section class="ip-sec tr-how" aria-labelledby="how-title">
		<div class="ip-split tr-how__head" data-reveal>
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'trust_how_kicker' ), (string) beckwealth_mod( 'trust_how_title' ), 'dash', 'ip-h2--44', false, 'how-title', false ); ?>
			<p class="ip-split__text ip-split__text--rule"><?php echo esc_html( beckwealth_mod( 'trust_how_text' ) ); ?></p>
		</div>
		<?php if ( $bw_steps ) : ?>
			<ol class="tr-steps">
				<?php foreach ( $bw_steps as $bw_i => $bw_t ) : ?>
					<li class="tr-step" data-reveal>
						<div class="tr-step__media">
							<?php beckwealth_ip_photo( beckwealth_mod( 'trust_step_image_' . ( $bw_i + 1 ) ), $bw_step_ph[ $bw_i ] ?? '', $bw_t, 'tr-step__photo', 'bw-frame--8' ); ?>
						</div>
						<div class="tr-step__body">
							<span class="tr-step__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
							<h3 class="tr-step__title"><?php echo esc_html( $bw_t ); ?></h3>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
	</section>

	<section class="tr-us" aria-labelledby="us-title">
		<div class="tr-us__panel">
			<div class="tr-us__text" data-reveal>
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'trust_us_kicker' ), 'dash', true ); ?>
				<h2 id="us-title" class="tr-us__title"><?php echo esc_html( beckwealth_mod( 'trust_us_title' ) ); ?></h2>
				<p class="tr-us__lead"><?php echo esc_html( beckwealth_mod( 'trust_us_text' ) ); ?></p>
			</div>
			<?php if ( $bw_regions ) : ?>
				<div class="tr-regions" data-reveal>
					<?php foreach ( $bw_regions as [ $bw_en, $bw_he, $bw_d ] ) : ?>
						<div class="tr-region">
							<span class="tr-region__en" dir="ltr"><?php echo esc_html( $bw_en ); ?></span>
							<span class="tr-region__he"><?php echo esc_html( $bw_he ); ?></span>
							<span class="tr-region__d"><?php echo esc_html( $bw_d ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="tr-us__media" data-gs="1">
			<?php beckwealth_slot_image( beckwealth_mod( 'trust_us_image' ) ?: beckwealth_default_image( 'zurich-window' ), '', 'large', array( 'class' => 'tr-us__img' ), __( 'ציריך', 'beckwealth' ) ); ?>
			<span class="bw-frame tr-us__frame" aria-hidden="true"></span>
		</div>
	</section>

	<?php beckwealth_ip_faq( 'trust_faq_items', (string) beckwealth_mod( 'ip_faq_kicker' ), (string) beckwealth_mod( 'ip_faq_title' ), 'dash', 'faq' ); ?>
	<?php beckwealth_ip_case( 'trust_case_items', 'dash' ); ?>
	<?php beckwealth_ip_form_band( 'split', 'dash' ); ?>
	<?php beckwealth_ip_others( 'dash' ); ?>

	<?php if ( trim( get_the_content() ) && ! str_contains( get_the_content(), (string) get_post_meta( get_the_ID(), '_bw_tagline', true ) ) ) : ?>
		<section class="ip-sec ip-content"><div class="entry-content"><?php the_content(); ?></div></section>
	<?php endif; ?>
</article>
