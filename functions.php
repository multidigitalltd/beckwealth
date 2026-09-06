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

define( 'BECKWEALTH_VERSION', '1.0.0' );
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
	'setup',          // הגדרות בסיס, תמיכות, תפריטים, גדלי תמונות.
	'nav-fallback',   // תפריטים חלופיים כשלא שויך תפריט.
	'template-tags',  // פונקציות עזר לתבניות.
	'post-types',     // סוגי תוכן: שירותים, צוות, המלצות, שאלות.
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
	'seed',           // תוכן התחלתי בהפעלה.
);

foreach ( $beckwealth_modules as $beckwealth_module ) {
	$beckwealth_path = BECKWEALTH_DIR . '/inc/' . $beckwealth_module . '.php';
	if ( is_readable( $beckwealth_path ) ) {
		require_once $beckwealth_path;
	}
}
unset( $beckwealth_modules, $beckwealth_module, $beckwealth_path );
