<?php
/**
 * כרטיס מחלקה (ארכיון/חיפוש).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_tagline = (string) get_post_meta( get_the_ID(), '_bw_tagline', true ) ?: get_the_excerpt();
$bw_index   = (int) ( $args['index'] ?? 0 );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--service' ); ?>>
	<div class="bw-photo card__photo" data-gs="1" data-zoom>
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'beckwealth-card', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<?php beckwealth_slot_image( 0, get_the_title() ); ?>
		<?php endif; ?>
	</div>
	<div class="card__body">
		<?php if ( $bw_index ) : ?>
			<span class="card__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_index ) ); ?></span>
		<?php endif; ?>
		<h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php if ( $bw_tagline ) : ?>
			<p class="card__tagline"><?php echo esc_html( $bw_tagline ); ?></p>
		<?php endif; ?>
		<a class="bw-link--plain" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: title */ __( '%s – לעמוד המחלקה', 'beckwealth' ), get_the_title() ) ); ?>"><?php echo esc_html( beckwealth_mod( 'dept_link_label' ) ); ?></a>
	</div>
</article>
