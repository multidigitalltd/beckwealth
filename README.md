# Beck Wealth – תבנית וורדפרס

תבנית ייעודית לאתר **Beck Wealth** (ניהול הון שוויצרי-ישראלי), שפותחה על-ידי [Multi Digital](https://m-d.co.il) לפי **תקן הפיתוח של Multi Digital** ולפי קובץ העיצוב `BeckWealth-Home-v6`.

- RTL מלא, עברית כברירת מחדל (Almoni + Index, מתארחים מקומית כ-woff2).
- ללא תלות בתוספים: טופס לידים, מיני-CRM, סרגל נגישות, הצהרת נגישות, הודעת פרטיות, SEO בסיסי – הכול בתבנית.
- Vanilla JS בלבד, ללא jQuery, קבצים Minified וטעינה מותנית לפי עמוד.

## מבנה

```
beckwealth/
├── style.css                 כותרת התבנית בלבד (ה-CSS בתיקיית assets)
├── functions.php             טוען את המודולים מ-inc/
├── inc/
│   ├── settings.php          אופציה אחת (beckwealth_settings) + beckwealth_get_setting()
│   ├── home-defaults.php     כל שדות התוכן של דף הבית + ברירות המחדל מהעיצוב (מקור יחיד)
│   ├── customizer.php        קסטומייזר (נבנה אוטומטית מ-home-defaults)
│   ├── post-types.php        שירותים (מחלקות), צוות, המלצות, שאלות ותשובות + שדות מטא
│   ├── enqueue.php           טעינת CSS/JS מותנית, preload לגופנים, גרסאות .min בייצור
│   ├── contact-form.php      טופס יצירת קשר + ניוזלטר (nonce, honeypot, rate-limit, Turnstile)
│   ├── leads.php             מיני-CRM: סוג תוכן bw_lead, סטטוסים, סינון, ייצוא CSV
│   ├── turnstile.php         Cloudflare Turnstile (פעיל רק כשהוגדרו מפתחות)
│   ├── accessibility.php     סרגל נגישות + עמוד הצהרת נגישות (ת"י 5568 / WCAG 2.2 AA)
│   ├── privacy.php           הודעת מדיניות פרטיות בכניסה ראשונה (localStorage – ידידותי ל-cache)
│   ├── security.php          הקשחה: XML-RPC, כותרות אבטחה, REST users, author enum, גרסאות
│   ├── performance.php       אימוג'י, oEmbed, jQuery Migrate, heartbeat, סגנונות בלוקים לפי צורך
│   ├── seo.php               Open Graph + סכמת FinancialService (כבוי אוטומטית עם תוסף SEO)
│   ├── admin.php             דשבורד "Beck Wealth" (סקירה, קיצורי עריכה) + עמוד הגדרות
│   ├── seed.php              תוכן התחלתי מהעיצוב בהפעלה ראשונה (לא דורס תוכן קיים)
│   ├── compat.php            Elementor Pro locations, WooCommerce, LiteSpeed ESI nonce
│   └── template-tags.php     פונקציות עזר (כפתורים, יהלום, משבצות תמונה, אייקונים, פירורי לחם)
├── template-parts/home/      14 מקטעי דף הבית (hero, trust, swiss, advantage, departments, offices,
│                             quote, legacy, process, team, testimonials, blog, faq, contact) + ticker
├── template-parts/content/   כרטיסים לעמודי ארכיון
├── page-templates/           יצירת קשר, רוחב מלא
├── patterns/                 תבניות בלוקים לעורך
├── assets/css                main (גלובלי) · home (דף הבית) · content (עמודים פנימיים) · editor
├── assets/js                 main (גלובלי) · home · contact-form · a11y (נטען עצל)
├── assets/fonts, assets/img  גופנים ותמונות ברירת מחדל מהעיצוב (WebP)
├── bin/build.js              Minify עם esbuild (npm run build)
└── languages/beckwealth.pot  תבנית תרגום
```

## התקנה

1. העתיקו את התיקייה ל-`wp-content/themes/beckwealth` (או העלו כ-ZIP).
2. הפעילו את התבנית. בהפעלה הראשונה נוצרים אוטומטית: דף הבית (מוגדר כעמוד ראשי), בלוג, יצירת קשר,
   הצהרת נגישות, 3 מחלקות, 4 אנשי צוות, 3 המלצות, 7 שאלות ו-3 מאמרים לדוגמה – עם תוכן העיצוב המאושר.
3. **Beck Wealth ‹ הגדרות**: אימייל ללידים, מפתחות Turnstile, טקסט הודעת הפרטיות, רכז/ת נגישות.
4. **הגדרות ‹ פרטיות**: בחרו/פרסמו עמוד מדיניות פרטיות (הקישור בהודעה ובטופס נמשך משם).
5. **מראה ‹ התאמה אישית ‹ הגדרות Beck Wealth**: פרטי קשר (טלפון, וואטסאפ), רשתות, וכל טקסט/תמונה בדף הבית.
6. **מראה ‹ תפריטים**: תפריט ראשי, פוטר ומשפטי (ללא תפריט – עוגני העיצוב מוצגים אוטומטית).

דרישות: WordPress 6.4+, PHP 8.0+ (נבדק על 8.4). מומלץ: LiteSpeed Cache / WP Rocket + Cloudflare.

## מה ניתן לערוך ואיפה

| מה | איפה |
|---|---|
| טקסטים, תמונות, מספרים, שאלות-קישורים, פוטר | קסטומייזר ‹ הגדרות Beck Wealth (מחולק לפי מקטע) |
| מחלקות (3 כרטיסים + עמודי מחלקה) | שירותים – שדות "תיאור קצר" ו"נקודות" + תמונה ראשית |
| צוות, המלצות, שאלות ותשובות | התפריטים הייעודיים; סדר ההצגה לפי "סדר" |
| מאמרים | פוסטים (קטגוריה ראשונה מוצגת כתגית זהב) |
| לידים | Beck Wealth ‹ לידים: סטטוס, הערות, סינון, ייצוא CSV |
| מתגים: אנימציות, שחור-לבן במעבר, שורת מהשוק, שאלה פתוחה, וואטסאפ | קסטומייזר ‹ התנהגות ואנימציות |

## אבטחה (תקן Multi Digital)

- כל קלט עובר `sanitize_*`, כל פלט עובר `esc_*`/`wp_kses_post`.
- Nonce + בדיקת הרשאות (`current_user_can`) בכל פעולה; `$wpdb->prepare` בשאילתה הישירה היחידה (ספירת לידים, במטמון).
- טפסים: Honeypot, זמן מילוי מינימלי, הגבלת קצב לפי IP (transient), Cloudflare Turnstile אופציונלי, `wp_safe_redirect` לדומיין האתר בלבד, CSV מוגן מפני הזרקת נוסחאות.
- מפתח Turnstile הסודי ניתן להגדרה כקבוע: `define( 'BECKWEALTH_TURNSTILE_SECRET', '...' );` ו-`BECKWEALTH_TURNSTILE_SITE_KEY`.
- אין `eval/exec/base64`, אין קוד מוסתר; שגיאות אינן נחשפות לגולש.
- מומלץ ב-`wp-config.php`: `DISALLOW_FILE_EDIT`, `WP_POST_REVISIONS`, מפתחות ייחודיים.

## ביצועים

- דף הבית: 2 קבצי CSS + 3 JS (Minified, defer). עמודים פנימיים: 2 CSS + 1 JS. סרגל הנגישות נטען רק בלחיצה.
- גופנים מקומיים עם `font-display: swap` ו-preload לשניים הקריטיים; תמונות WebP, `loading="lazy"`, `fetchpriority="high"` להירו.
- שאילתות: `no_found_rows`, ללא מטא/טרם cache כשלא נדרש, ספירת לידים ב-transient (5 דק').
- **Cache מלא (LiteSpeed/WP Rocket/Cloudflare):** ה-nonce של הטופס תקף 12–24 שעות – הגדירו TTL לעמודים ≤ 12 שעות, או הפעילו ESI ב-LiteSpeed (התבנית רושמת את ה-nonce ל-ESI אוטומטית). תגובות הטופס נשלחות עם `DONOTCACHEPAGE`.

## נגישות

- WCAG 2.2 AA / ת"י 5568: HTML סמנטי, H1 יחיד, תוויות לכל שדה, ניווט מקלדת מלא (כולל אקורדיון, תפריט נייד וסרגל נגישות עם לכידת פוקוס ו-ESC), `prefers-reduced-motion`, ניגודיות ≥ 4.5:1.
- axe-core (wcag2a/aa, 2.1, 2.2, best-practice): **0 הפרות** בדף הבית, בלוג, מחלקה, יצירת קשר והצהרת נגישות.
- סטייה מכוונת מהעיצוב לצורך ניגודיות: גוונים משניים הוכהו מעט (`#96793a→#7a6532` לטקסט קטן, `#877d6f→#6f665a`, `#9a9184→#767063`, `#b0a58e→#776d5f`); התיבה בטופס 24px במקום 17px. הערכים המקוריים מתועדים ב-`assets/css/main.css`.

## פיתוח

```bash
npm install          # esbuild בלבד
npm run build        # יוצר assets/**/*.min.{css,js}
composer install && composer lint   # WordPress Coding Standards (phpcs.xml.dist)
```

בסביבת פיתוח (`WP_ENVIRONMENT_TYPE !== production` או `SCRIPT_DEBUG`) נטענים קבצי המקור עם גרסה לפי זמן שינוי.

## Hooks

- `beckwealth_contact_lead( array $lead, int $lead_id )` – ליד חדש (חיבור ל-CRM חיצוני).
- `beckwealth_newsletter_signup( string $email )` – הרשמה לניוזלטר (חיבור למערכת דיוור).

## רישוי

GPL-2.0-or-later. גופני Almoni ו-Index סופקו על-ידי הלקוח – יש לוודא רישיון web לפני עלייה לאוויר.
