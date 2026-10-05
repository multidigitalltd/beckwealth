<?php
/**
 * פוטר האתר.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_contact = beckwealth_contact_details();
$bw_social  = beckwealth_social_links();
$bw_a11y    = function_exists( 'beckwealth_a11y_statement_url' ) ? beckwealth_a11y_statement_url() : '';
$bw_privacy = get_privacy_policy_url();
?>
	</main>

	<footer id="colophon" class="site-footer">
		<div class="bw-wrap bw-wrap--wide site-footer__inner">
			<div class="site-footer__top">
				<div class="site-branding">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php beckwealth_logo_img(); ?></a>
				</div>
				<p class="site-footer__cities"><?php echo esc_html( beckwealth_mod( 'footer_cities' ) ); ?></p>
			</div>

			<div class="site-footer__grid">
				<div class="site-footer__col">
					<h2 class="site-footer__title"><?php echo esc_html( beckwealth_mod( 'footer_il_title' ) ); ?></h2>
					<p class="site-footer__text">
						<?php
						foreach ( beckwealth_mod_lines( 'footer_il_text' ) as $bw_i => $bw_line ) {
							echo $bw_i ? '<br>' : '';
							if ( is_email( $bw_line ) ) {
								echo '<a href="mailto:' . esc_attr( antispambot( $bw_line ) ) . '" dir="ltr">' . esc_html( antispambot( $bw_line ) ) . '</a>';
							} elseif ( preg_match( '/^[+0-9][0-9\-\s()]{6,}$/', $bw_line ) ) {
								echo '<a href="' . esc_attr( beckwealth_tel_href( $bw_line ) ) . '" dir="ltr" style="unicode-bidi:isolate">' . esc_html( $bw_line ) . '</a>';
							} else {
								echo esc_html( $bw_line );
							}
						}
						?>
					</p>
					<?php if ( $bw_social ) : ?>
						<ul class="social-links" aria-label="<?php esc_attr_e( 'רשתות חברתיות', 'beckwealth' ); ?>">
							<?php foreach ( $bw_social as $bw_key => $bw_item ) : ?>
								<li><a href="<?php echo esc_url( $bw_item['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $bw_item['label'] ); ?>"><?php echo beckwealth_icon( $bw_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="site-footer__col">
					<h2 class="site-footer__title"><?php echo esc_html( beckwealth_mod( 'footer_ch_title' ) ); ?></h2>
					<p class="site-footer__text">
						<?php
						foreach ( beckwealth_mod_lines( 'footer_ch_text' ) as $bw_i => $bw_line ) {
							echo $bw_i ? '<br>' : '';
							if ( preg_match( '/^[+0-9][0-9\-\s()]{6,}$/', $bw_line ) ) {
								echo '<a href="' . esc_attr( beckwealth_tel_href( $bw_line ) ) . '" dir="ltr" style="unicode-bidi:isolate">' . esc_html( $bw_line ) . '</a>';
							} else {
								echo '<span dir="ltr" style="unicode-bidi:isolate">' . esc_html( $bw_line ) . '</span>';
							}
						}
						?>
					</p>
				</div>
				<div class="site-footer__col">
					<h2 class="site-footer__title"><?php echo esc_html( beckwealth_mod( 'footer_nav_title' ) ); ?></h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_class'     => 'site-footer__menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => 'beckwealth_footer_menu_fallback',
						)
					);
					?>
				</div>
				<div class="site-footer__col">
					<h2 class="site-footer__title"><?php echo esc_html( beckwealth_mod( 'footer_legal_title' ) ); ?></h2>
					<?php
					if ( has_nav_menu( 'legal' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'legal',
								'menu_class'     => 'site-footer__menu',
								'container'      => false,
								'depth'          => 1,
							)
						);
					} else {
						echo '<ul class="site-footer__menu">';
						echo '<li><a href="' . esc_url( beckwealth_contact_url() ) . '">' . esc_html__( 'גילוי נאות', 'beckwealth' ) . '</a></li>';
						echo '<li><a href="' . esc_url( beckwealth_contact_url() ) . '">' . esc_html__( 'תנאי שימוש', 'beckwealth' ) . '</a></li>';
						if ( $bw_privacy ) {
							echo '<li><a href="' . esc_url( $bw_privacy ) . '">' . esc_html__( 'פרטיות', 'beckwealth' ) . '</a></li>';
						}
						if ( $bw_a11y ) {
							echo '<li><a href="' . esc_url( $bw_a11y ) . '">' . esc_html__( 'נגישות', 'beckwealth' ) . '</a></li>';
						}
						echo '</ul>';
					}
					?>
				</div>
			</div>

			<?php if ( beckwealth_mod( 'footer_disclaimer' ) ) : ?>
				<p class="site-footer__legal"><?php echo esc_html( beckwealth_mod( 'footer_disclaimer' ) ); ?></p>
			<?php endif; ?>

			<div class="site-footer__bottom">
				<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'כל הזכויות שמורות.', 'beckwealth' ); ?></p>
				<p><?php esc_html_e( 'עיצוב ופיתוח:', 'beckwealth' ); ?> <a href="https://m-d.co.il" target="_blank" rel="noopener nofollow">Multi Digital</a></p>
			</div>

			<?php if ( beckwealth_mod( 'footer_watermark' ) ) : ?>
				<?php beckwealth_logo_img( 'site-footer__watermark', '' ); ?>
			<?php endif; ?>
		</div>
	</footer>

	<?php if ( $bw_contact['whatsapp'] && beckwealth_mod( 'show_whatsapp' ) ) : ?>
		<a class="whatsapp-float" href="<?php echo esc_url( beckwealth_whatsapp_href( $bw_contact['whatsapp'], (string) beckwealth_mod( 'contact_wa_msg' ) ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'וואטסאפ', 'beckwealth' ); ?>">
			<?php echo beckwealth_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
		</a>
	<?php endif; ?>

	<?php beckwealth_a11y_toolbar(); ?>
	<?php beckwealth_privacy_notice(); ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
