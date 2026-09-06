<?php
/**
 * היתרון: שתי עמודות (שוויץ / ישראל) עם איורי קו.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_columns = array(
	array( 'adv_ch', false ),
	array( 'adv_il', true ),
);
?>
<section id="advantage" aria-labelledby="advantage-title">
	<div class="advantage__wrap">
		<div class="bw-sec-head" data-reveal>
			<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>
			<p class="bw-kicker"><?php echo esc_html( beckwealth_mod( 'adv_kicker' ) ); ?></p>
			<h2 id="advantage-title" class="bw-h2"><?php echo esc_html( beckwealth_mod( 'adv_title' ) ); ?></h2>
		</div>
		<div class="advantage__grid">
			<?php foreach ( $bw_columns as [ $bw_prefix, $bw_is_il ] ) : ?>
				<div class="advantage__col" data-reveal>
					<div class="advantage__art<?php echo $bw_is_il ? ' advantage__art--end' : ''; ?>" aria-hidden="true">
						<?php if ( ! $bw_is_il ) : ?>
							<svg viewBox="0 0 260 78" width="230" height="69" focusable="false">
								<g class="bw-alps-sm">
									<g stroke-opacity=".22" stroke-width=".7">
										<path d="M4 68 L34 52 L48 58 L70 42 L84 50 L104 36"></path>
										<path d="M158 52 L182 32 L196 42 L218 60 L240 68 L256 70"></path>
									</g>
									<path d="M16 70 L44 62 L64 52 L84 42 L100 32 L112 20 L124 4 L134 22 L143 34 L156 50 L166 60 L182 68 L206 72 L244 72" stroke-opacity=".7" stroke-width=".95"></path>
									<path d="M124 4 L120 24 L115 42 L110 60 L106 70" stroke-opacity=".48" stroke-width=".6"></path>
									<path d="M112 20 L103 33 M118 30 L109 45 M115 44 L106 60" stroke-opacity=".38" stroke-width=".55"></path>
									<g stroke-opacity=".24" stroke-width=".5">
										<path d="M128 14 l4 11 M132 24 l5 12 M136 34 l5 13 M140 44 l5 12 M145 54 l5 11 M150 63 l4 8"></path>
										<path d="M96 46 l-4 10 M87 54 l-3 9 M78 61 l-3 8"></path>
									</g>
									<path d="M108 22 L114 15 L119 22 L124 12 L130 22 L135 15 L140 24" stroke-opacity=".42" stroke-width=".7"></path>
									<path d="M117 8 L124 4 L131 8" stroke="#96793a" stroke-opacity=".72" stroke-width=".95"></path>
								</g>
							</svg>
						<?php else : ?>
							<svg viewBox="0 0 260 80" width="234" height="72" focusable="false">
								<g class="bw-tlv">
									<path d="M6.3 72 L5.4 45.4 L12.6 44 L20.6 45.2 L19.6 71.8" stroke-opacity=".28"></path>
									<path d="M7.2 45.2 L6.4 71.6 M12.9 44.6 L12.4 72" stroke-opacity=".15"></path>
									<path d="M27.4 71.8 L28.4 31.6 L34.7 26.6 L42.7 31 L41.8 72.2" stroke-opacity=".52"></path>
									<path d="M28.6 31.4 L27.7 72 M42.2 31.2 L42.6 71.6 M28.2 31.8 L34.8 27 L42.4 31" stroke-opacity=".28"></path>
									<path d="M31.2 36.4 L38.6 35.6 M31 43.4 L38.4 43 M31.4 50 L38.8 50.4 M30.8 57.4 L38.6 56.8 M31.4 64 L38.2 64.4" stroke-opacity=".2"></path>
									<path d="M50.8 72 L49.8 53.4 L57.2 51 L64.6 53.4 L63.6 71.6" stroke-opacity=".36"></path>
									<path d="M50.4 53.2 L64.2 52.4 M57.2 51.8 L56.5 72" stroke-opacity=".18"></path>
									<path d="M71.4 72 L72.4 9.6 L74 3 L76.6 9.2 L75.6 71.8" stroke-opacity=".6"></path>
									<path d="M72.2 71.6 L72.8 9.4 M76 9.6 L75.4 72 M72 9.6 L76.4 9" stroke-opacity=".3"></path>
									<path d="M72 17.4 L76.2 16.8 M71.8 25 L76 25.4 M72.2 33.2 L76 32.6 M71.8 41 L76.2 41.2 M72 49.2 L75.8 48.6 M72.2 57 L76 57.2 M71.8 65 L76 64.6" stroke-opacity=".24"></path>
									<path d="M74 3 L74.2 -3.4" stroke="#96793a" stroke-opacity=".58"></path>
									<path d="M85.6 72 L86.4 39.4 C86 30.6 100.4 30.4 99.8 39.2 L100.6 71.6" stroke-opacity=".48"></path>
									<path d="M86.6 39.6 L85.9 72 M100 39.2 L99.4 71.8 M86.8 35 C90.4 30.8 96.2 31.2 99.4 34.8" stroke-opacity=".24"></path>
									<path d="M110.6 72 L109.7 47.4 L124.4 40.6 L123.4 72.2" stroke-opacity=".44"></path>
									<path d="M110.2 47.2 L124.2 40.8 M117.4 44 L116.6 72" stroke-opacity=".22"></path>
									<path d="M113.2 49.4 L120.8 48.8 M113.6 57.2 L120.6 57.6 M113.2 65 L120.8 64.6" stroke-opacity=".18"></path>
									<path d="M131.4 72 L132.4 23.6 L139 20 L146.6 23.4 L145.6 71.6" stroke-opacity=".58"></path>
									<path d="M132.6 23.4 L131.8 72 M146 23.6 L145.3 71.8 M132.2 23.8 L139 20.4 L146.4 23.4" stroke-opacity=".28"></path>
									<path d="M135.2 29.4 L143 28.8 M135 37.2 L142.6 37.4 M135.4 45 L143 44.6 M134.8 53.2 L142.8 53 M135.4 61 L142.6 61.4" stroke-opacity=".22"></path>
									<path d="M139 20.2 L138.8 10.6" stroke-opacity=".38"></path>
									<path d="M154.6 72 L153.8 51.4 L168.2 44.6 L182.4 51.2 L181.4 72.2" stroke-opacity=".4"></path>
									<path d="M154.2 51.2 L168.2 45 L182 51 M168.2 45 L167.4 72" stroke-opacity=".2"></path>
									<path d="M189.4 72 L190.4 35.4 L204.6 34.8 L203.6 71.6" stroke-opacity=".52"></path>
									<path d="M190.6 35.2 L189.8 72 M204 35 L203.4 71.8" stroke-opacity=".26"></path>
									<path d="M193.2 41.4 L200.8 40.8 M193.6 49 L200.6 49.4 M193.2 57.2 L200.8 56.6 M193.6 65 L200.4 65.2" stroke-opacity=".2"></path>
									<path d="M212.4 72 L211.6 57.4 L219 55.2 L226.6 57.4 L225.6 71.6" stroke-opacity=".34"></path>
									<path d="M212 57.2 L226.2 56.4 M219 56 L218.3 72" stroke-opacity=".18"></path>
									<path d="M233.4 72 L234.4 43.4 L248.6 42.8 L247.6 72.2" stroke-opacity=".48"></path>
									<path d="M234.6 43.2 L233.8 72 M248 43 L247.3 71.8 M237.2 47.4 L245 46.8 M237 55.2 L244.8 55.4 M237.4 63 L244.6 63" stroke-opacity=".22"></path>
									<path d="M-2 72.6 L120 71.6 L180 72.4 L258 71.4 M4 74 L110 73 L200 74.2 L254 73.2" stroke="#96793a" stroke-opacity=".28"></path>
								</g>
							</svg>
						<?php endif; ?>
					</div>
					<div class="advantage__body<?php echo $bw_is_il ? ' advantage__body--il' : ''; ?>">
						<div class="advantage__title-row">
							<h3 class="advantage__title"><?php echo esc_html( beckwealth_mod( $bw_prefix . '_title' ) ); ?></h3>
							<div class="advantage__tag">
								<span class="bw-en" dir="ltr"><?php echo esc_html( beckwealth_mod( $bw_prefix . '_tag' ) ); ?></span>
								<?php if ( ! $bw_is_il ) : ?>
									<svg viewBox="0 0 16 16" width="14" height="14" role="img" aria-label="<?php esc_attr_e( 'דגל שוויץ', 'beckwealth' ); ?>"><rect width="16" height="16" fill="#D52B1E"></rect><rect x="6.7" y="3.2" width="2.6" height="9.6" fill="#fff"></rect><rect x="3.2" y="6.7" width="9.6" height="2.6" fill="#fff"></rect></svg>
								<?php else : ?>
									<svg viewBox="0 0 22 16" width="19" height="14" role="img" aria-label="<?php esc_attr_e( 'דגל ישראל', 'beckwealth' ); ?>"><rect width="22" height="16" fill="#fff"></rect><rect y="1.7" width="22" height="2.2" fill="#0038B8"></rect><rect y="12.1" width="22" height="2.2" fill="#0038B8"></rect><path d="M11 5.4l2.5 4.3h-5z" fill="none" stroke="#0038B8" stroke-width="0.9"></path><path d="M11 10.6l-2.5-4.3h5z" fill="none" stroke="#0038B8" stroke-width="0.9"></path></svg>
								<?php endif; ?>
							</div>
						</div>
						<ul class="advantage__points">
							<?php foreach ( beckwealth_mod_pairs( $bw_prefix . '_points' ) as [ $bw_bold, $bw_rest ] ) : ?>
								<li><strong><?php echo esc_html( $bw_bold ); ?></strong> <?php echo esc_html( $bw_rest ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="advantage__links" data-reveal>
			<?php if ( beckwealth_mod( 'adv_link1_label' ) ) : ?>
				<a class="bw-link bw-link--2" href="<?php echo esc_url( (string) beckwealth_mod( 'adv_link1_url' ) ); ?>"><?php echo esc_html( beckwealth_mod( 'adv_link1_label' ) ); ?></a>
			<?php endif; ?>
			<?php if ( beckwealth_mod( 'adv_link2_label' ) ) : ?>
				<a class="bw-link bw-link--2" href="<?php echo esc_url( (string) beckwealth_mod( 'adv_link2_url' ) ); ?>"><?php echo esc_html( beckwealth_mod( 'adv_link2_label' ) ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
