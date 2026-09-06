<?php
/**
 * תגובות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$beckwealth_count = get_comments_number();
			printf(
				/* translators: %s: number of comments */
				esc_html( _n( 'תגובה אחת', '%s תגובות', $beckwealth_count, 'beckwealth' ) ),
				esc_html( number_format_i18n( $beckwealth_count ) )
			);
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'התגובות סגורות.', 'beckwealth' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
