<?php
/**
 * עזרי העמודים הפנימיים (מי אנחנו, היתרון השוויצרי, עמודי המחלקות, יצירת קשר):
 * זיהוי תבניות העיצוב, קישורים בין העמודים, ומקטעים משותפים (שאלות, שוויץ/ישראל,
 * סיפור מקרה, מחלקות נוספות, פס קריאה לפעולה, טופס מקוצר).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * כתובת העמוד הראשון (מפורסם) שמשויכת לו תבנית עמוד מסוימת.
 *
 * @param string $template נתיב התבנית, למשל page-templates/about.php.
 * @return string URL או ריק.
 */
function beckwealth_template_page_url( string $template ): string {
	static $cache = array();
	if ( isset( $cache[ $template ] ) ) {
		return $cache[ $template ];
	}
	$pages = get_posts(
		array(
			'post_type'              => 'page',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'meta_key'               => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'             => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	$cache[ $template ] = $pages ? (string) get_permalink( (int) $pages[0] ) : '';
	return $cache[ $template ];
}

/**
 * כתובת עמוד "היתרון השוויצרי" (או מקטע היתרון בדף הבית).
 *
 * @return string
 */
function beckwealth_swiss_url(): string {
	return beckwealth_template_page_url( 'page-templates/swiss-advantage.php' ) ?: home_url( '/#advantage' );
}

/**
 * כתובת עמוד "מי אנחנו" (או דף הבית).
 *
 * @return string
 */
function beckwealth_about_url(): string {
	return beckwealth_template_page_url( 'page-templates/about.php' ) ?: home_url( '/#about' );
}

/**
 * תבנית העיצוב של עמוד מחלקה לפי ה-slug של השירות ('' = פריסה כללית).
 *
 * @param int|null $post_id מזהה (ברירת מחדל: הנוכחי).
 * @return string
 */
function beckwealth_service_design( ?int $post_id = null ): string {
	$post_id = $post_id ?? get_the_ID();
	if ( ! $post_id || 'service' !== get_post_type( $post_id ) ) {
		return '';
	}
	$slug = (string) get_post_field( 'post_name', $post_id );
	return beckwealth_service_designs()[ $slug ] ?? '';
}

/**
 * האם העמוד הנוכחי הוא אחד מעמודי העיצוב הפנימיים (נטענים pages.css/js).
 *
 * @return bool
 */
function beckwealth_is_design_page(): bool {
	if ( is_page_template( array( 'page-templates/about.php', 'page-templates/swiss-advantage.php', 'page-templates/contact.php' ) ) ) {
		return true;
	}
	return is_singular( 'service' ) && '' !== beckwealth_service_design();
}

/**
 * פסקה עם הדגשה: טקסט בין ** הופך ל-<strong>. הפלט מנוקה.
 *
 * @param string $text טקסט.
 * @return string
 */
function beckwealth_strong( string $text ): string {
	return (string) preg_replace( '/\*\*(.+?)\*\*/u', '<strong>$1</strong>', esc_html( $text ) );
}

/**
 * הדפסת פסקאות משדה textarea (שורה לכל פסקה, ** = מודגש).
 *
 * @param string $key   מפתח השדה.
 * @param string $class מחלקה לכל <p>.
 */
function beckwealth_paragraphs( string $key, string $class = '' ): void {
	foreach ( beckwealth_mod_lines( $key ) as $line ) {
		echo '<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . beckwealth_strong( $line ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
	}
}

/**
 * פיצול שורות "א|ב|ג" לשלשות (או יותר) – משלים ערכים ריקים.
 *
 * @param string $key   מפתח.
 * @param int    $parts מספר חלקים.
 * @return array<int, string[]>
 */
function beckwealth_mod_tuples( string $key, int $parts = 3 ): array {
	$rows = array();
	foreach ( beckwealth_mod_lines( $key ) as $line ) {
		$rows[] = array_map( 'trim', array_pad( explode( '|', $line, $parts ), $parts, '' ) );
	}
	return $rows;
}

/**
 * שלוש המחלקות (לפי סדר): slug => [מספר, כותרת, תיאור קצר, קישור].
 *
 * @return array<string, array{num:string,title:string,tagline:string,url:string,id:int}>
 */
function beckwealth_service_links(): array {
	static $links = null;
	if ( null !== $links ) {
		return $links;
	}
	$links = array();
	$query = beckwealth_get_items( 'service', 3 );
	$i     = 0;
	foreach ( $query->posts as $post ) {
		++$i;
		$links[ $post->post_name ] = array(
			'num'     => beckwealth_pad( $i ),
			'title'   => get_the_title( $post ),
			'tagline' => (string) get_post_meta( $post->ID, '_bw_tagline', true ),
			'url'     => (string) get_permalink( $post ),
			'id'      => (int) $post->ID,
		);
	}
	return $links;
}

/**
 * פירורי לחם של העמודים הפנימיים (אנימציית כניסה).
 *
 * @param array<int, array{0:string,1:string}> $items פריטים [תווית, כתובת] – האחרון בלי כתובת.
 * @param bool                                 $slash מפריד "/" במקום יהלום.
 */
function beckwealth_ip_breadcrumbs( array $items, bool $slash = false ): void {
	if ( function_exists( 'yoast_breadcrumb' ) || function_exists( 'rank_math_the_breadcrumbs' ) ) {
		echo '<div class="ip-crumbs' . ( $slash ? ' ip-crumbs--slash' : '' ) . '">';
		beckwealth_breadcrumbs();
		echo '</div>';
		return;
	}
	echo '<nav class="breadcrumbs ip-crumbs' . ( $slash ? ' ip-crumbs--slash' : '' ) . '" aria-label="' . esc_attr__( 'פירורי לחם', 'beckwealth' ) . '"><ol>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'דף הבית', 'beckwealth' ) . '</a></li>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => [ $label, $url ] ) {
		if ( $i === $last || '' === $url ) {
			echo '<li><span' . ( $i === $last ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</span></li>';
		} else {
			echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/**
 * שורת "קיקר" של מקטע: סימן (יהלום/קו) + טקסט.
 *
 * @param string $text  טקסט.
 * @param string $mark  'diamond' | 'dash'.
 * @param bool   $dark  על רקע כהה.
 * @param string $class מחלקות נוספות.
 */
function beckwealth_ip_kicker( string $text, string $mark = 'diamond', bool $dark = false, string $class = '' ): void {
	echo '<div class="ip-kicker-row' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">';
	if ( 'dash' === $mark ) {
		echo '<span class="ip-dash" aria-hidden="true"></span>';
	} else {
		echo '<span class="bw-dia bw-dia--8" data-diamond aria-hidden="true"></span>';
	}
	echo '<p class="bw-kicker' . ( $dark ? ' bw-kicker--dark' : '' ) . '">' . esc_html( $text ) . '</p>';
	echo '</div>';
}

/**
 * כותרת מקטע (קיקר + h2) – לתחילת השורה או ממורכז.
 *
 * @param string $kicker  קיקר.
 * @param string $title   כותרת (** = זהב).
 * @param string $mark    סימן הקיקר.
 * @param string $h_class מחלקה ל-h2.
 * @param bool   $center  ממורכז.
 * @param string $id      מזהה ל-h2 (ל-aria-labelledby).
 * @param bool   $reveal  עם data-reveal.
 */
function beckwealth_ip_head( string $kicker, string $title, string $mark = 'diamond', string $h_class = '', bool $center = false, string $id = '', bool $reveal = true ): void {
	echo '<div class="ip-head' . ( $center ? ' ip-head--center' : '' ) . '"' . ( $reveal ? ' data-reveal' : '' ) . '>';
	beckwealth_ip_kicker( $kicker, $mark );
	echo '<h2 class="ip-h2' . ( $h_class ? ' ' . esc_attr( $h_class ) : '' ) . '"' . ( $id ? ' id="' . esc_attr( $id ) . '"' : '' ) . '>' . beckwealth_highlight( $title ) . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
	echo '</div>';
}

/**
 * תמונה למשבצת עיצוב עם מסגרת זהב פנימית.
 *
 * @param int|string $source      מזהה מדיה / URL ברירת מחדל.
 * @param string     $placeholder כיתוב למשבצת ריקה.
 * @param string     $alt         טקסט חלופי.
 * @param string     $class       מחלקות ל-.bw-photo.
 * @param string     $frame       מחלקת מסגרת (bw-frame--50 וכו').
 * @param array      $attr        תכונות ל-img.
 * @param bool       $zoom        זום במעבר עכבר.
 */
function beckwealth_ip_photo( $source, string $placeholder, string $alt, string $class = '', string $frame = 'bw-frame--50', array $attr = array(), bool $zoom = true ): void {
	echo '<div class="bw-photo' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" data-gs="1"' . ( $zoom ? ' data-zoom' : '' ) . '>';
	beckwealth_slot_image( $source, $placeholder, 'large', $attr, $alt );
	echo '</div>';
	if ( $frame ) {
		echo '<span class="bw-frame ' . esc_attr( $frame ) . '" aria-hidden="true"></span>';
	}
}

/**
 * כפתור "נייר" (רקע בהיר → זהב) לפסים הכהים.
 *
 * @param string $label טקסט.
 * @param string $url   כתובת.
 * @param bool   $dia   עם יהלום.
 */
function beckwealth_ip_paper_btn( string $label, string $url, bool $dia = true ): void {
	if ( '' === $label ) {
		return;
	}
	echo '<a class="bw-btn bw-btn--paper" href="' . esc_url( $url ) . '">' . esc_html( $label ) . ( $dia ? '<span class="bw-dia bw-dia--5 bw-dia--cur" aria-hidden="true"></span>' : '' ) . '</a>';
}

/**
 * מקטע שאלות ותשובות (אקורדיון נגיש) מתוך שדה "שאלה|תשובה".
 *
 * @param string $key    מפתח השדה.
 * @param string $kicker קיקר.
 * @param string $title  כותרת.
 * @param string $mark   סימן הקיקר.
 * @param string $id     מזהה ייחודי.
 */
function beckwealth_ip_faq( string $key, string $kicker, string $title, string $mark = 'diamond', string $id = 'faq' ): void {
	$items = beckwealth_mod_pairs( $key );
	if ( ! $items ) {
		return;
	}
	?>
	<section class="ip-sec ip-faq" aria-labelledby="<?php echo esc_attr( $id ); ?>-title">
		<div class="ip-faq__grid">
			<?php beckwealth_ip_head( $kicker, $title, $mark, 'ip-h2--38', false, $id . '-title' ); ?>
			<div class="faq__list" data-reveal>
				<?php foreach ( $items as $i => [ $q, $a ] ) : ?>
					<?php
					$open = 0 === $i && beckwealth_mod( 'open_first_faq' );
					$pid  = $id . '-' . ( $i + 1 );
					?>
					<div class="faq__item">
						<h3>
							<button type="button" class="faq__btn" id="<?php echo esc_attr( $pid ); ?>-btn" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $pid ); ?>">
								<span class="faq__num" dir="ltr"><?php echo esc_html( beckwealth_pad( $i + 1 ) ); ?></span>
								<span class="faq__q"><?php echo esc_html( $q ); ?></span>
								<span class="faq__sym" aria-hidden="true"><?php echo $open ? '−' : '+'; ?></span>
							</button>
						</h3>
						<div class="faq__a" id="<?php echo esc_attr( $pid ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $pid ); ?>-btn"<?php echo $open ? '' : ' hidden'; ?>>
							<p><?php echo esc_html( $a ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * מקטע "שוויץ וישראל" – המודל (משתמש בנקודות ההיתרון של דף הבית).
 *
 * @param string $mark   סימן הקיקר.
 * @param string $bullet 'diamond' | 'dot'.
 */
function beckwealth_ip_model( string $mark = 'diamond', string $bullet = 'diamond' ): void {
	$cols = array(
		array( 'adv_ch', 'ip_model_ch', false ),
		array( 'adv_il', 'ip_model_il', true ),
	);
	?>
	<section class="ip-band-alt ip-model" aria-labelledby="model-title">
		<div class="ip-band-alt__wrap">
			<?php beckwealth_ip_head( (string) beckwealth_mod( 'ip_model_kicker' ), (string) beckwealth_mod( 'ip_model_title' ), $mark, '', true, 'model-title' ); ?>
			<div class="ip-model__grid">
				<?php foreach ( $cols as $k => [ $prefix, $title_key, $is_il ] ) : ?>
					<?php if ( $k ) : ?>
						<div class="ip-model__connector" aria-hidden="true">
							<span class="ip-model__line"></span>
							<?php if ( 'diamond' === $bullet ) : ?>
								<span class="ip-model__swap"><span>⇄</span></span>
							<?php else : ?>
								<span class="ip-model__swap ip-model__swap--plain">⇄</span>
							<?php endif; ?>
							<span class="ip-model__line"></span>
						</div>
					<?php endif; ?>
					<div class="ip-model__col<?php echo $is_il ? ' ip-model__col--il' : ''; ?>" data-reveal>
						<div class="ip-model__title-row">
							<h3 class="ip-model__title"><?php echo esc_html( beckwealth_mod( $title_key ) ); ?></h3>
							<div class="advantage__tag">
								<span class="bw-en" dir="ltr"><?php echo esc_html( beckwealth_mod( $prefix . '_tag' ) ); ?></span>
								<?php if ( ! $is_il ) : ?>
									<svg viewBox="0 0 16 16" width="14" height="14" role="img" aria-label="<?php esc_attr_e( 'דגל שוויץ', 'beckwealth' ); ?>"><rect width="16" height="16" fill="#D52B1E"></rect><rect x="6.7" y="3.2" width="2.6" height="9.6" fill="#fff"></rect><rect x="3.2" y="6.7" width="9.6" height="2.6" fill="#fff"></rect></svg>
								<?php else : ?>
									<svg viewBox="0 0 22 16" width="19" height="14" role="img" aria-label="<?php esc_attr_e( 'דגל ישראל', 'beckwealth' ); ?>"><rect width="22" height="16" fill="#fff"></rect><rect y="1.7" width="22" height="2.2" fill="#0038B8"></rect><rect y="12.1" width="22" height="2.2" fill="#0038B8"></rect><path d="M11 5.4l2.5 4.3h-5z" fill="none" stroke="#0038B8" stroke-width="0.9"></path><path d="M11 10.6l-2.5-4.3h5z" fill="none" stroke="#0038B8" stroke-width="0.9"></path></svg>
								<?php endif; ?>
							</div>
						</div>
						<ul class="ip-model__points<?php echo 'dot' === $bullet ? ' ip-model__points--dot' : ''; ?>">
							<?php foreach ( beckwealth_mod_pairs( $prefix . '_points' ) as [ $bold, $rest ] ) : ?>
								<li><span class="ip-model__bullet" aria-hidden="true"></span><span><strong><?php echo esc_html( $bold ); ?></strong> <?php echo esc_html( $rest ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * מקטע "סיפור מקרה".
 *
 * @param string $key  מפתח השדה (כותרת|טקסט).
 * @param string $mark סימן הקיקר.
 */
function beckwealth_ip_case( string $key, string $mark = 'diamond' ): void {
	$items = beckwealth_mod_pairs( $key );
	if ( ! $items ) {
		return;
	}
	?>
	<section class="ip-sec ip-case" aria-labelledby="case-title">
		<div class="ip-case__grid" data-reveal>
			<div class="ip-head">
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'ip_case_kicker' ), $mark ); ?>
				<h2 id="case-title" class="ip-h2 ip-h2--34"><?php echo esc_html( beckwealth_mod( 'ip_case_title' ) ); ?></h2>
				<?php if ( beckwealth_mod( 'ip_case_note' ) ) : ?>
					<p class="ip-case__note"><?php echo esc_html( beckwealth_mod( 'ip_case_note' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="ip-case__cards">
				<?php foreach ( $items as [ $k, $t ] ) : ?>
					<div class="ip-case__card">
						<p class="ip-case__k"><?php echo esc_html( $k ); ?></p>
						<p class="ip-case__t"><?php echo esc_html( $t ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * מקטע "מחלקות נוספות" (שתי המחלקות האחרות).
 *
 * @param string $mark סימן הקיקר.
 */
function beckwealth_ip_others( string $mark = 'diamond' ): void {
	$others = array_filter( beckwealth_service_links(), static fn( $s ) => $s['id'] !== get_the_ID() );
	if ( ! $others ) {
		return;
	}
	?>
	<section class="ip-others" aria-labelledby="others-title">
		<div class="ip-others__wrap">
			<div class="ip-others__head" data-reveal>
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'ip_others_kicker' ), $mark ); ?>
				<h2 id="others-title" class="screen-reader-text"><?php echo esc_html( beckwealth_mod( 'ip_others_kicker' ) ); ?></h2>
			</div>
			<div class="ip-others__grid">
				<?php foreach ( $others as $s ) : ?>
					<a class="ip-others__card" href="<?php echo esc_url( $s['url'] ); ?>" data-reveal>
						<span class="ip-others__meta">
							<span class="ip-others__num" dir="ltr"><?php echo esc_html( $s['num'] ); ?></span>
							<span class="ip-others__title"><?php echo esc_html( $s['title'] ); ?></span>
							<?php if ( $s['tagline'] ) : ?>
								<span class="ip-others__desc"><?php echo esc_html( $s['tagline'] ); ?></span>
							<?php endif; ?>
						</span>
						<span class="ip-arrow" aria-hidden="true">←</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * פס כהה של קריאה לפעולה (מי אנחנו / היתרון השוויצרי).
 *
 * @param bool   $with_deps  עם רשת שלוש המחלקות.
 * @param string $link_label קישור משני – טקסט.
 * @param string $link_url   קישור משני – כתובת.
 */
function beckwealth_ip_cta( bool $with_deps = false, string $link_label = '', string $link_url = '' ): void {
	?>
	<section id="cta" class="ip-cta" aria-labelledby="cta-title">
		<div class="ip-cta__wrap">
			<div class="ip-cta__row">
				<div class="ip-cta__text" data-reveal>
					<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'ip_cta_kicker' ), 'diamond', true ); ?>
					<h2 id="cta-title" class="ip-cta__title"><?php echo esc_html( beckwealth_mod( 'ip_cta_title' ) ); ?></h2>
				</div>
				<div class="ip-cta__actions" data-reveal>
					<?php if ( $link_label ) : ?>
						<a class="bw-link bw-link--paper" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_label ); ?></a>
					<?php endif; ?>
					<?php beckwealth_ip_paper_btn( (string) beckwealth_mod( 'ip_cta_btn' ), beckwealth_contact_url() ); ?>
				</div>
			</div>
			<?php if ( $with_deps && beckwealth_service_links() ) : ?>
				<div class="ip-cta__deps" data-reveal>
					<?php foreach ( beckwealth_service_links() as $s ) : ?>
						<a class="ip-cta__dep" href="<?php echo esc_url( $s['url'] ); ?>">
							<span class="ip-cta__dep-meta"><span class="ip-cta__dep-num" dir="ltr"><?php echo esc_html( $s['num'] ); ?></span><span class="ip-cta__dep-title"><?php echo esc_html( $s['title'] ); ?></span></span>
							<span class="ip-arrow" aria-hidden="true">←</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * פס טופס מקוצר (עמודי המחלקות). 'center' – טקסט וטופס; 'split' – תמונה וטופס.
 *
 * @param string $layout 'center' | 'split'.
 * @param string $mark   סימן הקיקר.
 */
function beckwealth_ip_form_band( string $layout = 'center', string $mark = 'diamond' ): void {
	$subject = get_the_title();
	?>
	<section id="form" class="ip-form ip-form--<?php echo esc_attr( $layout ); ?>" aria-labelledby="form-title">
		<?php if ( 'split' === $layout ) : ?>
			<div class="ip-form__media" data-gs="1">
				<?php beckwealth_slot_image( beckwealth_mod( 'ip_form_image' ) ?: beckwealth_default_image( 'hero-telaviv' ), '', 'large', array( 'class' => 'ip-form__img' ), (string) beckwealth_mod( 'ip_form_image_cap' ) ); ?>
				<span class="ip-form__shade" aria-hidden="true"></span>
				<span class="bw-frame ip-form__frame" aria-hidden="true"></span>
				<?php if ( beckwealth_mod( 'ip_form_image_cap' ) ) : ?>
					<p class="ip-form__cap"><span class="ip-dash ip-dash--14" aria-hidden="true"></span><?php echo esc_html( beckwealth_mod( 'ip_form_image_cap' ) ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="ip-form__panel">
			<div class="ip-form__text" data-reveal>
				<?php beckwealth_ip_kicker( (string) beckwealth_mod( 'ip_cta_kicker' ), $mark, true ); ?>
				<h2 id="form-title" class="ip-form__title"><?php echo esc_html( beckwealth_mod( 'ip_cta_title' ) ); ?></h2>
			</div>
			<div class="ip-form__form" data-reveal>
				<?php beckwealth_contact_form_compact( $subject ); ?>
			</div>
		</div>
	</section>
	<?php
}
