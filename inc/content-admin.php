<?php
/**
 * "ניהול תוכן" – מסך עריכה ידידותי בלוח הבקרה לכל הטקסטים, התמונות ופרטי הקשר של האתר.
 *
 * נבנה אוטומטית מאותם רישומי שדות שמזינים את הקסטומייזר (home-defaults.php, pages-defaults.php),
 * ולכן שני המסכים עורכים את אותם הנתונים (theme_mods). בנוסף: זהות האתר (לוגו, שם), פרטי קשר,
 * רשתות, הודעת הפרטיות ואימייל הלידים. רשימות ("שורה לכל פריט") מוצגות כטבלאות עם הוספה/הסרה/סידור,
 * תמונות עם בחירה מספריית המדיה, ומתגים במקום צ'קבוקסים.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * הלשוניות במסך: id => [title, sections, view-url (callable), icon].
 *
 * @return array<string, array{title:string, sections:string[], view:string, icon:string, intro:string}>
 */
function beckwealth_content_tabs(): array {
	$services = get_post_type_archive_link( 'service' );
	return array(
		'general'  => array(
			'title'    => __( 'כללי ופרטי קשר', 'beckwealth' ),
			'icon'     => 'dashicons-admin-site-alt3',
			'sections' => array( 'site', 'contact', 'social', 'header', 'footer', 'privacy', 'behavior' ),
			'view'     => home_url( '/' ),
			'intro'    => __( 'הלוגו, שם האתר, טלפון ווואטסאפ, רשתות, שורת "מהשוק", הפוטר והודעת הפרטיות – כל מה שמופיע בכל העמודים.', 'beckwealth' ),
		),
		'home'     => array(
			'title'    => __( 'דף הבית', 'beckwealth' ),
			'icon'     => 'dashicons-admin-home',
			'sections' => array( 'hero', 'trust', 'swiss', 'advantage', 'departments', 'offices', 'quote', 'legacy', 'process', 'team', 'blog', 'faq', 'contact_section' ),
			'view'     => home_url( '/' ),
			'intro'    => __( 'המקטעים לפי הסדר שבו הם מופיעים בעמוד, מלמעלה למטה.', 'beckwealth' ),
		),
		'about'    => array(
			'title'    => __( 'מי אנחנו', 'beckwealth' ),
			'icon'     => 'dashicons-groups',
			'sections' => array( 'about', 'about_story', 'about_group' ),
			'view'     => function_exists( 'beckwealth_about_url' ) ? beckwealth_about_url() : home_url( '/' ),
			'intro'    => __( 'הירו, ציר הזמן של שלושת הדורות, ארבעת פרקי הסיפור ומבנה הקבוצה.', 'beckwealth' ),
		),
		'swiss'    => array(
			'title'    => __( 'היתרון השוויצרי', 'beckwealth' ),
			'icon'     => 'dashicons-shield',
			'sections' => array( 'swiss_page' ),
			'view'     => function_exists( 'beckwealth_swiss_url' ) ? beckwealth_swiss_url() : home_url( '/' ),
			'intro'    => '',
		),
		'services' => array(
			'title'    => __( 'מחלקות', 'beckwealth' ),
			'icon'     => 'dashicons-portfolio',
			'sections' => array( 'ip_shared', 'svc_fam', 'svc_tax', 'svc_trust' ),
			'view'     => $services ? $services : home_url( '/' ),
			'intro'    => __( 'שלושת עמודי המחלקות המעוצבים. שם המחלקה, התיאור הקצר והנקודות בכרטיס בדף הבית נערכים בתפריט "מחלקות".', 'beckwealth' ),
		),
		'contact'  => array(
			'title'    => __( 'יצירת קשר', 'beckwealth' ),
			'icon'     => 'dashicons-email-alt',
			'sections' => array( 'contact_page' ),
			'view'     => function_exists( 'beckwealth_contact_url' ) ? beckwealth_contact_url() : home_url( '/' ),
			'intro'    => __( 'עמוד יצירת הקשר: שתי הערים, מכתב הפנייה והערוצים הישירים. הטלפון, הכתובת והאימייל עצמם – בלשונית "כללי ופרטי קשר".', 'beckwealth' ),
		),
		'more'     => array(
			'title'    => __( 'בלוג, צוות ועוד', 'beckwealth' ),
			'icon'     => 'dashicons-admin-post',
			'sections' => array(),
			'view'     => '',
			'intro'    => '',
		),
	);
}

/**
 * כותרות הסקשנים (מהרישומים + סקשנים ייעודיים למסך הזה).
 *
 * @return array<string, string>
 */
function beckwealth_content_section_titles(): array {
	return array(
		'site'    => __( 'זהות האתר – לוגו ושם', 'beckwealth' ),
		'contact' => __( 'פרטי קשר – טלפון, וואטסאפ, אימייל, כתובת', 'beckwealth' ),
		'social'  => __( 'רשתות חברתיות', 'beckwealth' ),
		'privacy' => __( 'הודעת פרטיות ואימייל לידים', 'beckwealth' ),
	) + beckwealth_home_sections() + beckwealth_page_sections();
}

/**
 * תיאור קצר לסקשן (מוצג מתחת לכותרת הכרטיס).
 *
 * @return array<string, string>
 */
function beckwealth_content_section_help(): array {
	return array(
		'site'            => __( 'הלוגו מוצג בכותרת ובפוטר (PNG/SVG שקוף, רוחב 900px ומעלה). אייקון האתר מופיע בלשונית הדפדפן (ריבוע, 512×512).', 'beckwealth' ),
		'contact'         => __( 'הפרטים האלה מוצגים בכותרת, בפוטר, בכפתור הוואטסאפ הצף ובעמוד יצירת הקשר. האימייל מקבל את הפניות מהטופס.', 'beckwealth' ),
		'privacy'         => __( 'ההודעה מוצגת פעם אחת לכל גולש. הקישור למדיניות הפרטיות נלקח מ"הגדרות ‹ פרטיות".', 'beckwealth' ),
		'behavior'        => __( 'מתגים כלליים. כיבוי "אנימציות" מבטל את כל התנועה באתר (למשל לבדיקות נגישות).', 'beckwealth' ),
		'header'          => __( 'שורת "מהשוק" רצה מתחת לתפריט. ריק = השורה לא מוצגת.', 'beckwealth' ),
		'hero'            => __( 'המסך הראשון בדף הבית. טקסט בין ** מודגש בזהב.', 'beckwealth' ),
		'team'            => __( 'אנשי הצוות עצמם (שם, תפקיד, תמונה) נערכים בתפריט "צוות".', 'beckwealth' ),
		'faq'             => __( 'השאלות והתשובות עצמן נערכות בתפריט "שאלות ותשובות".', 'beckwealth' ),
		'blog'            => __( 'הכתבות עצמן נערכות בתפריט "פוסטים". כתבה חיצונית = פוסט עם "קישור לכתבה חיצונית".', 'beckwealth' ),
		'departments'     => __( 'שם המחלקה, התיאור והנקודות בכרטיסים נערכים בתפריט "מחלקות".', 'beckwealth' ),
		'contact_section' => __( 'הטופס בתחתית דף הבית והפופאפ שמוצג אחרי שליחה מוצלחת.', 'beckwealth' ),
		'about'           => __( 'ציר הזמן: שורת דור מתחילה ב-# (למשל "# I"), ואחריה שורות של שנה + טקסט.', 'beckwealth' ),
		'about_story'     => __( 'ארבעה פרקים. כל שורה בטקסט = פסקה נפרדת.', 'beckwealth' ),
	);
}

/**
 * שדות ייעודיים למסך (שאינם ברישומי התוכן): זהות, פרטי קשר, רשתות, פרטיות.
 * מבנה כל שדה: id (מפתח אחסון), kind (mod|option|setting), label, type, default, section, help.
 *
 * @return array<string, array<string, mixed>>
 */
function beckwealth_content_extra_fields(): array {
	$fields = array(
		'custom_logo'    => array( 'kind' => 'mod', 'label' => __( 'לוגו', 'beckwealth' ), 'type' => 'image', 'default' => 0, 'section' => 'site' ),
		'site_icon'      => array( 'kind' => 'option', 'label' => __( 'אייקון האתר (favicon)', 'beckwealth' ), 'type' => 'image', 'default' => 0, 'section' => 'site' ),
		'blogname'       => array( 'kind' => 'option', 'label' => __( 'שם האתר', 'beckwealth' ), 'type' => 'text', 'default' => '', 'section' => 'site' ),
		'blogdescription' => array( 'kind' => 'option', 'label' => __( 'תיאור קצר (מוצג בכותרת הלשונית)', 'beckwealth' ), 'type' => 'text', 'default' => '', 'section' => 'site' ),

		'beckwealth_phone'        => array( 'kind' => 'mod', 'label' => __( 'טלפון (ישראל)', 'beckwealth' ), 'type' => 'text', 'default' => '03-000-0000', 'section' => 'contact', 'ltr' => true ),
		'beckwealth_whatsapp'     => array( 'kind' => 'mod', 'label' => __( 'וואטסאפ – מספר (למשל 0501234567 או +972501234567)', 'beckwealth' ), 'type' => 'text', 'default' => '', 'section' => 'contact', 'ltr' => true ),
		'beckwealth_email'        => array( 'kind' => 'mod', 'label' => __( 'אימייל ליצירת קשר (מקבל את הפניות מהטופס)', 'beckwealth' ), 'type' => 'email', 'default' => BECKWEALTH_OFFICE_EMAIL, 'section' => 'contact', 'ltr' => true ),
		'beckwealth_address'      => array( 'kind' => 'mod', 'label' => __( 'כתובת (ישראל)', 'beckwealth' ), 'type' => 'text', 'default' => BECKWEALTH_OFFICE_ADDRESS, 'section' => 'contact' ),
		'beckwealth_hours'        => array( 'kind' => 'mod', 'label' => __( 'שעות פעילות', 'beckwealth' ), 'type' => 'text', 'default' => '', 'section' => 'contact' ),
		'beckwealth_contact_page' => array( 'kind' => 'mod', 'label' => __( 'עמוד יצירת קשר (היעד של כפתורי "תיאום שיחה")', 'beckwealth' ), 'type' => 'page', 'default' => 0, 'section' => 'contact' ),

		'privacy_popup'      => array( 'kind' => 'setting', 'label' => __( 'הצגת הודעת פרטיות בכניסה לאתר', 'beckwealth' ), 'type' => 'checkbox', 'default' => true, 'section' => 'privacy' ),
		'privacy_popup_text' => array( 'kind' => 'setting', 'label' => __( 'טקסט ההודעה', 'beckwealth' ), 'type' => 'textarea', 'default' => '', 'section' => 'privacy' ),
		'lead_notify_email'  => array( 'kind' => 'setting', 'label' => __( 'אימייל להתראה על ליד חדש (ריק = האימייל מפרטי הקשר)', 'beckwealth' ), 'type' => 'email', 'default' => '', 'section' => 'privacy', 'ltr' => true ),
	);
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'x' => 'X (Twitter)', 'tiktok' => 'TikTok' ) as $key => $label ) {
		$fields[ 'beckwealth_social_' . $key ] = array( 'kind' => 'mod', 'label' => $label, 'type' => 'url', 'default' => '', 'section' => 'social', 'ltr' => true );
	}
	return $fields;
}

/**
 * כל השדות של המסך, מנורמלים: id => [kind, label, type, default, section, ltr].
 *
 * @return array<string, array<string, mixed>>
 */
function beckwealth_content_all_fields(): array {
	static $all = null;
	if ( null !== $all ) {
		return $all;
	}
	$all = array();
	foreach ( beckwealth_home_fields() + beckwealth_page_fields() as $key => [ $label, $type, $default, $section ] ) {
		$all[ 'bw_' . $key ] = array( 'kind' => 'mod', 'label' => $label, 'type' => $type, 'default' => $default, 'section' => $section, 'ltr' => 'url' === $type );
	}
	foreach ( beckwealth_content_extra_fields() as $id => $field ) {
		$all[ $id ] = $field + array( 'ltr' => false );
	}
	return $all;
}

/**
 * הערך השמור של שדה.
 *
 * @param string               $id    מזהה.
 * @param array<string, mixed> $field הגדרת השדה.
 * @return mixed
 */
function beckwealth_content_get( string $id, array $field ) {
	switch ( $field['kind'] ) {
		case 'option':
			return get_option( $id, $field['default'] );
		case 'setting':
			return beckwealth_get_setting( $id, $field['default'] );
		default:
			return get_theme_mod( $id, $field['default'] );
	}
}

/**
 * ניתוח שדה textarea: רשימה (עמודות), פסקאות או טקסט חופשי.
 *
 * @param string $id    מזהה.
 * @param string $label תווית.
 * @param mixed  $default ברירת מחדל.
 * @return array{mode:string, columns:string[]}
 */
function beckwealth_content_textarea_mode( string $id, string $label, $default ): array {
	$overrides = array(
		'bw_about_tl_items' => array( __( 'שנה (או "# I" לפתיחת דור)', 'beckwealth' ), __( 'טקסט האירוע (או כותרת הדור)', 'beckwealth' ), __( 'שם (רק בשורת דור)', 'beckwealth' ) ),
	);
	if ( isset( $overrides[ $id ] ) ) {
		return array( 'mode' => 'list', 'columns' => $overrides[ $id ] );
	}
	if ( str_contains( $label, 'פסק' ) ) {
		return array( 'mode' => 'paragraphs', 'columns' => array() );
	}
	$default = (string) $default;
	$is_list = str_contains( $label, 'שורה לכל' ) || str_contains( $label, 'שורת ' ) || str_contains( $label, 'בכל שורה' ) || str_contains( $label, '|' ) || str_contains( $default, "\n" ) || str_contains( $default, '|' );
	if ( ! $is_list ) {
		return array( 'mode' => 'text', 'columns' => array() );
	}
	$columns = array();
	if ( preg_match( '/([^\s:()"]+(?:\|[^\s:()"]+)+)/u', $label, $m ) ) {
		$columns = array_map( 'trim', explode( '|', $m[1] ) );
	} else {
		$first = (string) strtok( $default, "\n" );
		$n     = substr_count( $first, '|' ) + 1;
		if ( 1 === $n ) {
			$columns = array( __( 'פריט', 'beckwealth' ) );
		} else {
			for ( $i = 1; $i <= $n; $i++ ) {
				/* translators: %d: column number */
				$columns[] = sprintf( __( 'חלק %d', 'beckwealth' ), $i );
			}
		}
	}
	return array( 'mode' => 'list', 'columns' => $columns );
}

/**
 * רישום המסך בתפריט (אחרי "סקירה").
 */
function beckwealth_content_admin_menu(): void {
	$hook = add_submenu_page( 'beckwealth', __( 'ניהול תוכן', 'beckwealth' ), __( 'ניהול תוכן', 'beckwealth' ), 'edit_theme_options', 'beckwealth-content', 'beckwealth_render_content_admin', 1 );
	add_action( 'load-' . $hook, 'beckwealth_content_admin_load' );
}
add_action( 'admin_menu', 'beckwealth_content_admin_menu', 11 );

/**
 * נכסים של המסך (רק בו).
 */
function beckwealth_content_admin_load(): void {
	add_action(
		'admin_enqueue_scripts',
		static function (): void {
			wp_enqueue_media();
			wp_enqueue_style( 'beckwealth-admin-content', beckwealth_asset_url( 'assets/css/admin-content.css' ), array(), beckwealth_asset_version( 'assets/css/admin-content.css' ) );
			wp_enqueue_script( 'beckwealth-admin-content', beckwealth_asset_url( 'assets/js/admin-content.js' ), array( 'jquery' ), beckwealth_asset_version( 'assets/js/admin-content.js' ), true );
			wp_localize_script(
				'beckwealth-admin-content',
				'beckwealthContent',
				array(
					'i18n' => array(
						'choose'   => __( 'בחירת תמונה', 'beckwealth' ),
						'use'      => __( 'שימוש בתמונה זו', 'beckwealth' ),
						'remove'   => __( 'הסרה', 'beckwealth' ),
						'unsaved'  => __( 'יש שינויים שלא נשמרו.', 'beckwealth' ),
						'noMatch'  => __( 'לא נמצאו שדות מתאימים בלשונית הזו. נסו לשונית אחרת.', 'beckwealth' ),
						'rowUp'    => __( 'העברה למעלה', 'beckwealth' ),
						'rowDown'  => __( 'העברה למטה', 'beckwealth' ),
						'rowDel'   => __( 'מחיקת שורה', 'beckwealth' ),
					),
				)
			);
		}
	);
}

/**
 * מסך ניהול התוכן.
 */
function beckwealth_render_content_admin(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$tabs   = beckwealth_content_tabs();
	$titles = beckwealth_content_section_titles();
	$help   = beckwealth_content_section_help();
	$fields = beckwealth_content_all_fields();
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- פרמטרי תצוגה בלבד.
	$current = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
	$updated = ! empty( $_GET['updated'] );
	// phpcs:enable
	if ( ! isset( $tabs[ $current ] ) ) {
		$current = 'general';
	}
	$tab = $tabs[ $current ];
	?>
	<div class="wrap bw-content">
		<div class="bw-content__head">
			<div>
				<h1><?php esc_html_e( 'ניהול תוכן', 'beckwealth' ); ?></h1>
				<p class="bw-content__lead"><?php esc_html_e( 'כל הטקסטים, התמונות ופרטי הקשר של האתר במקום אחד. עורכים, לוחצים "שמירה" – והאתר מתעדכן מיד.', 'beckwealth' ); ?></p>
			</div>
			<label class="bw-content__search">
				<span class="dashicons dashicons-search" aria-hidden="true"></span>
				<input type="search" id="bw-content-search" placeholder="<?php esc_attr_e( 'חיפוש שדה (למשל: טלפון, כותרת, תמונה)', 'beckwealth' ); ?>" autocomplete="off">
			</label>
		</div>

		<?php if ( $updated ) : ?>
			<div class="notice notice-success is-dismissible bw-content__notice"><p><?php esc_html_e( 'השינויים נשמרו. האתר מעודכן.', 'beckwealth' ); ?> <a href="<?php echo esc_url( $tab['view'] ?: home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'לצפייה בעמוד ↗', 'beckwealth' ); ?></a></p></div>
		<?php endif; ?>

		<nav class="bw-tabs" aria-label="<?php esc_attr_e( 'עמודי האתר', 'beckwealth' ); ?>">
			<?php foreach ( $tabs as $id => $t ) : ?>
				<a class="bw-tab<?php echo $id === $current ? ' is-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=beckwealth-content&tab=' . $id ) ); ?>"<?php echo $id === $current ? ' aria-current="page"' : ''; ?>>
					<span class="dashicons <?php echo esc_attr( $t['icon'] ); ?>" aria-hidden="true"></span><?php echo esc_html( $t['title'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<?php if ( 'more' === $current ) : ?>
			<?php beckwealth_render_content_more_tab(); ?>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="bw-content-form" class="bw-content__form">
				<input type="hidden" name="action" value="beckwealth_save_content">
				<input type="hidden" name="tab" value="<?php echo esc_attr( $current ); ?>">
				<?php wp_nonce_field( 'beckwealth_save_content_' . $current, 'bw_content_nonce' ); ?>

				<div class="bw-content__intro">
					<p><?php echo esc_html( $tab['intro'] ); ?></p>
					<?php if ( $tab['view'] ) : ?>
						<a class="button" href="<?php echo esc_url( $tab['view'] ); ?>" target="_blank" rel="noopener"><span class="dashicons dashicons-visibility" aria-hidden="true"></span> <?php esc_html_e( 'צפייה בעמוד באתר', 'beckwealth' ); ?></a>
					<?php endif; ?>
				</div>

				<?php foreach ( $tab['sections'] as $section ) : ?>
					<?php
					$section_fields = array_filter( $fields, static fn( array $f ): bool => $f['section'] === $section );
					if ( ! $section_fields ) {
						continue;
					}
					?>
					<details class="bw-section" open data-section="<?php echo esc_attr( $section ); ?>">
						<summary class="bw-section__head">
							<span class="bw-section__title"><?php echo esc_html( $titles[ $section ] ?? $section ); ?></span>
							<span class="bw-section__count"><?php echo esc_html( number_format_i18n( count( $section_fields ) ) ); ?></span>
							<span class="bw-section__chev dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>
						</summary>
						<?php if ( ! empty( $help[ $section ] ) ) : ?>
							<p class="bw-section__help"><?php echo esc_html( $help[ $section ] ); ?></p>
						<?php endif; ?>
						<div class="bw-fields">
							<?php foreach ( $section_fields as $id => $field ) : ?>
								<?php beckwealth_render_content_field( $id, $field ); ?>
							<?php endforeach; ?>
						</div>
					</details>
				<?php endforeach; ?>

				<p class="bw-content__nomatch" hidden></p>

				<div class="bw-savebar">
					<span class="bw-savebar__status" aria-live="polite"></span>
					<?php submit_button( __( 'שמירת השינויים', 'beckwealth' ), 'primary large', 'submit', false ); ?>
				</div>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * שדה אחד במסך.
 *
 * @param string               $id    מזהה אחסון.
 * @param array<string, mixed> $field הגדרת השדה.
 */
function beckwealth_render_content_field( string $id, array $field ): void {
	$value = beckwealth_content_get( $id, $field );
	$type  = (string) $field['type'];
	$label = (string) $field['label'];
	$name  = 'f[' . $id . ']';
	$dom   = 'bw-f-' . sanitize_html_class( $id );
	$wide  = in_array( $type, array( 'textarea', 'image' ), true );
	$help  = '';
	// הסבר קצר מהתווית (החלק שבסוגריים) – מוצג כהערה מתחת לשדה.
	if ( preg_match( '/^(.*?)\s*\((.+)\)\s*$/u', $label, $m ) && 'textarea' !== $type ) {
		$label = $m[1];
		$help  = $m[2];
	}
	?>
	<div class="bw-f<?php echo $wide ? ' bw-f--wide' : ''; ?> bw-f--<?php echo esc_attr( $type ); ?>" data-search="<?php echo esc_attr( mb_strtolower( $field['label'] . ' ' . $id ) ); ?>">
		<?php if ( 'checkbox' === $type ) : ?>
			<label class="bw-switch">
				<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( (bool) $value ); ?>>
				<span class="bw-switch__track" aria-hidden="true"></span>
				<span class="bw-switch__label"><?php echo esc_html( $label ); ?></span>
			</label>
		<?php elseif ( 'image' === $type ) : ?>
			<?php $img_id = (int) $value; ?>
			<span class="bw-f__label"><?php echo esc_html( $label ); ?></span>
			<div class="bw-image<?php echo $img_id ? ' has-image' : ''; ?>">
				<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $img_id ); ?>" class="bw-image__id">
				<div class="bw-image__preview"><?php echo $img_id ? wp_get_attachment_image( $img_id, 'medium' ) : ''; ?></div>
				<div class="bw-image__actions">
					<button type="button" class="button bw-image__choose"><span class="dashicons dashicons-format-image" aria-hidden="true"></span> <?php echo $img_id ? esc_html__( 'החלפת תמונה', 'beckwealth' ) : esc_html__( 'בחירת תמונה', 'beckwealth' ); ?></button>
					<button type="button" class="button-link bw-image__remove"<?php echo $img_id ? '' : ' hidden'; ?>><?php esc_html_e( 'הסרה', 'beckwealth' ); ?></button>
				</div>
			</div>
		<?php elseif ( 'page' === $type ) : ?>
			<label class="bw-f__label" for="<?php echo esc_attr( $dom ); ?>"><?php echo esc_html( $label ); ?></label>
			<?php
			wp_dropdown_pages(
				array(
					'name'              => $name,
					'id'                => $dom,
					'selected'          => (int) $value,
					'show_option_none'  => __( '— ללא (עוגן לטופס בדף הבית) —', 'beckwealth' ),
					'option_none_value' => '0',
					'class'             => 'bw-input',
				)
			);
			?>
		<?php elseif ( 'textarea' === $type ) : ?>
			<?php $mode = beckwealth_content_textarea_mode( $id, $label, $field['default'] ); ?>
			<?php if ( 'list' === $mode['mode'] ) : ?>
				<?php
				$clean_label = preg_replace( '/\s*[–\-:(].*$/u', '', $label ) ?: $label;
				$rows        = array();
				foreach ( preg_split( '/\r\n|\r|\n/', (string) $value ) ?: array() as $line ) {
					if ( '' === trim( $line ) ) {
						continue;
					}
					$rows[] = array_map( 'trim', explode( '|', $line ) );
				}
				$long_cols = array();
				foreach ( $mode['columns'] as $i => $col ) {
					$long_cols[ $i ] = (bool) preg_match( '/טקסט|תיאור|תשובה|פסקה|כיתוב|שאלה|תוכן/u', $col );
				}
				?>
				<span class="bw-f__label"><?php echo esc_html( $clean_label ); ?></span>
				<span class="bw-f__help"><?php echo esc_html( $label ); ?></span>
				<div class="bw-list" data-cols="<?php echo esc_attr( (string) count( $mode['columns'] ) ); ?>">
					<textarea name="<?php echo esc_attr( $name ); ?>" class="bw-list__value" hidden aria-hidden="true" tabindex="-1"><?php echo esc_textarea( (string) $value ); ?></textarea>
					<table class="bw-list__table">
						<thead>
							<tr>
								<th class="bw-list__n" scope="col">#</th>
								<?php foreach ( $mode['columns'] as $col ) : ?>
									<th scope="col"><?php echo esc_html( $col ); ?></th>
								<?php endforeach; ?>
								<th class="bw-list__tools" scope="col"><span class="screen-reader-text"><?php esc_html_e( 'פעולות', 'beckwealth' ); ?></span></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $rows as $r => $cells ) : ?>
								<tr>
									<td class="bw-list__n"><?php echo esc_html( (string) ( $r + 1 ) ); ?></td>
									<?php foreach ( $mode['columns'] as $i => $col ) : ?>
										<td>
											<?php if ( $long_cols[ $i ] ) : ?>
												<textarea rows="1" class="bw-cell" aria-label="<?php echo esc_attr( $col ); ?>"><?php echo esc_textarea( $cells[ $i ] ?? '' ); ?></textarea>
											<?php else : ?>
												<input type="text" class="bw-cell" value="<?php echo esc_attr( $cells[ $i ] ?? '' ); ?>" aria-label="<?php echo esc_attr( $col ); ?>">
											<?php endif; ?>
										</td>
									<?php endforeach; ?>
									<td class="bw-list__tools">
										<button type="button" class="bw-list__btn" data-row-up aria-label="<?php esc_attr_e( 'העברה למעלה', 'beckwealth' ); ?>"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
										<button type="button" class="bw-list__btn" data-row-down aria-label="<?php esc_attr_e( 'העברה למטה', 'beckwealth' ); ?>"><span class="dashicons dashicons-arrow-down-alt2"></span></button>
										<button type="button" class="bw-list__btn bw-list__btn--del" data-row-del aria-label="<?php esc_attr_e( 'מחיקת שורה', 'beckwealth' ); ?>"><span class="dashicons dashicons-no-alt"></span></button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<template class="bw-list__tpl">
						<tr>
							<td class="bw-list__n"></td>
							<?php foreach ( $mode['columns'] as $i => $col ) : ?>
								<td>
									<?php if ( $long_cols[ $i ] ) : ?>
										<textarea rows="1" class="bw-cell" aria-label="<?php echo esc_attr( $col ); ?>"></textarea>
									<?php else : ?>
										<input type="text" class="bw-cell" value="" aria-label="<?php echo esc_attr( $col ); ?>">
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
							<td class="bw-list__tools">
								<button type="button" class="bw-list__btn" data-row-up aria-label="<?php esc_attr_e( 'העברה למעלה', 'beckwealth' ); ?>"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
								<button type="button" class="bw-list__btn" data-row-down aria-label="<?php esc_attr_e( 'העברה למטה', 'beckwealth' ); ?>"><span class="dashicons dashicons-arrow-down-alt2"></span></button>
								<button type="button" class="bw-list__btn bw-list__btn--del" data-row-del aria-label="<?php esc_attr_e( 'מחיקת שורה', 'beckwealth' ); ?>"><span class="dashicons dashicons-no-alt"></span></button>
							</td>
						</tr>
					</template>
					<button type="button" class="button bw-list__add"><span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span> <?php esc_html_e( 'הוספת שורה', 'beckwealth' ); ?></button>
				</div>
			<?php else : ?>
				<label class="bw-f__label" for="<?php echo esc_attr( $dom ); ?>"><?php echo esc_html( $label ); ?></label>
				<?php if ( 'paragraphs' === $mode['mode'] ) : ?>
					<span class="bw-f__help"><?php esc_html_e( 'כל שורה (Enter) = פסקה חדשה באתר.', 'beckwealth' ); ?></span>
				<?php endif; ?>
				<textarea class="bw-input bw-textarea" id="<?php echo esc_attr( $dom ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="<?php echo 'paragraphs' === $mode['mode'] ? 7 : 3; ?>"><?php echo esc_textarea( (string) $value ); ?></textarea>
			<?php endif; ?>
		<?php else : ?>
			<label class="bw-f__label" for="<?php echo esc_attr( $dom ); ?>"><?php echo esc_html( $label ); ?></label>
			<?php if ( $help ) : ?>
				<span class="bw-f__help"><?php echo esc_html( $help ); ?></span>
			<?php endif; ?>
			<input type="<?php echo 'email' === $type ? 'email' : ( 'url' === $type ? 'text' : 'text' ); ?>" class="bw-input" id="<?php echo esc_attr( $dom ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $value ); ?>"<?php echo ! empty( $field['ltr'] ) ? ' dir="ltr"' : ''; ?><?php echo 'url' === $type ? ' placeholder="https://… או #contact"' : ''; ?>>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * הלשונית "בלוג, צוות ועוד": קיצורים לתכנים שמנוהלים כפריטים.
 */
function beckwealth_render_content_more_tab(): void {
	$cards = array(
		array( 'dashicons-admin-post', __( 'כתבות בבלוג', 'beckwealth' ), __( 'מאמרים פנימיים, וגם כתבות באתרים אחרים: בעריכת פוסט מלאו "קישור לכתבה חיצונית" – הכרטיס ייראה זהה והלחיצה תוביל החוצה.', 'beckwealth' ), admin_url( 'edit.php' ), admin_url( 'post-new.php' ) ),
		array( 'dashicons-portfolio', __( 'מחלקות', 'beckwealth' ), __( 'שם המחלקה, תיאור קצר ונקודות בכרטיס. הטקסטים של עמודי המחלקות המעוצבים – בלשונית "מחלקות".', 'beckwealth' ), admin_url( 'edit.php?post_type=service' ), admin_url( 'post-new.php?post_type=service' ) ),
		array( 'dashicons-groups', __( 'צוות', 'beckwealth' ), __( 'אנשי הצוות המוצגים בדף הבית: שם, תפקיד, תמונה, פרטי קשר.', 'beckwealth' ), admin_url( 'edit.php?post_type=team' ), admin_url( 'post-new.php?post_type=team' ) ),
		array( 'dashicons-format-quote', __( 'המלצות', 'beckwealth' ), __( 'ציטוטי לקוחות בדף הבית.', 'beckwealth' ), admin_url( 'edit.php?post_type=testimonial' ), admin_url( 'post-new.php?post_type=testimonial' ) ),
		array( 'dashicons-editor-help', __( 'שאלות ותשובות', 'beckwealth' ), __( 'השאלות באקורדיון בדף הבית ובעמודי המחלקות.', 'beckwealth' ), admin_url( 'edit.php?post_type=faq' ), admin_url( 'post-new.php?post_type=faq' ) ),
		array( 'dashicons-feedback', __( 'לידים (פניות מהטופס)', 'beckwealth' ), __( 'כל הפניות שהתקבלו, עם סטטוס, הערות וייצוא.', 'beckwealth' ), admin_url( 'edit.php?post_type=bw_lead' ), '' ),
		array( 'dashicons-menu', __( 'תפריטים', 'beckwealth' ), __( 'התפריט הראשי, תפריט הפוטר והתפריט המשפטי.', 'beckwealth' ), admin_url( 'nav-menus.php' ), '' ),
		array( 'dashicons-admin-page', __( 'עמודים', 'beckwealth' ), __( 'מדיניות פרטיות, הצהרת נגישות ועמודים נוספים.', 'beckwealth' ), admin_url( 'edit.php?post_type=page' ), admin_url( 'post-new.php?post_type=page' ) ),
		array( 'dashicons-admin-generic', __( 'הגדרות מתקדמות', 'beckwealth' ), __( 'Cloudflare Turnstile, רכז/ת נגישות, עמוד הצהרת הנגישות.', 'beckwealth' ), admin_url( 'admin.php?page=beckwealth-settings' ), '' ),
	);
	?>
	<div class="bw-more">
		<?php foreach ( $cards as [ $icon, $title, $text, $url, $new ] ) : ?>
			<div class="bw-more__card">
				<span class="dashicons <?php echo esc_attr( $icon ); ?> bw-more__icon" aria-hidden="true"></span>
				<h2><?php echo esc_html( $title ); ?></h2>
				<p><?php echo esc_html( $text ); ?></p>
				<p class="bw-more__actions">
					<a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'ניהול', 'beckwealth' ); ?></a>
					<?php if ( $new ) : ?>
						<a class="button" href="<?php echo esc_url( $new ); ?>"><?php esc_html_e( '+ הוספה', 'beckwealth' ); ?></a>
					<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * שמירה.
 */
function beckwealth_save_content(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'אין הרשאה.', 'beckwealth' ) );
	}
	$tabs = beckwealth_content_tabs();
	$tab  = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : '';
	if ( ! isset( $tabs[ $tab ] ) || ! isset( $_POST['bw_content_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bw_content_nonce'] ), 'beckwealth_save_content_' . $tab ) ) {
		wp_die( esc_html__( 'הטופס פג תוקף. חזרו אחורה ונסו שוב.', 'beckwealth' ) );
	}
	$posted   = isset( $_POST['f'] ) && is_array( $_POST['f'] ) ? wp_unslash( $_POST['f'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- מנוקה פר שדה למטה.
	$settings = wp_parse_args( (array) get_option( 'beckwealth_settings', array() ), beckwealth_default_settings() );
	$changed  = false;

	foreach ( beckwealth_content_all_fields() as $id => $field ) {
		if ( ! in_array( $field['section'], $tabs[ $tab ]['sections'], true ) ) {
			continue;
		}
		$type = (string) $field['type'];
		if ( 'checkbox' === $type ) {
			$value = ! empty( $posted[ $id ] );
		} elseif ( ! array_key_exists( $id, $posted ) ) {
			continue;
		} else {
			$raw   = is_string( $posted[ $id ] ) ? $posted[ $id ] : '';
			$value = match ( $type ) {
				'textarea' => sanitize_textarea_field( $raw ),
				'url'      => beckwealth_sanitize_url_or_anchor( $raw ),
				'email'    => sanitize_email( $raw ),
				'image'    => absint( $raw ),
				'page'     => beckwealth_sanitize_select_page( $raw ),
				default    => sanitize_text_field( $raw ),
			};
		}

		switch ( $field['kind'] ) {
			case 'option':
				if ( ! current_user_can( 'manage_options' ) ) {
					break;
				}
				if ( 'site_icon' === $id ) {
					update_option( 'site_icon', (int) $value );
				} else {
					update_option( $id, $value );
				}
				break;
			case 'setting':
				if ( current_user_can( 'manage_options' ) ) {
					$settings[ $id ] = is_bool( $value ) ? ( $value ? '1' : '0' ) : $value;
					$changed         = true;
				}
				break;
			default:
				set_theme_mod( $id, $value );
		}
	}
	if ( $changed ) {
		update_option( 'beckwealth_settings', $settings );
	}

	wp_safe_redirect( add_query_arg( array( 'page' => 'beckwealth-content', 'tab' => $tab, 'updated' => '1' ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_beckwealth_save_content', 'beckwealth_save_content' );
