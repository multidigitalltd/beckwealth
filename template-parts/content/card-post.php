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
	<a class="bw-photo card__photo" data-gs="1" data-zoom <?php echo beckwealth_post_link_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?> tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'beckwealth-card', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<?php beckwealth_slot_image( 0, '' ); ?>
		<?php endif; ?>
	</a>
	<div class="card__body">
		<?php if ( $bw_cats ) : ?>
			<span class="blog__cat bw-kicker" style="letter-spacing:.14em"><?php echo esc_html( $bw_cats[0]->name ); ?></span>
		<?php endif; ?>
		<h2 class="card-title"><a <?php echo beckwealth_post_link_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>><?php the_title(); ?></a></h2>
		<p class="card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<div class="entry-meta"><?php beckwealth_posted_on(); ?></div>
	</div>
</article>
