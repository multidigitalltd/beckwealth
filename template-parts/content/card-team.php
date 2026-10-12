<?php
/**
 * כרטיס איש צוות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_role = (string) get_post_meta( get_the_ID(), '_bw_role', true );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--team' ); ?>>
	<div class="bw-photo card__photo" data-gs="1" data-zoom>
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'beckwealth-portrait', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<?php beckwealth_slot_image( 0, __( 'פורטרט', 'beckwealth' ) ); ?>
		<?php endif; ?>
	</div>
	<div class="card__body">
		<h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php if ( $bw_role ) : ?>
			<p class="card__role"><?php echo esc_html( $bw_role ); ?></p>
		<?php endif; ?>
	</div>
</article>
