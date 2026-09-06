<?php
/**
 * הגדרת שדות התוכן של דף הבית (מקור יחיד לברירות מחדל – התוכן המאושר מהעיצוב v6).
 * הקסטומייזר נבנה אוטומטית מהמערך הזה; התבניות קוראות ערכים דרך beckwealth_mod().
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * מחזיר את כל השדות: id => [label, type, default, section].
 * type: text | textarea | url | image | checkbox | page
 *
 * @return array<string, array{0:string,1:string,2:mixed,3:string}>
 */
function beckwealth_home_fields(): array {
	static $fields = null;
	if ( null !== $fields ) {
		return $fields;
	}

	$fields = array(
		/* ---------- כללי / התנהגות ---------- */
		'motion'            => array( __( 'אנימציות (מתג ראשי)', 'beckwealth' ), 'checkbox', true, 'behavior' ),
		'grayscale_photos'  => array( __( 'תמונות עוברות לשחור-לבן במעבר עכבר', 'beckwealth' ), 'checkbox', true, 'behavior' ),
		'show_ticker'       => array( __( 'הצגת שורת "מהשוק"', 'beckwealth' ), 'checkbox', true, 'behavior' ),
		'open_first_faq'    => array( __( 'השאלה הראשונה פתוחה כברירת מחדל', 'beckwealth' ), 'checkbox', true, 'behavior' ),
		'show_whatsapp'     => array( __( 'הצגת כפתור וואטסאפ צף', 'beckwealth' ), 'checkbox', true, 'behavior' ),

		/* ---------- כותרת עליונה ---------- */
		'header_cta_label'  => array( __( 'כפתור בכותרת – טקסט', 'beckwealth' ), 'text', __( 'תיאום שיחה', 'beckwealth' ), 'header' ),
		'header_en_url'     => array( __( 'קישור לגרסה האנגלית (ריק = לא פעיל)', 'beckwealth' ), 'url', '', 'header' ),
		'ticker_items'      => array( __( 'שורת "מהשוק" – פריט בכל שורה', 'beckwealth' ), 'textarea', "ה־SMI, מדד המניות השוויצרי, סגר שבוע שלישי ברציפות של עליות\nהפרנק השוויצרי שומר על יציבות מול סל המטבעות העולמי\nBeckWealth מרחיבה את צוות הייעוץ בתל אביב\nביקוש גובר מצד משפחות ישראליות לפיזור הון גלובלי\nהבנק המרכזי השוויצרי הותיר את הריבית ללא שינוי", 'header' ),
		'ticker_label'      => array( __( 'שורת "מהשוק" – תווית', 'beckwealth' ), 'text', __( 'מהשוק', 'beckwealth' ), 'header' ),

		/* ---------- הירו ---------- */
		'hero_kicker'       => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'פמילי אופיס שוויצרי · שלושה דורות', 'beckwealth' ), 'hero' ),
		'hero_title'        => array( __( 'כותרת (טקסט בין ** יוצג בזהב)', 'beckwealth' ), 'text', __( 'ניהול הון **בבנקאות שוויצרית**, עם נוכחות מלאה בישראל', 'beckwealth' ), 'hero' ),
		'hero_text'         => array( __( 'טקסט', 'beckwealth' ), 'textarea', __( 'ליווי אישי למשפחות, ליזמים ולבעלי עסקים שרוצים את היציבות של ציריך, בלי לוותר על קרבה, על שפה ועל פגישה פנים אל פנים.', 'beckwealth' ), 'hero' ),
		'hero_btn_label'    => array( __( 'כפתור ראשי – טקסט', 'beckwealth' ), 'text', __( 'תיאום שיחת היכרות', 'beckwealth' ), 'hero' ),
		'hero_btn_url'      => array( __( 'כפתור ראשי – קישור', 'beckwealth' ), 'url', '#contact', 'hero' ),
		'hero_link_label'   => array( __( 'קישור משני – טקסט', 'beckwealth' ), 'text', __( 'מה זה היתרון השוויצרי', 'beckwealth' ), 'hero' ),
		'hero_link_url'     => array( __( 'קישור משני – קישור', 'beckwealth' ), 'url', '#advantage', 'hero' ),
		'hero_image_a'      => array( __( 'תמונה 1 (תל אביב, 3:4)', 'beckwealth' ), 'image', 0, 'hero' ),
		'hero_image_b'      => array( __( 'תמונה 2 (ציריך, 3:4)', 'beckwealth' ), 'image', 0, 'hero' ),
		'hero_cap_a_en'     => array( __( 'כיתוב 1 – לועזי', 'beckwealth' ), 'text', 'ZÜRICH · BAHNHOFSTRASSE', 'hero' ),
		'hero_cap_a_he'     => array( __( 'כיתוב 1 – עברי', 'beckwealth' ), 'text', __( 'המשרד הראשי, במרחק הליכה מהבנקים', 'beckwealth' ), 'hero' ),
		'hero_cap_b_en'     => array( __( 'כיתוב 2 – לועזי', 'beckwealth' ), 'text', 'TEL AVIV', 'hero' ),
		'hero_cap_b_he'     => array( __( 'כיתוב 2 – עברי', 'beckwealth' ), 'text', __( 'המשרד הראשי בישראל', 'beckwealth' ), 'hero' ),

		/* ---------- שורת אמון ---------- */
		'trust_image'       => array( __( 'תמונה (ציריך)', 'beckwealth' ), 'image', 0, 'trust' ),
		'trust_image_en'    => array( __( 'תווית לועזית על התמונה', 'beckwealth' ), 'text', 'ZÜRICH', 'trust' ),
		'trust_stats'       => array( __( 'מספרים – שורה לכל פריט: ערך|תיאור (למשל CHF 4.2B|נכסים בניהול)', 'beckwealth' ), 'textarea', "60+|שנות ותק בניהול הון\nCHF 4.2B|נכסים בניהול\n230+|משפחות מלוות", 'trust' ),
		'trust_claim'       => array( __( 'משפט על רקע כהה', 'beckwealth' ), 'text', __( 'בנק שוויצרי, חשבון על שמכם, איש קשר אחד בעברית.', 'beckwealth' ), 'trust' ),
		'trust_badge'       => array( __( 'תג – ערך', 'beckwealth' ), 'text', 'FINMA', 'trust' ),
		'trust_badge_label' => array( __( 'תג – תיאור', 'beckwealth' ), 'text', __( 'פיקוח שוויצרי', 'beckwealth' ), 'trust' ),

		/* ---------- שוויץ ---------- */
		'swiss_kicker'      => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'שוויץ', 'beckwealth' ), 'swiss' ),
		'swiss_title'       => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'מאה שנה של יציבות, במרחק שיחה אחת', 'beckwealth' ), 'swiss' ),
		'swiss_text'        => array( __( 'טקסט', 'beckwealth' ), 'textarea', __( 'שוויץ אינה רק כתובת. היא מערכת בנקאית שנבנתה על רציפות, על חוקי סודיות ופיקוח קפדניים ועל מטבע שמחזיק את ערכו לאורך דורות. ההון שלכם יושב שם, ואנחנו יושבים כאן.', 'beckwealth' ), 'swiss' ),
		'swiss_cards'       => array( __( 'ארבעה כרטיסים – שורה לכל כרטיס: תווית לועזית|כיתוב', 'beckwealth' ), 'textarea', "THE ALPS|נוף שלא משתנה. גם לא הכלכלה שמתחתיו\nZÜRICH|בהנהופשטראסה — לב הבנקאות הפרטית\nLAKE ZÜRICH|שקט, שמרנות, טווח ארוך\nFINMA|פיקוח שוויצרי על כל שקל וכל פרנק", 'swiss' ),
		'swiss_image_1'     => array( __( 'תמונה 1 (4:5)', 'beckwealth' ), 'image', 0, 'swiss' ),
		'swiss_image_2'     => array( __( 'תמונה 2 (4:5)', 'beckwealth' ), 'image', 0, 'swiss' ),
		'swiss_image_3'     => array( __( 'תמונה 3 (4:5)', 'beckwealth' ), 'image', 0, 'swiss' ),
		'swiss_image_4'     => array( __( 'תמונה 4 (4:5)', 'beckwealth' ), 'image', 0, 'swiss' ),

		/* ---------- היתרון ---------- */
		'adv_kicker'        => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'המודל', 'beckwealth' ), 'advantage' ),
		'adv_title'         => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'הביטחון של ציריך, הקרבה של תל אביב', 'beckwealth' ), 'advantage' ),
		'adv_ch_title'      => array( __( 'עמודה שוויץ – כותרת', 'beckwealth' ), 'text', __( 'היתרון השוויצרי', 'beckwealth' ), 'advantage' ),
		'adv_ch_tag'        => array( __( 'עמודה שוויץ – תווית לועזית', 'beckwealth' ), 'text', 'ZÜRICH', 'advantage' ),
		'adv_ch_points'     => array( __( 'עמודה שוויץ – נקודות (שורה לכל נקודה: מודגש|טקסט)', 'beckwealth' ), 'textarea', "יציבות ורגולציה.|מערכת בנקאית עם מעל מאה שנות רציפות, תחת פיקוח FINMA.\nפיזור בין מדינות ומטבעות.|שכבת ביטחון להון שאינה תלויה במערכת אחת.\nגישה לשווקים ומכשירים.|פלטפורמות השקעה גלובליות שאינן זמינות מישראל.", 'advantage' ),
		'adv_il_title'      => array( __( 'עמודה ישראל – כותרת', 'beckwealth' ), 'text', __( 'הנוכחות בישראל', 'beckwealth' ), 'advantage' ),
		'adv_il_tag'        => array( __( 'עמודה ישראל – תווית לועזית', 'beckwealth' ), 'text', 'TEL AVIV', 'advantage' ),
		'adv_il_points'     => array( __( 'עמודה ישראל – נקודות (מודגש|טקסט)', 'beckwealth' ), 'textarea', "איש קשר בעברית.|באזור הזמן שלכם, זמין כשצריך אותו.\nמיסוי ורגולציה מקומית.|ההיכרות עם הצד הישראלי היא חלק מהשירות.\nפגישות פנים אל פנים.|בתל אביב, לא בציריך.", 'advantage' ),
		'adv_link1_label'   => array( __( 'קישור 1 – טקסט', 'beckwealth' ), 'text', __( 'לעמוד היתרון השוויצרי המלא', 'beckwealth' ), 'advantage' ),
		'adv_link1_url'     => array( __( 'קישור 1 – כתובת', 'beckwealth' ), 'url', '#advantage', 'advantage' ),
		'adv_link2_label'   => array( __( 'קישור 2 – טקסט', 'beckwealth' ), 'text', __( 'ליצירת קשר', 'beckwealth' ), 'advantage' ),
		'adv_link2_url'     => array( __( 'קישור 2 – כתובת', 'beckwealth' ), 'url', '#contact', 'advantage' ),

		/* ---------- מחלקות ---------- */
		'dept_kicker'       => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'השירותים', 'beckwealth' ), 'departments' ),
		'dept_title'        => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'שלוש מחלקות. כתובת אחת.', 'beckwealth' ), 'departments' ),
		'dept_link_label'   => array( __( 'טקסט קישור בכרטיס', 'beckwealth' ), 'text', __( 'לעמוד המחלקה ←', 'beckwealth' ), 'departments' ),

		/* ---------- שני המשרדים ---------- */
		'off_kicker'        => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'שיתוף הפעולה', 'beckwealth' ), 'offices' ),
		'off_title'         => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'שני משרדים. תיק אחד.', 'beckwealth' ), 'offices' ),
		'off_text'          => array( __( 'טקסט פתיחה', 'beckwealth' ), 'textarea', __( 'ההון מוחזק ומנוהל בציריך, תחת פיקוח שוויצרי. ההיכרות עם המשפחה, התכנון והפגישות נעשים מתל אביב. שני המשרדים עובדים על אותו תיק, עם אותם נתונים ואותה מדיניות.', 'beckwealth' ), 'offices' ),
		'off_ch_title'      => array( __( 'ציריך – כותרת', 'beckwealth' ), 'text', __( 'המשרד בציריך', 'beckwealth' ), 'offices' ),
		'off_ch_text'       => array( __( 'ציריך – טקסט', 'beckwealth' ), 'textarea', __( 'החזקת הנכסים, הקשר מול הבנק השוויצרי, ניהול ההשקעות והנאמנויות. כל מה שדורש נוכחות בתוך המערכת השוויצרית ופיקוח FINMA.', 'beckwealth' ), 'offices' ),
		'off_ch_image'      => array( __( 'ציריך – תמונה (16:9)', 'beckwealth' ), 'image', 0, 'offices' ),
		'off_il_title'      => array( __( 'תל אביב – כותרת', 'beckwealth' ), 'text', __( 'המשרד בתל אביב', 'beckwealth' ), 'offices' ),
		'off_il_text'       => array( __( 'תל אביב – טקסט', 'beckwealth' ), 'textarea', __( 'ההיכרות עם המשפחה, תכנון המס הישראלי, הפגישות והדיווח השוטף. הכתובת שאליה פונים, בעברית ובשעות שנוחות לכם.', 'beckwealth' ), 'offices' ),
		'off_il_image'      => array( __( 'תל אביב – תמונה (16:9)', 'beckwealth' ), 'image', 0, 'offices' ),
		'off_connector'     => array( __( 'מחבר – תווית', 'beckwealth' ), 'text', __( 'תיק אחד', 'beckwealth' ), 'offices' ),
		'off_connector_note' => array( __( 'מחבר – הערה (שתי שורות מופרדות ב-|)', 'beckwealth' ), 'text', __( '3,000 ק״מ|אזור זמן אחד הפרש', 'beckwealth' ), 'offices' ),
		'off_principles'    => array( __( 'שלושה עקרונות (שורה לכל אחד: כותרת|טקסט)', 'beckwealth' ), 'textarea', "איש קשר אחד|אתם מדברים עם מנהל התיק בתל אביב. הוא זה שמדבר עם ציריך, לא אתם.\nאותם נתונים בשני הצדדים|מערכת דיווח משותפת. מה שרואים בציריך רואים גם בתל אביב, באותו יום.\nהחלטה משותפת|שינוי מהותי בתיק עובר את שני הצוותים לפני ביצוע, ומתועד בכתב.", 'offices' ),

		/* ---------- ציטוט ---------- */
		'quote_text'        => array( __( 'ציטוט', 'beckwealth' ), 'textarea', __( '״הון עובר מדור לדור רק אם מישהו מלווה אותו בדרך. זה התפקיד שלנו.״', 'beckwealth' ), 'quote' ),
		'quote_author'      => array( __( 'מקור הציטוט', 'beckwealth' ), 'text', __( 'דניאל בק, שותף מנהל · ציריך', 'beckwealth' ), 'quote' ),
		'quote_image'       => array( __( 'תמונת רקע רחבה (21:9)', 'beckwealth' ), 'image', 0, 'quote' ),

		/* ---------- העברה בין-דורית ---------- */
		'legacy_kicker'     => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'העברה בין־דורית', 'beckwealth' ), 'legacy' ),
		'legacy_title'      => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'מה יקרה להון המשפחה אחרי 120?', 'beckwealth' ), 'legacy' ),
		'legacy_text'       => array( __( 'טקסט', 'beckwealth' ), 'textarea', __( 'רוב המריבות בין יורשים נולדות מחוסר בהירות, לא מחוסר כסף. שלוש השאלות האלה עולות כמעט בכל משפחה, ואת שלושתן אפשר להסדיר מראש.', 'beckwealth' ), 'legacy' ),
		'legacy_btn_label'  => array( __( 'כפתור – טקסט', 'beckwealth' ), 'text', __( 'לשיחה על תכנון ההעברה', 'beckwealth' ), 'legacy' ),
		'legacy_btn_url'    => array( __( 'כפתור – קישור', 'beckwealth' ), 'url', '#contact', 'legacy' ),
		'legacy_questions'  => array( __( 'שאלות (שורה לכל שאלה)', 'beckwealth' ), 'textarea', "איך מחלקים את הנכסים בין הילדים, בלי ליצור קרע ביניהם?\nכמה מס תשלם המשפחה על ההעברה, ומה אפשר לתכנן כבר היום?\nמי ימשיך להוביל את העסק המשפחתי, ומה יקבלו אלה שלא?", 'legacy' ),

		/* ---------- איך זה עובד ---------- */
		'proc_kicker'       => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'התהליך', 'beckwealth' ), 'process' ),
		'proc_title'        => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'איך זה עובד', 'beckwealth' ), 'process' ),
		'proc_steps'        => array( __( 'שלבים (שורה לכל שלב: כותרת|טקסט)', 'beckwealth' ), 'textarea', "שיחת היכרות|שיחה חסויה של 30 דקות, בזום או בטלפון. בלי התחייבות ובלי מצגות מכירה.\nתמונת מצב|מיפוי הנכסים, הצרכים והמטרות של המשפחה. לרוב שבועיים.\nמבנה והצעה|הצעת מבנה ומדיניות השקעה מותאמת, בכתב ובשקיפות מלאה על עלויות.\nליווי שוטף|צוות קבוע בישראל ובציריך, דיווח סדור ופגישות תקופתיות.", 'process' ),
		'proc_image_1'      => array( __( 'שלב 1 – תמונה (4:3)', 'beckwealth' ), 'image', 0, 'process' ),
		'proc_image_2'      => array( __( 'שלב 2 – תמונה (4:3)', 'beckwealth' ), 'image', 0, 'process' ),
		'proc_image_3'      => array( __( 'שלב 3 – תמונה (4:3)', 'beckwealth' ), 'image', 0, 'process' ),
		'proc_image_4'      => array( __( 'שלב 4 – תמונה (4:3)', 'beckwealth' ), 'image', 0, 'process' ),
		'proc_cta_title'    => array( __( 'שורת קריאה לפעולה – כותרת', 'beckwealth' ), 'text', __( 'מתלבטים? נתחיל בשיחה.', 'beckwealth' ), 'process' ),
		'proc_cta_phone'    => array( __( 'שורת קריאה לפעולה – טקסט ליד הטלפון', 'beckwealth' ), 'text', __( 'קו ישיר', 'beckwealth' ), 'process' ),
		'proc_cta_btn'      => array( __( 'שורת קריאה לפעולה – כפתור', 'beckwealth' ), 'text', __( 'תיאום שיחת היכרות', 'beckwealth' ), 'process' ),

		/* ---------- צוות ---------- */
		'team_kicker'       => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'הצוות', 'beckwealth' ), 'team' ),
		'team_title'        => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'האנשים', 'beckwealth' ), 'team' ),
		'team_sub'          => array( __( 'טקסט משני', 'beckwealth' ), 'text', __( 'הפעילות בישראל · השותפים בציריך', 'beckwealth' ), 'team' ),
		'team_link_label'   => array( __( 'קישור – טקסט', 'beckwealth' ), 'text', __( 'לעמוד הצוות המלא', 'beckwealth' ), 'team' ),
		'team_link_url'     => array( __( 'קישור – כתובת (ריק = עוגן)', 'beckwealth' ), 'url', '', 'team' ),

		/* ---------- בלוג ---------- */
		'blog_kicker'       => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'ידע ותובנות', 'beckwealth' ), 'blog' ),
		'blog_title'        => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'מהבלוג', 'beckwealth' ), 'blog' ),
		'blog_all_label'    => array( __( 'קישור לכל הכתבות – טקסט', 'beckwealth' ), 'text', __( 'לכל הכתבות ←', 'beckwealth' ), 'blog' ),
		'news_title'        => array( __( 'ניוזלטר – כותרת', 'beckwealth' ), 'text', __( 'הרשמה לניוזלטר', 'beckwealth' ), 'blog' ),
		'news_note'         => array( __( 'ניוזלטר – הערה', 'beckwealth' ), 'text', __( 'אחת לחודש, בלי ספאם. הסרה בכל רגע.', 'beckwealth' ), 'blog' ),

		/* ---------- שאלות ---------- */
		'faq_kicker'        => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'שאלות נפוצות', 'beckwealth' ), 'faq' ),
		'faq_title'         => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'שאלות ותשובות', 'beckwealth' ), 'faq' ),

		/* ---------- יצירת קשר ---------- */
		'contact_kicker'    => array( __( 'שורה עליונה', 'beckwealth' ), 'text', __( 'יצירת קשר', 'beckwealth' ), 'contact_section' ),
		'contact_title'     => array( __( 'כותרת', 'beckwealth' ), 'text', __( 'השיחה הראשונה לא עולה כלום, ושווה הרבה.', 'beckwealth' ), 'contact_section' ),
		'contact_text'      => array( __( 'טקסט', 'beckwealth' ), 'textarea', __( 'ממלאים את הטופס, ואנחנו חוזרים אליכם בתוך יום עסקים לתיאום שיחת היכרות חסויה.', 'beckwealth' ), 'contact_section' ),
		'contact_image'     => array( __( 'תמונה (16:10)', 'beckwealth' ), 'image', 0, 'contact_section' ),
		'contact_wealth'    => array( __( 'אפשרויות "היקף הון" (שורה לכל אפשרות)', 'beckwealth' ), 'textarea', "עד 1M$\n1–5M$\n5–20M$\nמעל 20M$\nמעדיפים לא לציין", 'contact_section' ),
		'contact_subjects'  => array( __( 'נושאי פנייה נוספים (מעבר לשירותים; שורה לכל נושא)', 'beckwealth' ), 'textarea', "תכנון העברה בין־דורית\nאחר", 'contact_section' ),
		'contact_news_label' => array( __( 'תיבת ניוזלטר – טקסט', 'beckwealth' ), 'text', __( 'אשמח לקבל מכם ניוזלטר חודשי (לא חובה)', 'beckwealth' ), 'contact_section' ),
		'contact_submit'    => array( __( 'כפתור שליחה – טקסט', 'beckwealth' ), 'text', __( 'שליחה', 'beckwealth' ), 'contact_section' ),
		'contact_wa_label'  => array( __( 'כפתור וואטסאפ – טקסט', 'beckwealth' ), 'text', __( 'וואטסאפ · מענה מיידי', 'beckwealth' ), 'contact_section' ),
		'contact_wa_msg'    => array( __( 'הודעת וואטסאפ מוכנה מראש', 'beckwealth' ), 'text', __( 'שלום, אשמח לתאם שיחת היכרות', 'beckwealth' ), 'contact_section' ),

		/* ---------- פוטר ---------- */
		'footer_cities'     => array( __( 'שורת ערים', 'beckwealth' ), 'text', __( 'ציריך · תל אביב', 'beckwealth' ), 'footer' ),
		'footer_il_title'   => array( __( 'עמודה 1 – כותרת', 'beckwealth' ), 'text', __( 'משרד ישראל', 'beckwealth' ), 'footer' ),
		'footer_il_text'    => array( __( 'עמודה 1 – טקסט (שורות)', 'beckwealth' ), 'textarea', "מגדל אלון 2, תל אביב\n03-000-0000\nisrael@beckwealth.ch", 'footer' ),
		'footer_ch_title'   => array( __( 'עמודה 2 – כותרת', 'beckwealth' ), 'text', __( 'משרד ציריך', 'beckwealth' ), 'footer' ),
		'footer_ch_text'    => array( __( 'עמודה 2 – טקסט (שורות)', 'beckwealth' ), 'textarea', "Bahnhofstrasse 00, Zürich\n+41 00 000 00 00", 'footer' ),
		'footer_nav_title'  => array( __( 'עמודה 3 – כותרת (תפריט פוטר)', 'beckwealth' ), 'text', __( 'ניווט', 'beckwealth' ), 'footer' ),
		'footer_legal_title' => array( __( 'עמודה 4 – כותרת (תפריט משפטי)', 'beckwealth' ), 'text', __( 'משפטי', 'beckwealth' ), 'footer' ),
		'footer_disclaimer' => array( __( 'הבהרה משפטית', 'beckwealth' ), 'textarea', __( 'בק וולת׳ (ישראל) בע״מ פועלת בשיתוף BeckWealth AG, ציריך, המפוקחת על ידי הרגולטור השוויצרי (FINMA). הנכסים מוחזקים בחשבונות על שם הלקוח בבנקים שוויצריים. אין באמור באתר זה משום ייעוץ השקעות, ייעוץ מס או שיווק השקעות כהגדרתם בחוק, ואין בו תחליף לייעוץ אישי המותאם לצרכיו של כל אדם. נוסח סופי באישור עו״ד.', 'beckwealth' ), 'footer' ),
		'footer_watermark'  => array( __( 'לוגו ענק שקוף בתחתית', 'beckwealth' ), 'checkbox', true, 'footer' ),
	);

	return $fields;
}

/**
 * סקשנים בקסטומייזר: id => כותרת.
 *
 * @return array<string, string>
 */
function beckwealth_home_sections(): array {
	return array(
		'behavior'        => __( 'התנהגות ואנימציות', 'beckwealth' ),
		'header'          => __( 'כותרת עליונה ושורת "מהשוק"', 'beckwealth' ),
		'hero'            => __( 'דף הבית – הירו', 'beckwealth' ),
		'trust'           => __( 'דף הבית – שורת אמון ומספרים', 'beckwealth' ),
		'swiss'           => __( 'דף הבית – שוויץ', 'beckwealth' ),
		'advantage'       => __( 'דף הבית – היתרון', 'beckwealth' ),
		'departments'     => __( 'דף הבית – מחלקות', 'beckwealth' ),
		'offices'         => __( 'דף הבית – שני המשרדים', 'beckwealth' ),
		'quote'           => __( 'דף הבית – ציטוט', 'beckwealth' ),
		'legacy'          => __( 'דף הבית – העברה בין-דורית', 'beckwealth' ),
		'process'         => __( 'דף הבית – איך זה עובד', 'beckwealth' ),
		'team'            => __( 'דף הבית – צוות', 'beckwealth' ),
		'blog'            => __( 'דף הבית – בלוג וניוזלטר', 'beckwealth' ),
		'faq'             => __( 'דף הבית – שאלות ותשובות', 'beckwealth' ),
		'contact_section' => __( 'דף הבית – יצירת קשר', 'beckwealth' ),
		'footer'          => __( 'פוטר', 'beckwealth' ),
	);
}

/**
 * קריאת ערך תוכן (theme_mod) עם ברירת המחדל מהעיצוב.
 *
 * @param string $key מפתח (ללא קידומת).
 * @return mixed
 */
function beckwealth_mod( string $key ) {
	$fields  = beckwealth_home_fields();
	$default = $fields[ $key ][2] ?? '';
	return get_theme_mod( 'bw_' . $key, $default );
}

/**
 * פיצול שדה textarea לשורות לא ריקות.
 *
 * @param string $key מפתח.
 * @return string[]
 */
function beckwealth_mod_lines( string $key ): array {
	$raw   = (string) beckwealth_mod( $key );
	$lines = preg_split( '/\r\n|\r|\n/', $raw ) ?: array();
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * פיצול שורות בפורמט "א|ב" לזוגות.
 *
 * @param string $key מפתח.
 * @return array<int, array{0:string,1:string}>
 */
function beckwealth_mod_pairs( string $key ): array {
	$pairs = array();
	foreach ( beckwealth_mod_lines( $key ) as $line ) {
		[ $a, $b ] = array_pad( explode( '|', $line, 2 ), 2, '' );
		$pairs[]   = array( trim( $a ), trim( $b ) );
	}
	return $pairs;
}
