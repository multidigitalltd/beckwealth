<?php
/**
 * Beck Wealth – פונקציות התבנית.
 *
 * הקובץ הזה טוען בלבד את המודולים שבתיקיית inc/.
 * כל לוגיקה חדשה נכנסת למודול ייעודי ולא לכאן.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

define( 'BECKWEALTH_VERSION', '1.2.1' );
define( 'BECKWEALTH_OFFICE_EMAIL', 'office@beckwealth.co.il' );
define( 'BECKWEALTH_OFFICE_ADDRESS', 'מצדה 7, מגדל ב.ס.ר 4, בני ברק' ); // הכתובת בישראל – ברירת המחדל לפוטר, לעמוד יצירת קשר ולסכמה. // אימייל המשרד – ברירת המחדל לכל מקום שמוצג בו אימייל ליצירת קשר.
define( 'BECKWEALTH_DIR', get_template_directory() );
define( 'BECKWEALTH_URI', get_template_directory_uri() );

/**
 * בדיקת גרסת PHP מינימלית לפני טעינת שאר הקוד.
 */
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
	add_action(
		'admin_notices',
		static function () {
			echo '<div class="notice notice-error"><p>';
			esc_html_e( 'תבנית Beck Wealth דורשת PHP 8.0 ומעלה. אנא שדרגו את גרסת ה-PHP בשרת.', 'beckwealth' );
			echo '</p></div>';
		}
	);
	return;
}

$beckwealth_modules = array(
	'settings',       // הגדרות התבנית (אופציה אחת) + גישה אחידה.
	'home-defaults',  // שדות תוכן דף הבית וברירות מחדל מהעיצוב.
	'pages-defaults', // שדות תוכן העמודים הפנימיים (מי אנחנו, היתרון השוויצרי, מחלקות, יצירת קשר).
	'setup',          // הגדרות בסיס, תמיכות, תפריטים, גדלי תמונות.
	'nav-fallback',   // תפריטים חלופיים כשלא שויך תפריט.
	'template-tags',  // פונקציות עזר לתבניות.
	'pages',          // עזרי העמודים הפנימיים: זיהוי תבניות, מקטעים משותפים.
	'post-types',     // סוגי תוכן: שירותים, צוות, המלצות, שאלות.
	'blog',           // מאמרים פנימיים וכתבות חיצוניות (קישור יוצא בכרטיס).
	'customizer',     // הגדרות אתר בקסטומייזר.
	'enqueue',        // טעינת CSS/JS מותנית.
	'widgets',        // אזורי ווידג'טים.
	'security',       // הקשחת אבטחה.
	'performance',    // אופטימיזציית ביצועים.
	'seo',            // מטא-תגיות וסכמה.
	'turnstile',      // Cloudflare Turnstile.
	'leads',          // מיני-CRM ללידים.
	'contact-form',   // טופס יצירת קשר וניוזלטר.
	'accessibility',  // סרגל נגישות והצהרת נגישות.
	'privacy',        // הודעת מדיניות פרטיות.
	'compat',         // Elementor / WooCommerce / LiteSpeed.
	'patterns',       // תבניות בלוקים לעורך.
	'admin',          // דשבורד ייעודי והגדרות.
	'content-admin',  // מסך "ניהול תוכן" ידידותי (טקסטים, תמונות, פרטי קשר).
	'seed',           // תוכן התחלתי בהפעלה.
);

foreach ( $beckwealth_modules as $beckwealth_module ) {
	$beckwealth_path = BECKWEALTH_DIR . '/inc/' . $beckwealth_module . '.php';
	if ( is_readable( $beckwealth_path ) ) {
		require_once $beckwealth_path;
	}
}
unset( $beckwealth_modules, $beckwealth_module, $beckwealth_path );
