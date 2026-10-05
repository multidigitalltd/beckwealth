<?php
/**
 * תוכן התחלתי בהפעלת התבנית – התוכן המאושר מהעיצוב. רץ פעם אחת בלבד לכל סוג
 * (רק אם אין עדיין פריטים מאותו סוג), כך שלא דורס תוכן של הלקוח.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * האם קיימים פריטים מסוג מסוים (כולל טיוטות).
 *
 * @param string $type סוג תוכן.
 * @return bool
 */
function beckwealth_type_has_items( string $type ): bool {
	$found = get_posts(
		array(
			'post_type'              => $type,
			'post_status'            => 'any',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	return ! empty( $found );
}

/**
 * יצירת פריט תוכן עם מטא.
 *
 * @param string $type    סוג.
 * @param string $title   כותרת.
 * @param string $content תוכן.
 * @param int    $order   סדר.
 * @param array  $meta    מטא.
 * @return int
 */
function beckwealth_seed_item( string $type, string $title, string $content = '', int $order = 0, array $meta = array() ): int {
	$id = wp_insert_post(
		array(
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
			'menu_order'   => $order,
			'meta_input'   => $meta,
		),
		false
	);
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * שלוש המחלקות המאושרות: [כותרת, תיאור קצר, נקודות (שורה לכל נקודה), slug].
 *
 * @return array<int, array{0:string,1:string,2:string,3:string}>
 */
function beckwealth_seed_services(): array {
	return array(
		array( 'ניהול הון משפחתי', 'ניהול כלל הנכסים הפיננסיים, באמצעות בנקאות שוויצרית.', "יצירת אסטרטגיית השקעה מותאמת\nניהול השקעות בשוק ההון\nניהול סיכונים ופיזור מותאם", 'assets' ),
		array( 'ניהול השקעות ותכנון מס', 'תכנון מבנה הנכסים הפיננסיים בישראל ובעולם, בכדי להגיע לחבות מס מינימלית.', "תכנון מס מקומי ובינלאומי\nמבנה נכסים ואחזקה\nתכנון ארוך טווח", 'tax' ),
		array( 'נאמנות', 'הכלי הנפוץ בעולם האנגלוסקסי להעברת הון בין-דורית.', "ניהול נכסים\nקביעת יעדים ומנגנון ניהול\nהורשה מתוחכמת", 'trust' ),
	);
}

/**
 * גרסאות קודמות של תוכן המחלקות (מה שה-seed יצר בעבר), לפי slug.
 * משמש לעדכון חד-פעמי באתרים שכבר הופעלו – מתעדכן רק פריט שלא נערך ידנית.
 *
 * @return array<string, array<int, array{0:string,1:string,2:string}>>
 */
function beckwealth_legacy_seed_services(): array {
	return array(
		'assets' => array( array( 'ניהול נכסים', 'ניהול השקעות ומעקב אחר כלל הנכסים, בבנק שוויצרי, על שמכם.', "בקרה, ניטור ואיחוד נכסים\nייעוץ פיננסי\nניהול נכסים מסורתי" ) ),
		'tax'    => array( array( 'ייעוץ מס ופיננסי', 'תכנון המס, הפנסיה ומבנה הנכסים, בישראל ובין מדינות.', "תכנון מס\nתכנון פנסיה\nמבנה נכסים" ) ),
		'trust'  => array( array( 'נאמנות', 'מבנים משפטיים ששומרים על ההון ועל הכוונות שלכם, לדורות הבאים.', "ניהול קרנות ונאמנויות\nתכנון ירושה ונכסים\nמבנה נכסים" ) ),
	);
}

/**
 * עדכון תוכן המחלקות לגרסה המאושרת האחרונה באתר שכבר הופעל.
 * פריט מתעדכן רק אם הכותרת, התיאור והנקודות שלו זהים לגרסת seed קודמת
 * (כלומר הלקוח לא ערך אותו). רץ פעם אחת לכל גרסת תוכן.
 */
function beckwealth_upgrade_seeded_services(): void {
	$rev = 5;
	$cur = (int) get_option( 'beckwealth_content_rev', 0 );
	if ( $cur >= $rev ) {
		return;
	}
	if ( $cur < 4 ) {
		beckwealth_upgrade_office_email(); // גרסה 4: אימייל המשרד office@beckwealth.co.il והכתובת בבני ברק.
	}
	if ( $cur < 5 ) {
		beckwealth_upgrade_publications(); // גרסה 5: "פרסומים ומאמרים" – כתבות חיצוניות, בלי אנגלית.
	}
	if ( $cur >= 1 && $cur < 3 ) {
		beckwealth_seed_design_pages(); // גרסה 3: עמודי "מי אנחנו" ו"היתרון השוויצרי".
	}
	if ( get_option( 'beckwealth_seeded' ) && $cur < 2 ) {
		$legacy = beckwealth_legacy_seed_services();
		foreach ( beckwealth_seed_services() as [ $title, $tagline, $bullets, $slug ] ) {
			$post = get_page_by_path( $slug, OBJECT, 'service' );
			if ( ! $post || empty( $legacy[ $slug ] ) ) {
				continue;
			}
			$cur = array( $post->post_title, (string) get_post_meta( $post->ID, '_bw_tagline', true ), (string) get_post_meta( $post->ID, '_bw_bullets', true ) );
			if ( ! in_array( $cur, $legacy[ $slug ], true ) ) {
				continue; // נערך ידנית – לא נוגעים.
			}
			wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_title'   => $title,
					'post_content' => '<!-- wp:paragraph --><p>' . esc_html( $tagline ) . '</p><!-- /wp:paragraph -->',
				)
			);
			update_post_meta( $post->ID, '_bw_tagline', $tagline );
			update_post_meta( $post->ID, '_bw_bullets', $bullets );
		}
	}
	update_option( 'beckwealth_content_rev', $rev, false );
}
add_action( 'after_switch_theme', 'beckwealth_upgrade_seeded_services', 30 );
add_action( 'admin_init', 'beckwealth_upgrade_seeded_services' );

/**
 * אימייל המשרד (office@beckwealth.co.il) והכתובת בבני ברק מחליפים את הערכים הישנים/הזמניים בכל מקום שנשמר באתר:
 * הפוטר ועמוד יצירת הקשר (אם עדיין מכילים את הישנים), "אימייל ליצירת קשר" (אם לא הוגדר, או שהוא אימייל המנהל)
 * ו"כתובת" (אם ריקה).
 */
function beckwealth_upgrade_office_email(): void {
	$old_addr = 'מגדל אלון 2, תל אביב';
	foreach ( array( 'bw_footer_il_text', 'bw_cp_tlv_addr' ) as $mod ) {
		$value = get_theme_mod( $mod );
		if ( is_string( $value ) && ( str_contains( $value, 'israel@beckwealth.ch' ) || str_contains( $value, $old_addr ) ) ) {
			set_theme_mod( $mod, str_replace( array( 'israel@beckwealth.ch', $old_addr ), array( BECKWEALTH_OFFICE_EMAIL, BECKWEALTH_OFFICE_ADDRESS ), $value ) );
		}
	}
	if ( 'https://www.openstreetmap.org/?mlat=32.0705&mlon=34.7935#map=16/32.0705/34.7935' === (string) get_theme_mod( 'bw_cp_tlv_map', '' ) ) {
		remove_theme_mod( 'bw_cp_tlv_map' ); // חוזר לברירת המחדל החדשה (מגדל ב.ס.ר 4).
	}
	if ( '' === (string) get_theme_mod( 'beckwealth_address', '' ) ) {
		set_theme_mod( 'beckwealth_address', BECKWEALTH_OFFICE_ADDRESS );
	}
	$email = (string) get_theme_mod( 'beckwealth_email', '' );
	if ( '' === $email || $email === (string) get_option( 'admin_email' ) || 'israel@beckwealth.ch' === $email ) {
		set_theme_mod( 'beckwealth_email', BECKWEALTH_OFFICE_EMAIL );
	}
}

/**
 * הכתבות החיצוניות של הלקוח (מוצגות ככרטיסים ב"פרסומים ומאמרים"; הלחיצה מובילה לאתר המפרסם).
 *
 * @return array<int, array{0:string,1:string,2:string,3:string}> [כותרת, כתובת, תקציר, תאריך].
 */
function beckwealth_external_publications(): array {
	return array(
		array( 'חדד, רוט, שנהר ושות׳ (HAR) – פרסומים בתקשורת', 'https://www.har.law/media/', 'כתבות, ראיונות והופעות בתקשורת של משרד עורכי הדין חדד, רוט, שנהר ושות׳.', '2026-03-10 09:00:00' ),
		array( 'עו״ד אילן בומבך ושות׳ – פרסומים במדיה', 'https://www.bombachlaw.com/media_publications/', 'פרסומים בתקשורת הארצית של משרד עו״ד אילן בומבך ושות׳: דיני חברות, משפט חוקתי וליטיגציה.', '2026-02-10 09:00:00' ),
		array( 'ד״ר גאי כרמי – בתקשורת', 'https://carmi.law/media/', 'מאמרים, ראיונות ותכניות טלוויזיה בהשתתפות ד״ר גאי כרמי: ליטיגציה מסחרית, מכרזים, דיני חברות וחוקה.', '2026-01-12 09:00:00' ),
	);
}

/**
 * טעינת הכתבות החיצוניות (רק אלה שעדיין לא קיימות – לפי הכתובת).
 *
 * @return int מספר הכתבות שנוספו.
 */
function beckwealth_seed_external_posts(): int {
	$term = term_exists( 'פרסומים בתקשורת', 'category' );
	if ( ! $term ) {
		$term = wp_insert_term( 'פרסומים בתקשורת', 'category', array( 'slug' => 'media' ) );
	}
	$cat_id = is_array( $term ) ? (int) $term['term_id'] : 0;
	$added  = 0;
	foreach ( beckwealth_external_publications() as [ $title, $url, $excerpt, $date ] ) {
		$exists = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_key'       => '_bw_external_url', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- חד-פעמי בהפעלה/שדרוג.
				'meta_value'     => $url, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		if ( $exists ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $title,
				'post_excerpt'  => $excerpt,
				'post_content'  => '<!-- wp:paragraph --><p>' . esc_html( $excerpt ) . '</p><!-- /wp:paragraph -->',
				'post_date'     => $date,
				'post_category' => $cat_id ? array( $cat_id ) : array(),
				'meta_input'    => array( '_bw_external_url' => $url ),
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			++$added;
		}
	}
	return $added;
}

/**
 * הקטגוריה "Uncategorized" (ברירת המחדל של וורדפרס) הופכת ל"כללי" – כדי שלא תוצג אנגלית בכרטיסים.
 */
function beckwealth_hebrew_default_category(): void {
	$cat = get_term( (int) get_option( 'default_category' ), 'category' );
	if ( $cat instanceof WP_Term && in_array( $cat->name, array( 'Uncategorized', 'כללי' ), true ) && 'כללי' !== $cat->name ) {
		wp_update_term( $cat->term_id, 'category', array( 'name' => 'כללי', 'slug' => 'general' ) );
	}
}

/**
 * גרסה 5 באתרים קיימים: שם העמוד "פרסומים ומאמרים" (אם עדיין "בלוג"), קטגוריית ברירת מחדל בעברית,
 * מאמרי הדוגמה שלא נערכו עוברים לטיוטה, והכתבות החיצוניות של הלקוח נטענות.
 */
function beckwealth_upgrade_publications(): void {
	$blog_id = (int) get_option( 'page_for_posts' );
	if ( $blog_id && 'בלוג' === get_the_title( $blog_id ) ) {
		wp_update_post( array( 'ID' => $blog_id, 'post_title' => __( 'פרסומים ומאמרים', 'beckwealth' ) ) );
	}
	beckwealth_hebrew_default_category();
	$samples = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 50, 'no_found_rows' => true ) );
	foreach ( $samples as $sample ) {
		if ( str_contains( (string) $sample->post_content, 'תוכן המאמר יתווסף על-ידי הלקוח' ) ) {
			wp_update_post( array( 'ID' => $sample->ID, 'post_status' => 'draft' ) ); // מאמר לדוגמה שלא נערך – לטיוטה.
		}
	}
	beckwealth_seed_external_posts();
}

/**
 * עמודי העיצוב הפנימיים: "מי אנחנו" ו"היתרון השוויצרי" עם התבניות הייעודיות.
 * נוצרים רק אם אין עמוד עם אותה תבנית ואין עמוד באותו slug.
 */
function beckwealth_seed_design_pages(): void {
	$pages = array(
		array( 'about', __( 'מי אנחנו', 'beckwealth' ), 'page-templates/about.php', 1 ),
		array( 'swiss-advantage', __( 'היתרון השוויצרי', 'beckwealth' ), 'page-templates/swiss-advantage.php', 2 ),
	);
	foreach ( $pages as [ $slug, $title, $template, $order ] ) {
		if ( beckwealth_template_page_url( $template ) || get_page_by_path( $slug ) ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
				'menu_order'  => $order,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_wp_page_template', $template );
		}
	}
}

/**
 * הרצת ה-seed.
 */
function beckwealth_seed_content(): void {
	if ( get_option( 'beckwealth_seeded' ) ) {
		return;
	}

	// מחלקות (שירותים).
	if ( ! beckwealth_type_has_items( 'service' ) ) {
		foreach ( beckwealth_seed_services() as $i => [ $title, $tagline, $bullets, $slug ] ) {
			$id = beckwealth_seed_item( 'service', $title, '<!-- wp:paragraph --><p>' . esc_html( $tagline ) . '</p><!-- /wp:paragraph -->', $i + 1, array( '_bw_tagline' => $tagline, '_bw_bullets' => $bullets ) );
			if ( $id ) {
				wp_update_post( array( 'ID' => $id, 'post_name' => $slug ) );
			}
		}
	}

	// צוות.
	if ( ! beckwealth_type_has_items( 'team' ) ) {
		$team = array(
			array( 'רונית שקד', 'מנהלת הפעילות · ישראל' ),
			array( 'אורי אלמוג', 'יועץ בכיר · ישראל' ),
			array( 'Thomas Beck', 'שותף מנהל · ציריך' ),
			array( 'Anna Keller', 'מנהלת השקעות · ציריך' ),
		);
		foreach ( $team as $i => [ $name, $role ] ) {
			beckwealth_seed_item( 'team', $name, '', $i + 1, array( '_bw_role' => $role ) );
		}
	}

	// המלצות.
	if ( ! beckwealth_type_has_items( 'testimonial' ) ) {
		$testimonials = array(
			array( 'משפחה · דור שלישי', '"אחרי שנים שהכסף היה מפוזר בין שלושה בנקים ושני יועצים, יש סוף־סוף תמונה אחת. הילדים יודעים בדיוק מה יש ואיפה."' ),
			array( 'יזם · אחרי אקזיט', '"אחרי האקזיט קיבלתי טלפון מכל בנק בארץ. בחרתי דווקא בשקט השוויצרי. הפגישות בעברית, עשר דקות מהבית."' ),
			array( 'בעלי עסק משפחתי · שני דורות', '"החשש היה שהכול יתנהל רחוק ובאנגלית. בפועל יש איש קשר אחד, בעברית, שמכיר את המשפחה כבר שלוש שנים."' ),
		);
		foreach ( $testimonials as $i => [ $who, $text ] ) {
			beckwealth_seed_item( 'testimonial', $who, '<!-- wp:paragraph --><p>' . esc_html( $text ) . '</p><!-- /wp:paragraph -->', $i + 1 );
		}
	}

	// שאלות ותשובות.
	if ( ! beckwealth_type_has_items( 'faq' ) ) {
		$faqs = array(
			array( 'למה לנהל הון דווקא בשוויץ ולא בישראל?', 'שוויץ היא מרכז ניהול ההון הפרטי הגדול בעולם כבר מעל מאה שנה: יציבות פוליטית, מטבע חזק ובנקים עם עודפי הון גבוהים. עבור ישראלים זה קודם כול פיזור, שכבת ביטחון להון שאינה תלויה במערכת אחת.' ),
			array( 'האם זה חוקי ומדווח?', 'לחלוטין. החשבונות נפתחים על שם הלקוח, מדווחים לרשות המסים בישראל במסגרת הסכמי חילופי המידע (CRS), וכל המבנה בנוי בשקיפות מלאה מול הרגולציה בשתי המדינות.' ),
			array( 'מי מנהל את הכסף בפועל?', 'צוות ההשקעות של BeckWealth בציריך, לפי מדיניות שנקבעת יחד אתכם. הכסף מוחזק בבנק שוויצרי על שמכם. אנחנו מנהלים, לא מחזיקים.' ),
			array( 'מה סף הכניסה?', 'הליווי שלנו מתאים לרוב מהיקף נכסים פנוי של כמיליון דולר ומעלה. בשיחת ההיכרות נבין יחד אם יש התאמה.' ),
			array( 'איך מגיעים לכסף כשצריך אותו?', 'הנזילות נשארת שלכם. הוראות משיכה מבוצעות בתוך ימים ספורים, ואפשר להגדיר מראש רמות נזילות שונות לחלקים שונים של התיק.' ),
			array( 'מה העלויות?', 'דמי ניהול שקופים הנגזרים מהיקף הנכסים, ללא עמלות נסתרות. את המבנה המלא תקבלו בכתב, לפני כל התחייבות.' ),
			array( 'מה קורה בשיחה הראשונה?', '30 דקות, בזום או אצלנו במשרד. אתם מספרים על הצרכים, אנחנו מסבירים את המודל, ובסופה תדעו אם זה רלוונטי עבורכם.' ),
		);
		foreach ( $faqs as $i => [ $q, $a ] ) {
			beckwealth_seed_item( 'faq', $q, '<!-- wp:paragraph --><p>' . esc_html( $a ) . '</p><!-- /wp:paragraph -->', $i + 1 );
		}
	}

	// "פרסומים ומאמרים": "שלום עולם" נמחק, הקטגוריה "Uncategorized" הופכת ל"כללי", והכתבות החיצוניות של הלקוח נטענות.
	$posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'fields' => 'ids', 'no_found_rows' => true ) );
	if ( 1 === count( $posts ) && 'hello-world' === get_post_field( 'post_name', $posts[0] ) ) {
		wp_delete_post( $posts[0], true );
	}
	beckwealth_hebrew_default_category();
	beckwealth_seed_external_posts();

	// עמודים: דף הבית, בלוג, יצירת קשר + הגדרת עמוד הבית.
	$front = get_page_by_path( 'home' );
	if ( ! $front ) {
		$front_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => __( 'דף הבית', 'beckwealth' ), 'post_name' => 'home' ) );
	} else {
		$front_id = $front->ID;
	}
	$blog = get_page_by_path( 'blog' );
	if ( ! $blog ) {
		$blog_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => __( 'פרסומים ומאמרים', 'beckwealth' ), 'post_name' => 'blog' ) );
	} else {
		$blog_id = $blog->ID;
	}
	beckwealth_seed_design_pages();
	if ( ! get_page_by_path( 'contact' ) ) {
		$contact_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => __( 'יצירת קשר', 'beckwealth' ), 'post_name' => 'contact' ) );
		if ( $contact_id && ! is_wp_error( $contact_id ) ) {
			update_post_meta( $contact_id, '_wp_page_template', 'page-templates/contact.php' );
			set_theme_mod( 'beckwealth_contact_page', (int) $contact_id );
		}
	}
	if ( $front_id && ! is_wp_error( $front_id ) && 'page' !== get_option( 'show_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $front_id );
		if ( $blog_id && ! is_wp_error( $blog_id ) ) {
			update_option( 'page_for_posts', (int) $blog_id );
		}
	}

	update_option( 'beckwealth_seeded', BECKWEALTH_VERSION, false );
	update_option( 'beckwealth_content_rev', 5, false );
}
add_action( 'after_switch_theme', 'beckwealth_seed_content', 20 );
