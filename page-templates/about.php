<?php
/**
 * Template Name: מי אנחנו
 * Template Post Type: page
 *
 * עמוד "מי אנחנו" לפי קובץ העיצוב BeckWealth-About: הירו, תמונת רוחב, ציר זמן נגלל,
 * ארבעה פרקי סיפור, מבנה הקבוצה ופס קריאה לפעולה. התוכן מהקסטומייזר (עמודים פנימיים).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

/* ציר הזמן: שורת "# ספרה|כותרת|שם" פותחת דור; "שנה|טקסט" = אירוע. */
$bw_gens = array();
foreach ( beckwealth_mod_lines( 'about_tl_items' ) as $bw_line ) {
	if ( str_starts_with( $bw_line, '#' ) ) {
		[ $bw_num, $bw_title, $bw_person ] = array_map( 'trim', array_pad( explode( '|', ltrim( $bw_line, '# ' ), 3 ), 3, '' ) );
		$bw_gens[]                         = array( 'num' => $bw_num, 'title' => $bw_title, 'person' => $bw_person, 'items' => array() );
		continue;
	}
	if ( ! $bw_gens ) {
		$bw_gens[] = array( 'num' => '', 'title' => '', 'person' => '', 'items' => array() );
	}
	[ $bw_y, $bw_t ]                                 = array_map( 'trim', array_pad( explode( '|', $bw_line, 2 ), 2, '' ) );
	$bw_gens[ count( $bw_gens ) - 1 ]['items'][] = array( $bw_y, $bw_t );
}

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'ip-page ip-page--about' ); ?>>

		<section id="top" class="ip-hero ip-hero--about" aria-labelledby="page-title">
			<div class="ip-hero__wrap">
				<?php beckwealth_ip_breadcrumbs( array( array( get_the_title(), '' ) ) ); ?>
				<div class="ip-hero__grid">
					<div class="ip-hero__content">
						<div class="ip-hero__kicker-row">
							<span class="bw-dia bw-dia--7" aria-hidden="true"></span>
							<p class="ip-hero__kicker"><?php echo esc_html( beckwealth_mod( 'about_kicker' ) ); ?></p>
							<span class="ip-hero__rule" aria-hidden="true"></span>
						</div>
						<h1 id="page-title" class="ip-hero__title ip-hero__title--xl"><?php the_title(); ?></h1>
						<?php if ( beckwealth_mod( 'about_sub' ) ) : ?>
							<p class="ip-hero__sub"><?php echo esc_html( beckwealth_mod( 'about_sub' ) ); ?></p>
						<?php endif; ?>
						<div class="ip-hero__text ip-hero__text--paras">
							<?php beckwealth_paragraphs( 'about_intro' ); ?>
						</div>
						<?php if ( beckwealth_mod( 'about_link_label' ) ) : ?>
							<div class="ip-hero__actions">
								<a class="bw-link" href="#ch-1"><?php echo esc_html( beckwealth_mod( 'about_link_label' ) ); ?></a>
							</div>
						<?php endif; ?>
					</div>
					<div class="ip-hero__media">
						<?php beckwealth_ip_photo( beckwealth_mod( 'about_hero_image' ), __( 'תמונת ארכיון משפחתית · 3:4', 'beckwealth' ), (string) beckwealth_mod( 'about_hero_cap' ), 'ip-hero__photo', 'bw-frame', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
						<?php if ( beckwealth_mod( 'about_hero_year' ) || beckwealth_mod( 'about_hero_cap' ) ) : ?>
							<div class="ab-hero__tag">
								<span class="ab-hero__year" dir="ltr"><?php echo esc_html( beckwealth_mod( 'about_hero_year' ) ); ?></span>
								<span class="ab-hero__cap"><?php echo esc_html( beckwealth_mod( 'about_hero_cap' ) ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="ab-wide" aria-label="<?php echo esc_attr( beckwealth_mod( 'about_wide_cap' ) ); ?>">
			<div class="ab-wide__frame" data-reveal>
				<?php beckwealth_ip_photo( beckwealth_mod( 'about_wide_image' ), __( 'תמונת רוחב · המשרד בציריך / תמונה משפחתית מהארכיון · 21:9', 'beckwealth' ), (string) beckwealth_mod( 'about_wide_cap' ), 'ab-wide__photo', 'bw-frame' ); ?>
			</div>
			<div class="ab-wide__caps">
				<span><?php echo esc_html( beckwealth_mod( 'about_wide_cap' ) ); ?></span>
				<span class="ab-wide__en" dir="ltr"><?php echo esc_html( beckwealth_mod( 'about_wide_en' ) ); ?></span>
			</div>
		</section>

		<?php if ( $bw_gens ) : ?>
			<section class="ip-band-alt ab-tl" aria-labelledby="tl-title">
				<div class="ip-band-alt__wrap">
					<div class="ab-tl__head" data-reveal>
						<?php beckwealth_ip_head( (string) beckwealth_mod( 'about_tl_kicker' ), (string) beckwealth_mod( 'about_tl_title' ), 'diamond', 'ip-h2--46', false, 'tl-title', false ); ?>
						<div class="ab-tl__ctrl">
							<span class="ab-tl__range" dir="ltr"><?php echo esc_html( beckwealth_mod( 'about_tl_range' ) ); ?></span>
							<div class="ab-tl__btns">
								<button type="button" class="ab-tl__btn" data-tl-prev aria-label="<?php esc_attr_e( 'הקודם', 'beckwealth' ); ?>">→</button>
								<button type="button" class="ab-tl__btn ab-tl__btn--dark" data-tl-next aria-label="<?php esc_attr_e( 'הבא', 'beckwealth' ); ?>">←</button>
							</div>
						</div>
					</div>
					<div class="ab-tl__body" data-reveal>
						<div id="tl-track" class="ab-tl__track" tabindex="0" aria-label="<?php echo esc_attr( beckwealth_mod( 'about_tl_title' ) ); ?>">
							<?php foreach ( $bw_gens as $bw_gen ) : ?>
								<div class="ab-gen">
									<?php if ( $bw_gen['title'] || $bw_gen['num'] ) : ?>
										<h3 class="ab-gen__head">
											<span class="ab-gen__num" dir="ltr"><?php echo esc_html( $bw_gen['num'] ); ?></span>
											<span class="ab-gen__title"><?php echo esc_html( $bw_gen['title'] ); ?></span>
											<span class="ab-gen__person"><?php echo esc_html( $bw_gen['person'] ); ?></span>
										</h3>
									<?php endif; ?>
									<ol class="ab-gen__items">
										<?php foreach ( $bw_gen['items'] as [ $bw_y, $bw_t ] ) : ?>
											<li class="ab-ev">
												<span class="ab-ev__rail" aria-hidden="true"><span class="ab-ev__line"></span><span class="ab-ev__node"></span></span>
												<div class="ab-ev__body">
													<span class="ab-ev__year" dir="ltr"><?php echo esc_html( $bw_y ); ?></span>
													<p class="ab-ev__text"><?php echo esc_html( $bw_t ); ?></p>
												</div>
											</li>
										<?php endforeach; ?>
									</ol>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="ab-tl__progress" aria-hidden="true"><span id="tl-bar" class="ab-tl__bar"></span></div>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php
		$bw_images = array(
			1 => __( 'דוד בעק · תמונת ארכיון · 4:5', 'beckwealth' ),
			2 => __( 'יוסף בעק · 4:5', 'beckwealth' ),
			3 => '',
			4 => __( 'יעקב בעק · 4:5', 'beckwealth' ),
		);
		for ( $bw_n = 1; $bw_n <= 4; $bw_n++ ) :
			$bw_p = 'about_ch' . $bw_n . '_';
			if ( ! beckwealth_mod( $bw_p . 'title' ) ) {
				continue;
			}
			$bw_stat  = beckwealth_mod_pairs( $bw_p . 'stat' );
			$bw_stat  = $bw_stat ? $bw_stat[0] : null;
			$bw_count = $bw_stat ? beckwealth_parse_stat( $bw_stat[0] ) : null;
			?>
			<section id="ch-<?php echo (int) $bw_n; ?>" class="ab-ch" aria-labelledby="ch-<?php echo (int) $bw_n; ?>-title">
				<div class="ab-ch__grid">
					<div class="ab-ch__aside" data-reveal>
						<div class="ab-ch__years"><span class="bw-dia bw-dia--7" data-diamond aria-hidden="true"></span><span dir="ltr"><?php echo esc_html( beckwealth_mod( $bw_p . 'years' ) ); ?></span></div>
						<div class="ab-ch__title-row">
							<span class="ab-ch__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $bw_n ) ); ?></span>
							<h2 id="ch-<?php echo (int) $bw_n; ?>-title" class="ab-ch__title"><?php echo esc_html( beckwealth_mod( $bw_p . 'title' ) ); ?></h2>
						</div>
						<?php if ( $bw_images[ $bw_n ] || beckwealth_mod( $bw_p . 'image' ) ) : ?>
							<div class="ab-ch__media">
								<?php beckwealth_ip_photo( beckwealth_mod( $bw_p . 'image' ), $bw_images[ $bw_n ], (string) beckwealth_mod( $bw_p . 'title' ), 'ab-ch__photo', 'bw-frame--10' ); ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="ab-ch__body" data-reveal>
						<?php beckwealth_paragraphs( $bw_p . 'text' ); ?>
						<?php if ( beckwealth_mod( $bw_p . 'quote' ) ) : ?>
							<blockquote class="ab-quote">
								<p class="ab-quote__text"><?php echo esc_html( beckwealth_mod( $bw_p . 'quote' ) ); ?></p>
								<?php if ( beckwealth_mod( $bw_p . 'quote_by' ) ) : ?>
									<footer class="ab-quote__by"><?php echo esc_html( beckwealth_mod( $bw_p . 'quote_by' ) ); ?></footer>
								<?php endif; ?>
							</blockquote>
						<?php endif; ?>
						<?php if ( $bw_stat && $bw_stat[0] ) : ?>
							<div class="ab-stat">
								<?php if ( $bw_count ) : ?>
									<span class="ab-stat__num" dir="ltr" data-count="<?php echo esc_attr( $bw_count['number'] ); ?>" data-prefix="<?php echo esc_attr( $bw_count['prefix'] ); ?>" data-suffix="<?php echo esc_attr( $bw_count['suffix'] ); ?>" data-decimals="<?php echo esc_attr( (string) $bw_count['decimals'] ); ?>"><?php echo esc_html( $bw_stat[0] ); ?></span>
								<?php else : ?>
									<span class="ab-stat__num" dir="ltr"><?php echo esc_html( $bw_stat[0] ); ?></span>
								<?php endif; ?>
								<p class="ab-stat__text"><?php echo esc_html( $bw_stat[1] ); ?></p>
							</div>
						<?php endif; ?>
						<?php beckwealth_paragraphs( $bw_p . 'text2' ); ?>
					</div>
				</div>
			</section>
		<?php endfor; ?>

		<?php $bw_companies = beckwealth_mod_tuples( 'about_companies', 4 ); ?>
		<?php if ( $bw_companies ) : ?>
			<section id="group" class="ip-sec ab-group" aria-labelledby="group-title">
				<?php beckwealth_ip_head( (string) beckwealth_mod( 'about_group_kicker' ), (string) beckwealth_mod( 'about_group_title' ), 'diamond', 'ip-h2--42', true, 'group-title' ); ?>
				<div class="ab-companies">
					<?php foreach ( $bw_companies as [ $bw_year, $bw_place, $bw_name, $bw_desc ] ) : ?>
						<div class="ab-co" data-reveal data-card>
							<div class="ab-co__row">
								<span class="ab-co__year" data-num dir="ltr"><?php echo esc_html( $bw_year ); ?></span>
								<span class="ab-co__rule" aria-hidden="true"></span>
								<span class="ab-co__place" dir="ltr"><?php echo esc_html( $bw_place ); ?></span>
							</div>
							<h3 class="ab-co__name"<?php echo preg_match( '/^[A-Za-z0-9&\s.,\-]+$/', $bw_name ) ? ' dir="ltr"' : ''; ?>><?php echo esc_html( $bw_name ); ?></h3>
							<p class="ab-co__desc"><?php echo esc_html( $bw_desc ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php
		$bw_cta_url = (string) beckwealth_mod( 'about_cta_link_url' ) ?: home_url( '/#team' );
		beckwealth_ip_cta( false, (string) beckwealth_mod( 'about_cta_link' ), $bw_cta_url );
		?>

		<?php if ( trim( get_the_content() ) ) : ?>
			<section class="ip-sec ip-content"><div class="entry-content"><?php the_content(); ?></div></section>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
