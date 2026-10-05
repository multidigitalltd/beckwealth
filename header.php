<?php
/**
 * כותרת האתר: שתי שורות (שפה · לוגו · טלפון+כפתור / תפריט) + פס התקדמות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_contact = beckwealth_contact_details();
$bw_en_url  = (string) beckwealth_mod( 'header_en_url' );
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

	<header id="masthead" class="site-header">
		<div id="bw-progress" class="bw-progress" aria-hidden="true"></div>
		<div class="bw-wrap site-header__inner">
			<div class="site-header__row">
				<div class="bw-lang" aria-label="<?php esc_attr_e( 'בחירת שפה', 'beckwealth' ); ?>">
					<span class="bw-lang__cur" aria-current="true">עב</span>
					<span class="bw-lang__sep" aria-hidden="true"></span>
					<?php if ( $bw_en_url ) : ?>
						<a href="<?php echo esc_url( $bw_en_url ); ?>" lang="en" hreflang="en">EN</a>
					<?php else : ?>
						<span lang="en" aria-disabled="true" title="<?php esc_attr_e( 'בקרוב', 'beckwealth' ); ?>">EN</span>
					<?php endif; ?>
				</div>

				<div class="site-branding">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<?php beckwealth_logo_img(); ?>
					</a>
				</div>

				<div class="site-header__actions">
					<?php if ( $bw_contact['phone'] ) : ?>
						<a class="bw-phone" href="<?php echo esc_attr( beckwealth_tel_href( $bw_contact['phone'] ) ); ?>" dir="ltr"><?php echo esc_html( $bw_contact['phone'] ); ?></a>
					<?php endif; ?>
					<?php beckwealth_cta( (string) beckwealth_mod( 'header_cta_label' ), beckwealth_contact_url() ); ?>
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<?php echo beckwealth_icon( 'menu' ) . beckwealth_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
						<span class="screen-reader-text"><?php esc_html_e( 'פתח תפריט', 'beckwealth' ); ?></span>
					</button>
				</div>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'תפריט ראשי', 'beckwealth' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'menu',
						'container'      => false,
						'fallback_cb'    => 'beckwealth_menu_fallback',
					)
				);
				?>
			</nav>
		</div>
	</header>

	<?php if ( beckwealth_mod( 'show_ticker' ) ) : ?>
		<?php get_template_part( 'template-parts/home/ticker' ); ?>
	<?php endif; ?>

	<main id="main" class="site-main">
