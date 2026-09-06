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
 * הרצת ה-seed.
 */
function beckwealth_seed_content(): void {
	if ( get_option( 'beckwealth_seeded' ) ) {
		return;
	}

	// מחלקות (שירותים).
	if ( ! beckwealth_type_has_items( 'service' ) ) {
		$services = array(
			array( 'ניהול נכסים', 'ניהול השקעות ומעקב אחר כלל הנכסים, בבנק שוויצרי, על שמכם.', "בקרה, ניטור ואיחוד נכסים\nייעוץ פיננסי\nניהול נכסים מסורתי", 'assets' ),
			array( 'ייעוץ מס ופיננסי', 'תכנון המס, הפנסיה ומבנה הנכסים, בישראל ובין מדינות.', "תכנון מס\nתכנון פנסיה\nמבנה נכסים", 'tax' ),
			array( 'נאמנות', 'מבנים משפטיים ששומרים על ההון ועל הכוונות שלכם, לדורות הבאים.', "ניהול קרנות ונאמנויות\nתכנון ירושה ונכסים\nמבנה נכסים", 'trust' ),
		);
		foreach ( $services as $i => [ $title, $tagline, $bullets, $slug ] ) {
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

	// מאמרים לדוגמה (רק אם הבלוג ריק לגמרי, כולל "שלום עולם").
	$posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => 2, 'fields' => 'ids', 'no_found_rows' => true ) );
	$hello = 1 === count( $posts ) && 'hello-world' === get_post_field( 'post_name', $posts[0] );
	if ( empty( $posts ) || $hello ) {
		if ( $hello ) {
			wp_delete_post( $posts[0], true );
		}
		$articles = array(
			array( 'מס יציאה לישראלים: מה חשוב לדעת לפני רילוקיישן', 'מיסוי', '2026-01-12 09:00:00' ),
			array( 'איך בנק שוויצרי שומר על הכסף שלכם בתקופות של אי־ודאות', 'בנקאות', '2025-12-08 09:00:00' ),
			array( 'נאמנות משפחתית: מתי זה הכלי הנכון, ומתי לא', 'נאמנויות', '2025-11-10 09:00:00' ),
		);
		foreach ( $articles as [ $title, $cat, $date ] ) {
			$term = term_exists( $cat, 'category' );
			if ( ! $term ) {
				$term = wp_insert_term( $cat, 'category' );
			}
			$cat_id  = is_array( $term ) ? (int) $term['term_id'] : 0;
			$content = '<!-- wp:paragraph --><p>' . esc_html__( 'תוכן המאמר יתווסף על-ידי הלקוח. זהו מאמר לדוגמה שנוצר עם הפעלת התבנית.', 'beckwealth' ) . '</p><!-- /wp:paragraph -->';
			$id      = wp_insert_post(
				array(
					'post_type'     => 'post',
					'post_status'   => 'publish',
					'post_title'    => $title,
					'post_content'  => $content,
					'post_date'     => $date,
					'post_category' => $cat_id ? array( $cat_id ) : array(),
				)
			);
		}
	}

	// עמודים: דף הבית, בלוג, יצירת קשר + הגדרת עמוד הבית.
	$front = get_page_by_path( 'home' );
	if ( ! $front ) {
		$front_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => __( 'דף הבית', 'beckwealth' ), 'post_name' => 'home' ) );
	} else {
		$front_id = $front->ID;
	}
	$blog = get_page_by_path( 'blog' );
	if ( ! $blog ) {
		$blog_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => __( 'בלוג', 'beckwealth' ), 'post_name' => 'blog' ) );
	} else {
		$blog_id = $blog->ID;
	}
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
}
add_action( 'after_switch_theme', 'beckwealth_seed_content', 20 );
