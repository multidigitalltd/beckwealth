<?php
/**
 * הצוות – מסוג התוכן "צוות" (ארבעה ראשונים), פורטרטים עם היסט.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_team = beckwealth_get_items( 'team', 4 );
if ( ! $bw_team->have_posts() ) {
	return;
}
$bw_link = (string) beckwealth_mod( 'team_link_url' ) ?: '#team';
?>
<section id="team" class="team" aria-labelledby="team-title">
	<div class="team__wrap">
		<div class="team__head">
			<div class="bw-sec-head bw-sec-head--start" data-reveal>
				<div class="bw-sec-head__k">
					<span class="bw-dia bw-dia--7" data-diamond aria-hidden="true"></span>
					<p class="bw-kicker bw-kicker--tight bw-kicker--dark"><?php echo esc_html( beckwealth_mod( 'team_kicker' ) ); ?></p>
				</div>
				<h2 id="team-title" class="bw-h2"><?php echo esc_html( beckwealth_mod( 'team_title' ) ); ?></h2>
			</div>
			<p class="team__sub"><?php echo esc_html( beckwealth_mod( 'team_sub' ) ); ?></p>
		</div>
		<div class="team__grid">
			<?php
			$bw_i = 0;
			while ( $bw_team->have_posts() ) :
				$bw_team->the_post();
				++$bw_i;
				$bw_role = (string) get_post_meta( get_the_ID(), '_bw_role', true );
				?>
				<article class="member<?php echo 0 === $bw_i % 2 ? ' member--offset' : ''; ?>" data-reveal>
					<div class="member__photo-wrap">
						<div class="bw-photo member__photo" data-gs="1" data-zoom>
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'beckwealth-portrait', array( 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<?php $bw_default = array( 1 => 'team-il-1', 2 => 'team-il-2', 3 => 'team-ch-1', 4 => 'team-ch-2' ); ?>
								<?php beckwealth_slot_image( beckwealth_default_image( $bw_default[ $bw_i ] ?? '' ), __( 'פורטרט · 3:4', 'beckwealth' ), 'medium_large', array(), get_the_title() ); ?>
							<?php endif; ?>
						</div>
						<span class="bw-frame bw-frame--30" aria-hidden="true"></span>
					</div>
					<div class="member__meta">
						<h3 class="member__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<?php if ( $bw_role ) : ?>
							<p class="member__role"><?php echo esc_html( $bw_role ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php if ( beckwealth_mod( 'team_link_label' ) ) : ?>
			<a class="bw-link--dark team__more" href="<?php echo esc_url( $bw_link ); ?>"><?php echo esc_html( beckwealth_mod( 'team_link_label' ) ); ?></a>
		<?php endif; ?>
	</div>
</section>
