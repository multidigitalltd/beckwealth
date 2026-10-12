<?php
/**
 * עמוד איש צוות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$beckwealth_role     = (string) get_post_meta( get_the_ID(), '_bw_role', true );
	$beckwealth_phone    = (string) get_post_meta( get_the_ID(), '_bw_phone', true );
	$beckwealth_email    = (string) get_post_meta( get_the_ID(), '_bw_email', true );
	$beckwealth_linkedin = (string) get_post_meta( get_the_ID(), '_bw_linkedin', true );
	?>
	<div class="page-header">
		<div class="page-header__inner">
			<?php beckwealth_breadcrumbs(); ?>
			<h1 class="page-title"><?php the_title(); ?></h1>
			<?php if ( $beckwealth_role ) : ?>
				<p class="page-subtitle"><?php echo esc_html( $beckwealth_role ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
		<div class="container team-single__grid">
			<div class="team-single__media">
				<?php beckwealth_post_thumbnail( 'beckwealth-portrait' ); ?>
				<ul class="contact-list">
					<?php if ( $beckwealth_phone ) : ?>
						<li><?php echo beckwealth_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_attr( beckwealth_tel_href( $beckwealth_phone ) ); ?>" dir="ltr"><?php echo esc_html( $beckwealth_phone ); ?></a></li>
					<?php endif; ?>
					<?php if ( $beckwealth_email ) : ?>
						<li><?php echo beckwealth_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="mailto:<?php echo esc_attr( antispambot( $beckwealth_email ) ); ?>" dir="ltr"><?php echo esc_html( antispambot( $beckwealth_email ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( $beckwealth_linkedin ) : ?>
						<li><?php echo beckwealth_icon( 'linkedin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( $beckwealth_linkedin ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="entry-content"><?php the_content(); ?></div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
