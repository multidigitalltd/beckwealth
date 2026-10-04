<?php
/**
 * עמוד מחלקה: ניהול הון משפחתי (BeckWealth-Family-Wealth).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_num      = beckwealth_service_links()[ get_post_field( 'post_name' ) ]['num'] ?? '01';
$bw_pillars  = beckwealth_mod_pairs( 'fam_pillars' );
$bw_archive  = (string) get_post_type_archive_link( 'service' );
$bw_pillar_a = array( 'planning', 'tax', 'risk', 'nextgen', 'succession' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ip-page ip-page--service ip-page--fam' ); ?>>

	<section id="top" class="ip-hero" aria-labelledby="page-title">
		<div class="ip-hero__wrap">
			<?php beckwealth_ip_breadcrumbs( array( array( __( 'מחלקות', 'beckwealth' ), $bw_archive ), array( get_the_title(), '' ) ) ); ?>
			<div class="ip-hero__grid">
				<div class="ip-hero__content">
					<div class="ip-hero__kicker-row">
						<span class="ip-hero__num" dir="ltr"><?php echo esc_html( $bw_num ); ?></span>
						<p class="ip-hero__kicker"><?php the_title(); ?></p>
						<span class="ip-hero__rule" aria-hidden="true"></span>
					</div>
					<h1 id="page-title" class="ip-hero__title"><?php echo beckwealth_highlight( (string) beckwealth_mod( 'fam_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
					<p class="ip-hero__text ip-hero__text--600"><?php echo beckwealth_strong( (string) beckwealth_mod( 'fam_intro' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
					<div class="ip-hero__actions">
						<?php beckwealth_cta( (string) beckwealth_mod( 'ip_cta_btn' ), '#form', 'lg' ); ?>
						<?php if ( beckwealth_mod( 'fam_link' ) ) : ?>
							<a class="bw-link" href="#includes"><?php echo esc_html( beckwealth_mod( 'fam_link' ) ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="ip-hero__media">
					<?php beckwealth_ip_photo( beckwealth_mod( 'fam_hero_image' ) ?: ( has_post_thumbnail() ? (int) get_post_thumbnail_id() : 0 ), __( 'ניהול הון אישי ומשפחתי · 3:4', 'beckwealth' ), get_the_title(), 'ip-hero__photo', 'bw-frame--50', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<section class="ip-sec fw-s2" aria-labelledby="s2-title">
		<div class="ip-split">
			<div class="ip-split__sticky" data-reveal>
				<?php beckwealth_ip_head( (string) beckwealth_mod( 'fam_s2_kicker' ), (string) beckwealth_mod( 'fam_s2_title' ), 'diamond', 'ip-h2--40', false, 's2-title', false ); ?>
			</div>
			<div class="ip-split__body" data-reveal>
				<p class="ip-lead-21"><?php echo beckwealth_strong( (string) beckwealth_mod( 'fam_s2_lead' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
				<p class="ip-text-18"><?php echo beckwealth_strong( (string) beckwealth_mod( 'fam_s2_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></p>
			</div>
		</div>
	</section>

	<section class="ip-band-alt fw-why" aria-labelledby="why-title">
		<div class="ip-band-alt__wrap">
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'fam_why_kicker' ), (string) beckwealth_mod( 'fam_why_title' ), 'diamond', 'ip-h2--44', true, 'why-title' ); ?>
			<div class="fw-why__grid">
				<?php foreach ( beckwealth_mod_lines( 'fam_why_items' ) as $bw_i => $bw_t ) : ?>
					<div class="fw-why__card" data-reveal>
						<span class="fw-why__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
						<p><?php echo esc_html( $bw_t ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<?php if ( beckwealth_mod( 'fam_why_box_title' ) ) : ?>
				<div class="fw-box" data-reveal>
					<span class="bw-dia bw-dia--12 fw-box__dia" aria-hidden="true"></span>
					<div class="fw-box__body">
						<p class="fw-box__title"><?php echo esc_html( beckwealth_mod( 'fam_why_box_title' ) ); ?></p>
						<p class="fw-box__text"><?php echo esc_html( beckwealth_mod( 'fam_why_box_text' ) ); ?></p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section id="includes" class="ip-sec fw-inc" aria-labelledby="includes-title">
		<div class="ip-head-split" data-reveal>
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'fam_inc_kicker' ), (string) beckwealth_mod( 'fam_inc_title' ), 'diamond', 'ip-h2--44', false, 'includes-title', false ); ?>
			<p class="ip-head-split__lead"><?php echo esc_html( beckwealth_mod( 'fam_inc_text' ) ); ?></p>
		</div>
		<?php if ( $bw_pillars ) : ?>
			<ol class="fw-pillars">
				<?php foreach ( $bw_pillars as $bw_i => [ $bw_t, $bw_text ] ) : ?>
					<li id="<?php echo esc_attr( $bw_pillar_a[ $bw_i ] ?? 'pillar-' . ( $bw_i + 1 ) ); ?>" class="fw-pillar" data-reveal data-card>
						<span class="fw-pillar__num" data-num dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_i + 1 ) ); ?></span>
						<h3 class="fw-pillar__title"><?php echo esc_html( $bw_t ); ?></h3>
						<p class="fw-pillar__text"><?php echo esc_html( $bw_text ); ?></p>
						<div class="fw-pillar__media">
							<?php beckwealth_ip_photo( beckwealth_mod( 'fam_pillar_image_' . ( $bw_i + 1 ) ), $bw_t . ' · 4:3', $bw_t, 'fw-pillar__photo', 'bw-frame--6' ); ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>
	</section>

	<?php beckwealth_ip_model( 'diamond', 'diamond' ); ?>

	<section class="ip-band fw-close" aria-labelledby="close-title">
		<div class="ip-band__bg" data-gs="1">
			<?php beckwealth_slot_image( beckwealth_mod( 'fam_close_image' ), __( 'תמונת רקע · משפחה / ציריך · 21:9', 'beckwealth' ), 'large', array(), (string) beckwealth_mod( 'fam_close_title' ) ); ?>
		</div>
		<span class="ip-band__shade ip-band__shade--2" aria-hidden="true"></span>
		<span class="ip-band__frame" aria-hidden="true"></span>
		<div class="ip-band__content" data-reveal>
			<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'fam_close_kicker' ), 'diamond', true ); ?>
			<h2 id="close-title" class="ip-band__title"><?php echo esc_html( beckwealth_mod( 'fam_close_title' ) ); ?></h2>
			<p class="ip-band__text"><?php echo esc_html( beckwealth_mod( 'fam_close_text' ) ); ?></p>
			<?php if ( beckwealth_mod( 'fam_close_tag' ) ) : ?>
				<p class="ip-band__tag"><?php echo esc_html( beckwealth_mod( 'fam_close_tag' ) ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php beckwealth_ip_faq( 'fam_faq_items', (string) beckwealth_mod( 'ip_faq_kicker' ), (string) beckwealth_mod( 'ip_faq_title' ), 'diamond', 'faq' ); ?>
	<?php beckwealth_ip_case( 'fam_case_items', 'diamond' ); ?>
	<?php beckwealth_ip_form_band( 'center', 'diamond' ); ?>
	<?php beckwealth_ip_others( 'diamond' ); ?>

	<?php if ( trim( get_the_content() ) && ! str_contains( get_the_content(), (string) get_post_meta( get_the_ID(), '_bw_tagline', true ) ) ) : ?>
		<section class="ip-sec ip-content"><div class="entry-content"><?php the_content(); ?></div></section>
	<?php endif; ?>
</article>
