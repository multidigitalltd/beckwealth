<?php
/**
 * פונקציות עזר לשימוש בתבניות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * תאריך פרסום ומחבר לפוסט.
 */
function beckwealth_posted_on(): void {
	$time = sprintf(
		'<time class="entry-date published" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time .= sprintf(
			'<time class="updated screen-reader-text" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);
	}

	echo '<span class="posted-on">' . $time . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.

	$categories = get_the_category_list( ', ' );
	if ( $categories ) {
		echo '<span class="cat-links">' . wp_kses_post( $categories ) . '</span>';
	}
}

/**
 * זמן קריאה משוער בדקות.
 *
 * @param int|null $post_id מזהה פוסט.
 * @return int
 */
function beckwealth_reading_time( ?int $post_id = null ): int {
	$content = get_post_field( 'post_content', $post_id ?? get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	// לעברית str_word_count אינו מדויק, לכן נספור גם לפי רווחים.
	$words = max( $words, count( preg_split( '/\s+/u', wp_strip_all_tags( $content ), -1, PREG_SPLIT_NO_EMPTY ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * מדפיס תמונה ראשית עם lazy loading ו-alt.
 *
 * @param string $size גודל התמונה.
 * @param array  $attr תכונות נוספות.
 */
function beckwealth_post_thumbnail( string $size = 'beckwealth-card', array $attr = array() ): void {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy' ) );

	if ( is_singular() ) {
		echo '<figure class="post-thumbnail">';
		the_post_thumbnail( $size, $attr );
		echo '</figure>';
		return;
	}

	printf(
		'<a class="post-thumbnail" href="%1$s" aria-hidden="true" tabindex="-1">',
		esc_url( get_permalink() )
	);
	the_post_thumbnail( $size, $attr );
	echo '</a>';
}

/**
 * פרטי קשר מהקסטומייזר.
 *
 * @return array{phone:string,whatsapp:string,email:string,address:string,hours:string}
 */
function beckwealth_contact_details(): array {
	return array(
		'phone'    => (string) get_theme_mod( 'beckwealth_phone', '' ),
		'whatsapp' => (string) get_theme_mod( 'beckwealth_whatsapp', '' ),
		'email'    => (string) get_theme_mod( 'beckwealth_email', get_option( 'admin_email' ) ),
		'address'  => (string) get_theme_mod( 'beckwealth_address', '' ),
		'hours'    => (string) get_theme_mod( 'beckwealth_hours', '' ),
	);
}

/**
 * ניקוי מספר טלפון ל-href של tel:.
 *
 * @param string $phone מספר.
 * @return string
 */
function beckwealth_tel_href( string $phone ): string {
	$digits = preg_replace( '/[^0-9+]/', '', $phone );
	return 'tel:' . $digits;
}

/**
 * כתובת וואטסאפ מלאה.
 *
 * @param string $number  מספר (ישראלי או בינלאומי).
 * @param string $message הודעה מוכנה מראש.
 * @return string
 */
function beckwealth_whatsapp_href( string $number, string $message = '' ): string {
	$digits = preg_replace( '/[^0-9]/', '', $number );
	if ( str_starts_with( $digits, '0' ) ) {
		$digits = '972' . substr( $digits, 1 );
	}
	$url = 'https://wa.me/' . $digits;
	return $message ? add_query_arg( 'text', rawurlencode( $message ), $url ) : $url;
}

/**
 * רשימת רשתות חברתיות מוגדרות.
 *
 * @return array<string, array{label:string,url:string}>
 */
function beckwealth_social_links(): array {
	$networks = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'x'         => 'X',
		'tiktok'    => 'TikTok',
	);
	$links    = array();
	foreach ( $networks as $key => $label ) {
		$url = get_theme_mod( 'beckwealth_social_' . $key, '' );
		if ( $url ) {
			$links[ $key ] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}
	return $links;
}

/**
 * אייקון SVG מובנה (ללא ספריות חיצוניות).
 *
 * @param string $name שם האייקון.
 * @return string
 */
function beckwealth_icon( string $name ): string {
	$icons = array(
		'phone'     => '<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1L6.6 10.8z"/>',
		'mail'      => '<path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm1 3.2V18h16V8.2l-8 5-8-5zM4.5 7l7.5 4.7L19.5 7h-15z"/>',
		'pin'       => '<path d="M12 2a7 7 0 0 1 7 7c0 5-7 13-7 13S5 14 5 9a7 7 0 0 1 7-7zm0 9.5A2.5 2.5 0 1 0 12 6.5a2.5 2.5 0 0 0 0 5z"/>',
		'clock'     => '<path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm0 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm1 4v4.6l3.2 1.9-1 1.7L11 13V8h2z"/>',
		'whatsapp'  => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>',
		'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v2H7v4h3v8h4v-8h3l1-4h-4V8z"/>',
		'instagram' => '<path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM17.5 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>',
		'linkedin'  => '<path d="M4 3a2 2 0 1 1 0 4 2 2 0 0 1 0-4zM2 9h4v12H2V9zm7 0h4v1.7c.6-1 1.9-2 3.8-2 4 0 4.7 2.6 4.7 6V21h-4v-5.5c0-1.5 0-3.3-2-3.3s-2.3 1.5-2.3 3.2V21H9V9z"/>',
		'youtube'   => '<path d="M22 8.2a2.7 2.7 0 0 0-1.9-1.9C18.4 6 12 6 12 6s-6.4 0-8.1.3A2.7 2.7 0 0 0 2 8.2 28 28 0 0 0 1.7 12 28 28 0 0 0 2 15.8a2.7 2.7 0 0 0 1.9 1.9c1.7.3 8.1.3 8.1.3s6.4 0 8.1-.3a2.7 2.7 0 0 0 1.9-1.9A28 28 0 0 0 22.3 12 28 28 0 0 0 22 8.2zM10 15V9l5.2 3L10 15z"/>',
		'x'         => '<path d="M17.5 3h3l-7 8 8.2 10h-6.4l-5-6.5L4.5 21h-3l7.5-8.5L1.2 3h6.5l4.5 6L17.5 3zm-1 16h1.7L7 4.8H5.1L16.5 19z"/>',
		'tiktok'    => '<path d="M16.5 3c.3 2.4 1.7 3.8 4 4v3.3a7.2 7.2 0 0 1-4-1.3v6.2a5.7 5.7 0 1 1-5.7-5.7c.3 0 .7 0 1 .1v3.4a2.4 2.4 0 1 0 1.4 2.2V3h3.3z"/>',
		'arrow'     => '<path d="M15 5l-1.4 1.4L18.2 11H3v2h15.2l-4.6 4.6L15 19l7-7-7-7z"/>',
		'menu'      => '<path d="M3 6h18v1.5H3V6zm0 5.25h18v1.5H3v-1.5zm0 5.25h18V18H3v-1.5z"/>',
		'close'     => '<path d="M18.3 5.7L12 12l6.3 6.3-1.4 1.4L12 13.4l-6.3 6.3-1.4-1.4L10.6 12 4.3 5.7l1.4-1.4L12 10.6l6.3-6.3z"/>',
		'a11y'      => '<path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm9 6.5c-3.4.6-6.1.9-9 .9s-5.6-.3-9-.9l-.4 2c2.3.4 4.4.7 6.4.8v3.2L6.6 21l1.9.8L12 15.6l3.5 6.2 1.9-.8-2.4-6.5v-3.2c2-.1 4.1-.4 6.4-.8l-.4-2z"/>',
		'reset'     => '<path d="M12 5V2L7 6l5 4V7a5 5 0 1 1-5 5H5a7 7 0 1 0 7-7z"/>',
		'plus'      => '<path d="M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6V5z"/>',
		'minus'     => '<path d="M5 11h14v2H5z"/>',
		'contrast'  => '<path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 18V4a8 8 0 0 1 0 16z"/>',
		'invert'    => '<path d="M12 2l6 6.5a8.5 8.5 0 1 1-12 0L12 2zm0 3L7.4 9.9a6.5 6.5 0 0 0 9.2 0L12 5zm0 14.5a6.5 6.5 0 0 0 4.6-1.9L12 11l-4.6 4.6a6.5 6.5 0 0 0 4.6 1.9z"/>',
		'grayscale' => '<path d="M4 4h16v16H4V4zm2 2v12h4V6H6zm6 0v12h4V6h-4z"/>',
		'underline' => '<path d="M7 3h2v8a3 3 0 0 0 6 0V3h2v8a5 5 0 0 1-10 0V3zM5 19h14v2H5z"/>',
		'font'      => '<path d="M9.5 4h2l6 16h-2.2l-1.7-4.7H7.3L5.6 20H3.5l6-16zm-1.5 9.5h4.6L10.3 7 8 13.5z"/>',
		'pause'     => '<path d="M7 5h4v14H7V5zm6 0h4v14h-4V5z"/>',
		'heading'   => '<path d="M5 4h2.5v6.5h9V4H19v16h-2.5v-7h-9v7H5V4z"/>',
		'link'      => '<path d="M10 13a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1.5 1.5 1.4 1.4 1.5-1.5a2 2 0 0 1 2.8 2.8l-3 3a2 2 0 0 1-2.8 0L10 13zm4-2a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1.5-1.5-1.4-1.4-1.5 1.5a2 2 0 0 1-2.8-2.8l3-3a2 2 0 0 1 2.8 0L14 11z"/>',
		'guide'     => '<path d="M3 5h18v2H3V5zm0 6h18v2H3v-2zm0 6h12v2H3v-2z"/>',
		'keyboard'  => '<path d="M3 6h18a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1zm1 2v8h16V8H4zm2 1h2v2H6V9zm3 0h2v2H9V9zm3 0h2v2h-2V9zm3 0h2v2h-2V9zM6 13h12v2H6v-2z"/>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon--' . esc_attr( $name ) . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * לוגו האתר: לוגו מותאם מהקסטומייזר, ואם אין – ה-SVG של המותג.
 *
 * @param string $class מחלקת CSS לתמונה.
 * @param string $alt   טקסט חלופי.
 */
function beckwealth_logo_img( string $class = '', string $alt = '' ): void {
	$alt = $alt ?: get_bloginfo( 'name' );
	$id  = (int) get_theme_mod( 'custom_logo' );
	if ( $id ) {
		echo wp_get_attachment_image( $id, 'full', false, array( 'class' => $class, 'alt' => $alt, 'loading' => 'eager', 'decoding' => 'async' ) );
		return;
	}
	printf( '<img src="%1$s" alt="%2$s" width="234" height="19" class="%3$s" decoding="async">', esc_url( BECKWEALTH_URI . '/assets/img/logo.svg' ), esc_attr( $alt ), esc_attr( $class ) );
}

/**
 * תמונת ברירת מחדל למשבצת עיצוב (assets/img/<slot>.webp) – עד שהלקוח מעלה תמונה משלו.
 *
 * @param string $slot שם המשבצת.
 * @return string URL או ריק.
 */
function beckwealth_default_image( string $slot ): string {
	$slot = sanitize_file_name( $slot );
	return file_exists( BECKWEALTH_DIR . '/assets/img/' . $slot . '.webp' ) ? BECKWEALTH_URI . '/assets/img/' . $slot . '.webp' : '';
}

/**
 * מדפיס יהלום זהב (מוטיב העיצוב).
 *
 * @param int  $size   גודל בפיקסלים (4-10).
 * @param bool $reveal האם לאנימציית reveal (data-diamond).
 * @param string $extra מחלקות נוספות.
 */
function beckwealth_diamond( int $size = 7, bool $reveal = false, string $extra = '' ): void {
	printf(
		'<span class="bw-dia bw-dia--%1$d%2$s"%3$s aria-hidden="true"></span>',
		(int) $size,
		$extra ? ' ' . esc_attr( $extra ) : '',
		$reveal ? ' data-diamond' : ''
	);
}

/**
 * תמונה למשבצת עיצוב: תמונת מדיה (מזהה), נתיב ברירת מחדל מהתבנית, או משבצת ריקה.
 *
 * @param int|string $source      מזהה קובץ מדיה או כתובת URL.
 * @param string     $placeholder כיתוב למשבצת ריקה.
 * @param string     $size        גודל תמונה.
 * @param array      $attr        תכונות נוספות ל-img.
 * @param string     $alt         טקסט חלופי כשמדובר ב-URL.
 */
function beckwealth_slot_image( $source, string $placeholder = '', string $size = 'large', array $attr = array(), string $alt = '' ): void {
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );

	if ( is_numeric( $source ) && (int) $source > 0 ) {
		$html = wp_get_attachment_image( (int) $source, $size, false, $attr );
		if ( $html ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup.
			return;
		}
	} elseif ( is_string( $source ) && '' !== $source ) {
		$html = '';
		foreach ( $attr as $k => $v ) {
			$html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
		printf( '<img src="%1$s" alt="%2$s"%3$s>', esc_url( $source ), esc_attr( $alt ), $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		return;
	}

	echo '<div class="bw-slot" aria-hidden="true">' . ( $placeholder ? '<span>' . esc_html( $placeholder ) . '</span>' : '' ) . '</div>';
}

/**
 * כותרת מקטע במרכז: יהלום + שורה עליונה + כותרת.
 *
 * @param string $kicker שורה עליונה.
 * @param string $title  כותרת.
 * @param string $class  מחלקות נוספות ל-h2.
 * @param bool   $center ממורכז (true) או לתחילת השורה.
 * @param int    $dia    גודל יהלום.
 */
function beckwealth_section_head( string $kicker, string $title, string $class = '', bool $center = true, int $dia = 8 ): void {
	echo '<div class="bw-sec-head' . ( $center ? '' : ' bw-sec-head--start' ) . '" data-reveal>';
	if ( $center ) {
		beckwealth_diamond( $dia, true );
		echo '<p class="bw-kicker">' . esc_html( $kicker ) . '</p>';
	} else {
		echo '<div class="bw-sec-head__k">';
		beckwealth_diamond( $dia, true );
		echo '<p class="bw-kicker bw-kicker--tight">' . esc_html( $kicker ) . '</p>';
		echo '</div>';
	}
	echo '<h2 class="bw-h2' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">' . esc_html( $title ) . '</h2>';
	echo '</div>';
}

/**
 * כפתור עיצובי (רקע דו-גווני מחליק) עם יהלום.
 *
 * @param string $label   טקסט.
 * @param string $url     כתובת.
 * @param string $variant '', 'lg', 'md', 'inv', 'legacy', 'submit', 'news'.
 * @param array  $attr    תכונות נוספות.
 */
function beckwealth_cta( string $label, string $url, string $variant = '', array $attr = array() ): void {
	if ( '' === $label ) {
		return;
	}
	$html = '';
	foreach ( $attr as $k => $v ) {
		$html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
	}
	printf(
		'<a class="bw-btn%1$s" href="%2$s"%3$s>%4$s<span class="bw-dia bw-dia--5 bw-dia--cur" aria-hidden="true"></span></a>',
		$variant ? ' bw-btn--' . esc_attr( $variant ) : '',
		esc_url( $url ),
		$html, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		esc_html( $label )
	);
}

/**
 * כותרת עם הדגשת זהב: טקסט בין ** הופך ל-<em>.
 *
 * @param string $text טקסט.
 * @return string HTML מנוקה.
 */
function beckwealth_highlight( string $text ): string {
	$escaped = esc_html( $text );
	return (string) preg_replace( '/\*\*(.+?)\*\*/u', '<em>$1</em>', $escaped );
}

/**
 * מספר עם ריפוד (01, 02...).
 *
 * @param int $n מספר.
 * @return string
 */
function beckwealth_pad( int $n ): string {
	return str_pad( (string) $n, 2, '0', STR_PAD_LEFT );
}

/**
 * פירוק ערך מספרי לאנימציית ספירה: קידומת, מספר, סיומת, ספרות עשרוניות.
 *
 * @param string $value למשל "CHF 4.2B".
 * @return array{prefix:string,number:string,suffix:string,decimals:int}|null
 */
function beckwealth_parse_stat( string $value ): ?array {
	if ( ! preg_match( '/^([^\d]*)(\d[\d,.]*)(.*)$/u', trim( $value ), $m ) ) {
		return null;
	}
	$number   = str_replace( ',', '', $m[2] );
	$decimals = str_contains( $number, '.' ) ? strlen( substr( $number, strpos( $number, '.' ) + 1 ) ) : 0;
	return array(
		'prefix'   => $m[1],
		'number'   => $number,
		'suffix'   => $m[3],
		'decimals' => $decimals,
	);
}

/**
 * שאילתה בטוחה לסוג תוכן עם מגבלת כמות.
 *
 * @param string $post_type סוג תוכן.
 * @param int    $count     כמות.
 * @param array  $args      ארגומנטים נוספים.
 * @return WP_Query
 */
function beckwealth_get_items( string $post_type, int $count = 6, array $args = array() ): WP_Query {
	$defaults = array(
		'post_type'              => $post_type,
		'posts_per_page'         => $count,
		'post_status'            => 'publish',
		'orderby'                => 'menu_order title',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'ignore_sticky_posts'    => true,
	);
	return new WP_Query( wp_parse_args( $args, $defaults ) );
}

/**
 * מדפיס עימוד נגיש.
 */
function beckwealth_pagination(): void {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => '<span class="screen-reader-text">' . esc_html__( 'הקודם', 'beckwealth' ) . '</span>' . beckwealth_icon( 'arrow' ),
			'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'הבא', 'beckwealth' ) . '</span>' . beckwealth_icon( 'arrow' ),
			'screen_reader_text' => __( 'ניווט בין עמודים', 'beckwealth' ),
		)
	);
}

/**
 * פירורי לחם פשוטים (ללא תוסף). אם Yoast/RankMath פעילים – משתמשים בהם.
 */
function beckwealth_breadcrumbs(): void {
	if ( is_front_page() ) {
		return;
	}

	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'פירורי לחם', 'beckwealth' ) . '">', '</nav>' );
		return;
	}
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		rank_math_the_breadcrumbs();
		return;
	}

	$items   = array();
	$items[] = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'דף הבית', 'beckwealth' ) . '</a>';

	if ( is_singular() ) {
		$post_type = get_post_type_object( get_post_type() );
		if ( $post_type && $post_type->has_archive && 'post' !== $post_type->name ) {
			$items[] = '<a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '">' . esc_html( $post_type->labels->name ) . '</a>';
		} elseif ( 'post' === get_post_type() ) {
			$blog_page = (int) get_option( 'page_for_posts' );
			if ( $blog_page ) {
				$items[] = '<a href="' . esc_url( get_permalink( $blog_page ) ) . '">' . esc_html( get_the_title( $blog_page ) ) . '</a>';
			}
		} elseif ( is_page() && wp_get_post_parent_id( get_the_ID() ) ) {
			foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
				$items[] = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
			}
		}
		$items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_archive() ) {
		$items[] = '<span aria-current="page">' . wp_kses_post( get_the_archive_title() ) . '</span>';
	} elseif ( is_search() ) {
		$items[] = '<span aria-current="page">' . esc_html__( 'תוצאות חיפוש', 'beckwealth' ) . '</span>';
	} elseif ( is_404() ) {
		$items[] = '<span aria-current="page">' . esc_html__( 'העמוד לא נמצא', 'beckwealth' ) . '</span>';
	} elseif ( is_home() ) {
		$items[] = '<span aria-current="page">' . esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: __( 'בלוג', 'beckwealth' ) ) . '</span>';
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'פירורי לחם', 'beckwealth' ) . '"><ol>';
	foreach ( $items as $item ) {
		echo '<li>' . $item . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
	echo '</ol></nav>';
}
