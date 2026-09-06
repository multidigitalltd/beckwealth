<?php
/**
 * כרטיס פוסט.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_cats = get_the_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--post' ); ?>>
	<div class="bw-photo card__photo" data-gs="1" data-zoom>
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'beckwealth-card', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<?php beckwealth_slot_image( 0, '' ); ?>
		<?php endif; ?>
	</div>
	<div class="card__body">
		<?php if ( $bw_cats ) : ?>
			<span class="blog__cat bw-kicker" style="letter-spacing:.14em"><?php echo esc_html( $bw_cats[0]->name ); ?></span>
		<?php endif; ?>
		<h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<div class="entry-meta"><?php beckwealth_posted_on(); ?></div>
	</div>
</article>
