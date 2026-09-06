<?php
/**
 * הירו: קווי אלפים מונפשים, כותרת, כפתורים, תמונה 3:4 עם כרוס-פייד.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_img_a = beckwealth_mod( 'hero_image_a' ) ?: BECKWEALTH_URI . '/assets/img/hero-telaviv.webp';
$bw_img_b = beckwealth_mod( 'hero_image_b' ) ?: BECKWEALTH_URI . '/assets/img/hero-zurich.webp';
?>
<section id="hero" class="hero" aria-labelledby="hero-title">
	<div class="hero__wrap">
		<div class="hero__grid">
			<div class="hero__content">
				<div class="hero__art" aria-hidden="true">
					<svg viewBox="0 0 320 132" width="312" height="128" focusable="false">
						<g class="bw-alps">
							<g class="bw-alps__bg">
								<path pathLength="100" d="M6 114 L38 94 L54 100 L78 78 L94 86 L118 66"></path>
								<path pathLength="100" d="M198 90 L224 62 L240 74 L264 98 L290 110 L314 114"></path>
							</g>
							<path class="bw-alps__ridge" pathLength="100" d="M22 118 L56 109 L80 97 L102 85 L118 73 L132 53 L150 16 L162 39 L173 53 L187 73 L199 87 L217 99 L241 109 L276 117"></path>
							<g class="bw-alps__detail">
								<path d="M150 16 L145 41 L139 61 L133 85 L129 106" stroke-opacity=".5" stroke-width=".6"></path>
								<path d="M132 53 L122 67 M139 61 L129 77 M136 76 L126 94" stroke-opacity=".4" stroke-width=".55"></path>
								<path d="M155 28 l5 13 M159 38 l6 14 M163 48 l7 15 M167 58 l7 16 M172 68 l7 15 M177 78 l7 14 M182 88 l6 12 M188 96 l5 10" stroke-opacity=".26" stroke-width=".5"></path>
								<path d="M112 82 l-4 11 M102 90 l-4 10 M92 97 l-3 9 M230 76 l4 11 M240 87 l4 10 M250 97 l3 8" stroke-opacity=".2" stroke-width=".5"></path>
							</g>
							<g class="bw-alps__peaks">
								<path d="M133 52 L138 46 L143 52 L150 42 L157 52 L162 46 L167 55" stroke-opacity=".45" stroke-width=".7"></path>
								<path d="M217 71 L222 65 L227 70 L233 63 L239 73" stroke-opacity=".32" stroke-width=".6"></path>
								<path class="bw-alps__peak-glow" d="M141 33 L150 16 L159 33"></path>
							</g>
							<g class="bw-alps__mist">
								<path d="M124 88 C140 82 152 86 168 80 C178 76 186 79 194 84"></path>
								<path d="M136 104 C152 99 166 103 182 98 C196 94 206 98 214 102"></path>
							</g>
						</g>
					</svg>
				</div>
				<div class="hero__kicker-row">
					<span class="bw-dia bw-dia--7" aria-hidden="true"></span>
					<p class="hero__kicker"><?php echo esc_html( beckwealth_mod( 'hero_kicker' ) ); ?></p>
					<span class="hero__rule" aria-hidden="true"></span>
				</div>
				<h1 id="hero-title" class="hero__title"><?php echo beckwealth_highlight( (string) beckwealth_mod( 'hero_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
				<p class="hero__text"><?php echo esc_html( beckwealth_mod( 'hero_text' ) ); ?></p>
				<div class="hero__actions">
					<?php beckwealth_cta( (string) beckwealth_mod( 'hero_btn_label' ), (string) beckwealth_mod( 'hero_btn_url' ) ?: beckwealth_contact_url(), 'lg' ); ?>
					<?php if ( beckwealth_mod( 'hero_link_label' ) ) : ?>
						<a class="bw-link" href="<?php echo esc_url( (string) beckwealth_mod( 'hero_link_url' ) ); ?>"><?php echo esc_html( beckwealth_mod( 'hero_link_label' ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="hero__media">
				<div class="hero__photo" data-gs="1" data-zoom>
					<?php beckwealth_slot_image( $bw_img_a, '', 'large', array( 'class' => 'hero__img-a', 'loading' => 'eager', 'fetchpriority' => 'high' ), (string) beckwealth_mod( 'hero_cap_b_he' ) ); ?>
					<?php beckwealth_slot_image( $bw_img_b, '', 'large', array( 'class' => 'hero__img-b', 'loading' => 'eager' ), (string) beckwealth_mod( 'hero_cap_a_he' ) ); ?>
					<span class="bw-frame bw-frame--50" aria-hidden="true"></span>
				</div>
				<div class="hero__captions" aria-live="off">
					<div class="hero__cap hero__cap--a">
						<span class="hero__cap-en" dir="ltr"><?php echo esc_html( beckwealth_mod( 'hero_cap_a_en' ) ); ?></span>
						<span class="hero__cap-he"><?php echo esc_html( beckwealth_mod( 'hero_cap_a_he' ) ); ?></span>
					</div>
					<div class="hero__cap hero__cap--b" aria-hidden="true">
						<span class="hero__cap-en" dir="ltr"><?php echo esc_html( beckwealth_mod( 'hero_cap_b_en' ) ); ?></span>
						<span class="hero__cap-he"><?php echo esc_html( beckwealth_mod( 'hero_cap_b_he' ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
