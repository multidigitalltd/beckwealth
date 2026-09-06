<?php
/**
 * טעינת קבצי CSS ו-JS – מותנית לפי עמוד, גרסאות Minified בייצור.
 *
 * מבנה הנכסים:
 *  - main.css / main.js           – גלובלי (בסיס, כותרת, פוטר, פרטיות, נגישות).
 *  - home.css / home.js           – דף הבית בלבד.
 *  - content.css                  – עמודי תוכן/ארכיון/בלוג (לא בדף הבית).
 *  - contact-form.js              – רק כשטופס יצירת קשר מוצג (נטען מתוך הטופס).
 *  - a11y.js                      – סרגל נגישות, נטען עצל בלחיצה ראשונה.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * נתיב יחסי לנכס: גרסת .min בייצור, מקור כש-SCRIPT_DEBUG פעיל.
 *
 * @param string $path נתיב יחסי, למשל assets/css/main.css.
 * @return string
 */
function beckwealth_asset_path( string $path ): string {
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		return $path;
	}
	$min = preg_replace( '/\.(css|js)$/', '.min.$1', $path );
	return file_exists( BECKWEALTH_DIR . '/' . $min ) ? $min : $path;
}

/**
 * כתובת מלאה לנכס.
 *
 * @param string $path נתיב יחסי.
 * @return string
 */
function beckwealth_asset_url( string $path ): string {
	return BECKWEALTH_URI . '/' . beckwealth_asset_path( $path );
}

/**
 * גרסה לנכס: זמן שינוי בפיתוח, גרסת התבנית בייצור (ידידותי ל-CDN/Cache).
 *
 * @param string $path נתיב יחסי.
 * @return string
 */
function beckwealth_asset_version( string $path ): string {
	$file = BECKWEALTH_DIR . '/' . beckwealth_asset_path( $path );
	if ( 'production' !== wp_get_environment_type() && file_exists( $file ) ) {
		return (string) filemtime( $file );
	}
	return BECKWEALTH_VERSION;
}

/**
 * האם להציג טופס יצירת קשר בעמוד הנוכחי (לטעינת נכסים מותנית).
 *
 * @return bool
 */
function beckwealth_page_has_contact_form(): bool {
	if ( is_front_page() ) {
		return true;
	}
	if ( is_singular( 'service' ) ) {
		return true;
	}
	if ( is_page_template( 'page-templates/contact.php' ) ) {
		return true;
	}
	return false;
}

/**
 * טעינת נכסים בצד הלקוח.
 */
function beckwealth_enqueue_assets(): void {
	// --- CSS גלובלי ---
	wp_enqueue_style( 'beckwealth-main', beckwealth_asset_url( 'assets/css/main.css' ), array(), beckwealth_asset_version( 'assets/css/main.css' ) );

	// --- CSS לפי עמוד ---
	if ( is_front_page() ) {
		wp_enqueue_style( 'beckwealth-home', beckwealth_asset_url( 'assets/css/home.css' ), array( 'beckwealth-main' ), beckwealth_asset_version( 'assets/css/home.css' ) );
	} else {
		wp_enqueue_style( 'beckwealth-content', beckwealth_asset_url( 'assets/css/content.css' ), array( 'beckwealth-main' ), beckwealth_asset_version( 'assets/css/content.css' ) );
	}

	// --- JS גלובלי (defer, ללא תלויות) ---
	wp_enqueue_script(
		'beckwealth-main',
		beckwealth_asset_url( 'assets/js/main.js' ),
		array(),
		beckwealth_asset_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'beckwealth-main',
		'beckwealthData',
		array(
			'i18n'       => array(
				'openMenu'  => __( 'פתח תפריט', 'beckwealth' ),
				'closeMenu' => __( 'סגור תפריט', 'beckwealth' ),
			),
			'a11yScript' => beckwealth_get_setting( 'a11y_toolbar', '1' ) ? add_query_arg( 'ver', beckwealth_asset_version( 'assets/js/a11y.js' ), beckwealth_asset_url( 'assets/js/a11y.js' ) ) : '',
		)
	);

	// --- JS דף הבית (reveal, מונים, אקורדיון) ---
	if ( is_front_page() ) {
		wp_enqueue_script(
			'beckwealth-home',
			beckwealth_asset_url( 'assets/js/home.js' ),
			array(),
			beckwealth_asset_version( 'assets/js/home.js' ),
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}

	// --- JS טופס יצירת קשר – רק בעמודים עם טופס ---
	if ( beckwealth_page_has_contact_form() ) {
		beckwealth_enqueue_contact_form_assets();
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'beckwealth_enqueue_assets' );

/**
 * נכסי טופס יצירת קשר. ניתן לקריאה גם מתוך שורטקוד/תבנית (לפני wp_footer).
 */
function beckwealth_enqueue_contact_form_assets(): void {
	if ( wp_script_is( 'beckwealth-contact-form', 'enqueued' ) ) {
		return;
	}
	wp_enqueue_script(
		'beckwealth-contact-form',
		beckwealth_asset_url( 'assets/js/contact-form.js' ),
		array(),
		beckwealth_asset_version( 'assets/js/contact-form.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
	wp_localize_script(
		'beckwealth-contact-form',
		'beckwealthForm',
		array(
			'i18n' => array(
				'required' => __( 'שדה חובה.', 'beckwealth' ),
				'email'    => __( 'יש להזין כתובת אימייל תקינה.', 'beckwealth' ),
				'phone'    => __( 'יש להזין מספר טלפון תקין.', 'beckwealth' ),
				'invalid'  => __( 'יש לבדוק את השדה.', 'beckwealth' ),
				'fix'      => __( 'יש לתקן את השדות המסומנים.', 'beckwealth' ),
				'sending'  => __( 'שולח…', 'beckwealth' ),
			),
		)
	);
}

/**
 * preload לגופנים הקריטיים (LCP): Almoni Regular + Index Medium.
 */
function beckwealth_preload_fonts(): void {
	foreach ( array( 'almoni-regular', 'index-medium' ) as $font ) {
		echo '<link rel="preload" href="' . esc_url( BECKWEALTH_URI . '/assets/fonts/' . $font . '.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'beckwealth_preload_fonts', 2 );

/**
 * גופני העיצוב בעורך הבלוקים כדי שהתצוגה תתאים לאתר.
 */
function beckwealth_editor_assets(): void {
	$base = BECKWEALTH_URI . '/assets/fonts/';
	$css  = '@font-face{font-family:"Almoni";font-weight:400;font-display:swap;src:url("' . esc_url( $base . 'almoni-regular.woff2' ) . '") format("woff2")}'
		. '@font-face{font-family:"Almoni";font-weight:500;font-display:swap;src:url("' . esc_url( $base . 'almoni-medium.woff2' ) . '") format("woff2")}'
		. '@font-face{font-family:"Almoni";font-weight:300;font-display:swap;src:url("' . esc_url( $base . 'almoni-light.woff2' ) . '") format("woff2")}'
		. '@font-face{font-family:"Almoni";font-weight:600;font-display:swap;src:url("' . esc_url( $base . 'almoni-demibold.woff2' ) . '") format("woff2")}'
		. '@font-face{font-family:"Index";font-weight:400;font-display:swap;src:url("' . esc_url( $base . 'index-regular.woff2' ) . '") format("woff2")}'
		. '@font-face{font-family:"Index";font-weight:500;font-display:swap;src:url("' . esc_url( $base . 'index-medium.woff2' ) . '") format("woff2")}';
	wp_register_style( 'beckwealth-editor-fonts', false, array(), BECKWEALTH_VERSION ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'beckwealth-editor-fonts' );
	wp_add_inline_style( 'beckwealth-editor-fonts', $css );
}
add_action( 'enqueue_block_editor_assets', 'beckwealth_editor_assets' );
