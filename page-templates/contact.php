<?php
/**
 * Template Name: יצירת קשר
 * Template Post Type: page
 *
 * עמוד יצירת קשר לפי קובץ העיצוב BeckWealth-Contact: כותרת ממורכזת, שתי הערים עם שעונים
 * מקומיים, "מכתב פנייה" (טופס עם מצב תודה) וערוצים ישירים.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bw_contact = beckwealth_contact_details();
$bw_cities  = array(
	array( 'cp_tlv', 'hero-telaviv', 'Asia/Jerusalem', 'cp-city--tlv' ),
	array( 'cp_zrh', 'zurich-window', 'Europe/Zurich', 'cp-city--zrh' ),
);
$bw_phone   = (string) ( beckwealth_mod_lines( 'footer_il_text' )[1] ?? '' );
$bw_phone   = $bw_contact['phone'] ?: ( preg_match( '/^[+0-9][0-9\-\s()]{6,}$/', $bw_phone ) ? $bw_phone : '' );

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'ip-page ip-page--contact' ); ?>>

		<section id="top" class="cp-hero" aria-labelledby="page-title">
			<div class="cp-hero__wrap">
				<?php beckwealth_ip_breadcrumbs( array( array( get_the_title(), '' ) ), true ); ?>
				<div class="cp-hero__box">
					<div class="cp-hero__kicker"><span class="ip-dash" aria-hidden="true"></span><span class="bw-kicker"><?php echo esc_html( beckwealth_mod( 'cp_kicker' ) ); ?></span><span class="ip-dash" aria-hidden="true"></span></div>
					<h1 id="page-title" class="cp-hero__title"><?php echo beckwealth_highlight( (string) beckwealth_mod( 'cp_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?></h1>
					<p class="cp-hero__text"><?php echo esc_html( beckwealth_mod( 'cp_text' ) ); ?></p>
				</div>
			</div>
		</section>

		<section class="cp-cities" aria-label="<?php esc_attr_e( 'המשרדים', 'beckwealth' ); ?>">
			<?php foreach ( $bw_cities as [ $bw_p, $bw_default, $bw_tz, $bw_class ] ) : ?>
				<div class="cp-city <?php echo esc_attr( $bw_class ); ?>" data-gs="1">
					<?php beckwealth_slot_image( beckwealth_mod( $bw_p . '_image' ) ?: beckwealth_default_image( $bw_default ), '', 'large', array( 'class' => 'cp-city__img' ), (string) beckwealth_mod( $bw_p . '_name' ) ); ?>
					<span class="cp-city__shade" aria-hidden="true"></span>
					<div class="cp-city__content">
						<div class="cp-city__meta">
							<span class="cp-city__en" dir="ltr"><?php echo esc_html( beckwealth_mod( $bw_p . '_en' ) ); ?></span>
							<h2 class="cp-city__name"><?php echo esc_html( beckwealth_mod( $bw_p . '_name' ) ); ?></h2>
							<p class="cp-city__addr"<?php echo 'cp_zrh' === $bw_p ? ' dir="ltr"' : ''; ?>><?php echo esc_html( beckwealth_mod( $bw_p . '_addr' ) ); ?></p>
							<?php if ( beckwealth_mod( $bw_p . '_map' ) ) : ?>
								<a class="cp-city__map" href="<?php echo esc_url( (string) beckwealth_mod( $bw_p . '_map' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( beckwealth_mod( 'cp_map_label' ) ); ?><span class="screen-reader-text"> (<?php esc_html_e( 'נפתח בחלון חדש', 'beckwealth' ); ?>)</span></a>
							<?php endif; ?>
						</div>
						<div class="cp-city__clock">
							<time class="cp-city__time" dir="ltr" data-clock="<?php echo esc_attr( $bw_tz ); ?>"><?php echo esc_html( wp_date( 'H:i', null, new DateTimeZone( $bw_tz ) ) ); ?></time>
							<span class="cp-city__clock-label"><?php echo esc_html( beckwealth_mod( 'cp_clock_label' ) ); ?></span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</section>

		<section class="cp-letter-band" aria-labelledby="letter-title">
			<div class="cp-letter-band__wrap">
				<?php beckwealth_contact_form_letter(); ?>
			</div>
		</section>

		<section class="cp-direct" aria-labelledby="direct-title">
			<div class="cp-direct__wrap">
				<div class="cp-direct__head" data-reveal>
					<h2 id="direct-title" class="cp-direct__title"><?php echo esc_html( beckwealth_mod( 'cp_direct_title' ) ); ?></h2>
					<?php $bw_hours = beckwealth_mod_lines( 'cp_hours' ); ?>
					<?php if ( $bw_hours ) : ?>
						<div class="cp-direct__hours">
							<?php foreach ( $bw_hours as $bw_h ) : ?>
								<span><?php echo esc_html( $bw_h ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="cp-direct__grid" data-reveal>
					<?php if ( $bw_phone ) : ?>
						<a class="cp-direct__item" href="<?php echo esc_attr( beckwealth_tel_href( $bw_phone ) ); ?>">
							<span class="cp-direct__en" dir="ltr">PHONE</span>
							<span class="cp-direct__val" dir="ltr"><?php echo esc_html( $bw_phone ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $bw_contact['whatsapp'] ) : ?>
						<a class="cp-direct__item" href="<?php echo esc_url( beckwealth_whatsapp_href( $bw_contact['whatsapp'], (string) beckwealth_mod( 'contact_wa_msg' ) ) ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="cp-direct__en" dir="ltr">WHATSAPP</span>
							<span class="cp-direct__val"><?php echo esc_html( beckwealth_mod( 'contact_wa_label' ) ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $bw_contact['email'] ) : ?>
						<a class="cp-direct__item" href="mailto:<?php echo esc_attr( antispambot( $bw_contact['email'] ) ); ?>">
							<span class="cp-direct__en" dir="ltr">EMAIL</span>
							<span class="cp-direct__val" dir="ltr"><?php echo esc_html( antispambot( $bw_contact['email'] ) ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( trim( get_the_content() ) ) : ?>
			<section class="ip-sec ip-content"><div class="entry-content"><?php the_content(); ?></div></section>
		<?php endif; ?>
		<div class="cp-spacer" aria-hidden="true"></div>
	</article>
	<?php
endwhile;

get_footer();
