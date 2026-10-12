<?php
/**
 * עמוד מחלקה: ניהול השקעות ותכנון מס (BeckWealth-Investments-Tax).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_num     = beckwealth_service_links()[ get_post_field( 'post_name' ) ]['num'] ?? '02';
$bw_archive = (string) get_post_type_archive_link( 'service' );
$bw_net     = beckwealth_mod_tuples( 'tax_net_items', 3 );
$bw_tiers   = beckwealth_mod_tuples( 'tax_tiers', 3 );
$bw_axis    = array_pad( explode( '|', (string) beckwealth_mod( 'tax_tiers_axis' ), 2 ), 2, '' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ip-page ip-page--service ip-page--tax' ); ?>>

	<section id="top" class="ip-hero" aria-labelledby="page-title">
		<div class="ip-hero__wrap">
			<?php beckwealth_ip_breadcrumbs( array( array( __( 'מחלקות', 'beckwealth' ), $bw_archive ), array( get_the_title(), '' ) ), true ); ?>
			<div class="ip-hero__grid">
				<div class="ip-hero__content">
					<div class="ip-hero__kicker-row">
						<span class="ip-hero__num" dir="ltr"><?php echo esc_html( $bw_num ); ?></span>
						<p class="ip-hero__kicker"><?php the_title(); ?></p>
						<span class="ip-hero__rule" aria-hidden="true"></span>
					</div>
					<h1 id="page-title" class="ip-hero__title"><?php echo esc_html( beckwealth_mod( 'tax_title' ) ); ?><?php if ( beckwealth_mod( 'tax_sub' ) ) : ?> <span class="ip-hero__title-sub"><?php echo esc_html( beckwealth_mod( 'tax_sub' ) ); ?></span><?php endif; ?></h1>
					<p class="ip-hero__text"><?php echo beckwealth_strong( (string) beckwealth_mod( 'tax_intro' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
					<div class="ip-hero__actions">
						<?php beckwealth_cta( (string) beckwealth_mod( 'ip_cta_btn' ), '#form', 'lg' ); ?>
						<?php if ( beckwealth_mod( 'tax_link' ) ) : ?>
							<a class="bw-link" href="#net"><?php echo esc_html( beckwealth_mod( 'tax_link' ) ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="ip-hero__media">
					<?php beckwealth_ip_photo( beckwealth_mod( 'tax_hero_image' ) ?: ( has_post_thumbnail() ? (int) get_post_thumbnail_id() : beckwealth_default_image( 'hero-telaviv' ) ), get_the_title() . ' · 3:4', get_the_title(), 'ip-hero__photo tx-hero__photo', 'bw-frame--50', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<section class="ip-sec tx-syn" aria-labelledby="syn-title">
		<div class="tx-syn__grid" data-reveal>
			<div class="tx-syn__boxes" aria-hidden="true">
				<div class="tx-syn__box"><span class="tx-syn__en" dir="ltr"><?php echo esc_html( beckwealth_mod( 'tax_syn_box1_en' ) ); ?></span><span class="tx-syn__label"><?php echo esc_html( beckwealth_mod( 'tax_syn_box1' ) ); ?></span></div>
				<span class="tx-syn__plus">+</span>
				<div class="tx-syn__box tx-syn__box--gold"><span class="tx-syn__en" dir="ltr"><?php echo esc_html( beckwealth_mod( 'tax_syn_box2_en' ) ); ?></span><span class="tx-syn__label"><?php echo esc_html( beckwealth_mod( 'tax_syn_box2' ) ); ?></span></div>
			</div>
			<div class="tx-syn__text">
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'tax_syn_kicker' ), 'dash' ); ?>
				<h2 id="syn-title" class="screen-reader-text"><?php echo esc_html( beckwealth_mod( 'tax_syn_kicker' ) ); ?></h2>
				<p class="ip-lead-20"><?php echo beckwealth_strong( (string) beckwealth_mod( 'tax_syn_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				<p class="ip-text-17"><?php echo beckwealth_strong( (string) beckwealth_mod( 'tax_syn_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
			</div>
		</div>
	</section>

	<section id="net" class="ip-band-alt tx-net" aria-labelledby="net-title">
		<div class="ip-band-alt__wrap tx-net__wrap">
			<div class="ip-head ip-head--center tx-net__head" data-reveal>
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'tax_net_kicker' ), 'dash' ); ?>
				<h2 id="net-title" class="ip-h2 ip-h2--48"><?php echo beckwealth_highlight( (string) beckwealth_mod( 'tax_net_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h2>
				<p class="tx-net__lead"><?php echo esc_html( beckwealth_mod( 'tax_net_text' ) ); ?></p>
			</div>
			<?php if ( $bw_net ) : ?>
				<?php $bw_last = count( $bw_net ) - 1; ?>
				<div class="tx-raster" data-reveal role="list">
					<?php foreach ( $bw_net as $bw_i => [ $bw_en, $bw_t, $bw_d ] ) : ?>
						<?php if ( $bw_i ) : ?>
							<span class="tx-raster__op" aria-hidden="true"><?php echo $bw_i === $bw_last ? '=' : '−'; ?></span>
						<?php endif; ?>
						<div class="tx-raster__cell<?php echo $bw_i === $bw_last ? ' tx-raster__cell--result' : ''; ?>" role="listitem">
							<span class="tx-raster__en" dir="ltr"><?php echo esc_html( $bw_en ); ?></span>
							<span class="tx-raster__title"><?php echo esc_html( $bw_t ); ?></span>
							<?php if ( $bw_d ) : ?>
								<span class="tx-raster__desc"><?php echo esc_html( $bw_d ); ?></span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="tx-net__image" data-reveal>
			<div class="tx-net__image-frame">
				<?php beckwealth_ip_photo( beckwealth_mod( 'tax_net_image' ) ?: beckwealth_default_image( 'zurich-window' ), __( 'ציריך · 3:1', 'beckwealth' ), __( 'ציריך', 'beckwealth' ), 'tx-net__photo', 'bw-frame bw-frame--light' ); ?>
			</div>
		</div>
	</section>

	<section class="ip-sec tx-fit" aria-labelledby="fit-title">
		<div class="ip-split">
			<div class="ip-split__sticky" data-reveal>
				<?php beckwealth_ip_head( (string) beckwealth_mod( 'tax_fit_kicker' ), (string) beckwealth_mod( 'tax_fit_title' ), 'dash', 'ip-h2--38', false, 'fit-title', false ); ?>
			</div>
			<div class="ip-split__body" data-reveal>
				<p class="ip-lead-21"><?php echo beckwealth_strong( (string) beckwealth_mod( 'tax_fit_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				<p class="ip-text-17"><?php echo beckwealth_strong( (string) beckwealth_mod( 'tax_fit_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				<?php if ( beckwealth_mod( 'tax_fit_box' ) ) : ?>
					<div class="ip-note-box"><span class="ip-dash" aria-hidden="true"></span><p><?php echo esc_html( beckwealth_mod( 'tax_fit_box' ) ); ?></p></div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( $bw_tiers ) : ?>
		<section id="tiers" class="ip-sec tx-tiers" aria-labelledby="tiers-title">
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'tax_tiers_kicker' ), (string) beckwealth_mod( 'tax_tiers_title' ), 'dash', 'ip-h2--40 tx-tiers__h2', true, 'tiers-title' ); ?>
			<ol class="tx-tiers__grid">
				<?php foreach ( $bw_tiers as $bw_i => [ $bw_range, $bw_t, $bw_text ] ) : ?>
					<li class="tx-tier tx-tier--<?php echo (int) ( $bw_i + 1 ); ?>" data-reveal data-card>
						<div class="tx-tier__head">
							<span class="tx-tier__num" data-num dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
							<?php if ( $bw_range ) : ?>
								<span class="ip-tag ip-tag--he"><?php echo esc_html( $bw_range ); ?></span>
							<?php endif; ?>
						</div>
						<div class="tx-tier__body">
							<h3 class="tx-tier__title"><?php echo esc_html( $bw_t ); ?></h3>
							<p class="tx-tier__text"><?php echo esc_html( $bw_text ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
			<div class="tx-axis" data-reveal aria-hidden="true"><span class="tx-axis__head"></span></div>
			<div class="tx-axis__labels" data-reveal>
				<span><?php echo esc_html( trim( $bw_axis[0] ) ); ?></span>
				<span><?php echo esc_html( trim( $bw_axis[1] ) ); ?></span>
			</div>
			<?php if ( beckwealth_mod( 'tax_tiers_text' ) ) : ?>
				<p class="tx-tiers__sum" data-reveal><?php echo esc_html( beckwealth_mod( 'tax_tiers_text' ) ); ?></p>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<div class="ip-gap" aria-hidden="true"></div>
	<?php beckwealth_ip_model( 'dash', 'dot' ); ?>

	<section class="ip-sec tx-leg" aria-labelledby="leg-title">
		<div class="ip-split ip-split--rule" data-reveal>
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'tax_leg_kicker' ), (string) beckwealth_mod( 'tax_leg_title' ), 'dash', 'ip-h2--38', false, 'leg-title', false ); ?>
			<p class="ip-split__text"><?php echo esc_html( beckwealth_mod( 'tax_leg_text' ) ); ?></p>
		</div>
	</section>

	<section class="ip-band ip-band--suit tx-suit" aria-labelledby="suit-title">
		<div class="ip-band__bg">
			<?php beckwealth_slot_image( beckwealth_mod( 'tax_suit_image' ) ?: beckwealth_default_image( 'zurich-lake' ), '', 'large', array(), __( 'ציריך', 'beckwealth' ) ); ?>
		</div>
		<span class="ip-band__shade ip-band__shade--3" aria-hidden="true"></span>
		<span class="ip-band__frame" aria-hidden="true"></span>
		<div class="ip-band__content" data-reveal>
			<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'tax_suit_kicker' ), 'dash', true ); ?>
			<h2 id="suit-title" class="ip-band__title ip-band__title--36"><?php echo esc_html( beckwealth_mod( 'tax_suit_title' ) ); ?></h2>
			<p class="ip-band__text ip-band__text--18"><?php echo esc_html( beckwealth_mod( 'tax_suit_text' ) ); ?></p>
		</div>
	</section>

	<?php beckwealth_ip_faq( 'tax_faq_items', (string) beckwealth_mod( 'ip_faq_kicker' ), (string) beckwealth_mod( 'ip_faq_title' ), 'dash', 'faq' ); ?>
	<?php beckwealth_ip_case( 'tax_case_items', 'dash' ); ?>
	<?php beckwealth_ip_form_band( 'split', 'dash' ); ?>
	<?php beckwealth_ip_others( 'dash' ); ?>

	<?php if ( trim( get_the_content() ) && ! str_contains( get_the_content(), (string) get_post_meta( get_the_ID(), '_bw_tagline', true ) ) ) : ?>
		<section class="ip-sec ip-content"><div class="entry-content"><?php the_content(); ?></div></section>
	<?php endif; ?>
</article>
