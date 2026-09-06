<?php
/**
 * Template Name: יצירת קשר
 * Template Post Type: page
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bw_contact = beckwealth_contact_details();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-header">
		<div class="page-header__inner">
			<?php beckwealth_breadcrumbs(); ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
		</div>
	</div>
	<section class="section contact-page">
		<div class="container contact-page__grid">
			<div class="contact-page__info">
				<?php if ( trim( get_the_content() ) ) : ?>
					<div class="entry-content"><?php the_content(); ?></div>
				<?php endif; ?>
				<ul class="contact-list contact-list--large">
					<?php if ( $bw_contact['phone'] ) : ?>
						<li><?php echo beckwealth_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_attr( beckwealth_tel_href( $bw_contact['phone'] ) ); ?>" dir="ltr"><?php echo esc_html( $bw_contact['phone'] ); ?></a></li>
					<?php endif; ?>
					<?php if ( $bw_contact['whatsapp'] ) : ?>
						<li><?php echo beckwealth_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( beckwealth_whatsapp_href( $bw_contact['whatsapp'], (string) beckwealth_mod( 'contact_wa_msg' ) ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'וואטסאפ', 'beckwealth' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $bw_contact['email'] ) : ?>
						<li><?php echo beckwealth_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="mailto:<?php echo esc_attr( antispambot( $bw_contact['email'] ) ); ?>" dir="ltr"><?php echo esc_html( antispambot( $bw_contact['email'] ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( $bw_contact['address'] ) : ?>
						<li><?php echo beckwealth_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $bw_contact['address'] ); ?></span></li>
					<?php endif; ?>
					<?php if ( $bw_contact['hours'] ) : ?>
						<li><?php echo beckwealth_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $bw_contact['hours'] ); ?></span></li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="contact-page__form">
				<?php beckwealth_contact_form( array( 'title' => __( 'השאירו פרטים ונחזור אליכם', 'beckwealth' ) ) ); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
