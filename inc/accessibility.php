<?php
/**
 * נגישות: סרגל נגישות, הצהרת נגישות (ת"י 5568 / WCAG 2.2 AA).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * כתובת עמוד הצהרת הנגישות.
 *
 * @return string
 */
function beckwealth_a11y_statement_url(): string {
	$page_id = (int) beckwealth_get_setting( 'a11y_statement_page', 0 );
	if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
		return (string) get_permalink( $page_id );
	}
	return '';
}

/**
 * כפתור + פאנל סרגל הנגישות (מודפס ב-footer; ה-JS נטען עצל).
 */
function beckwealth_a11y_toolbar(): void {
	if ( ! beckwealth_get_setting( 'a11y_toolbar', '1' ) ) {
		return;
	}
	$buttons = array(
		array( 'font-up', __( 'הגדלת טקסט', 'beckwealth' ), 'plus' ),
		array( 'font-down', __( 'הקטנת טקסט', 'beckwealth' ), 'minus' ),
		array( 'contrast', __( 'ניגודיות גבוהה', 'beckwealth' ), 'contrast' ),
		array( 'invert', __( 'היפוך צבעים', 'beckwealth' ), 'invert' ),
		array( 'grayscale', __( 'גווני אפור', 'beckwealth' ), 'grayscale' ),
		array( 'underline', __( 'הדגשת קישורים בקו', 'beckwealth' ), 'underline' ),
		array( 'readable', __( 'גופן קריא', 'beckwealth' ), 'font' ),
		array( 'no-motion', __( 'עצירת אנימציות', 'beckwealth' ), 'pause' ),
		array( 'headings', __( 'הדגשת כותרות', 'beckwealth' ), 'heading' ),
		array( 'links', __( 'הדגשת קישורים', 'beckwealth' ), 'link' ),
		array( 'guide', __( 'סרגל קריאה', 'beckwealth' ), 'guide' ),
		array( 'keyboard', __( 'ניווט מקלדת מודגש', 'beckwealth' ), 'keyboard' ),
	);
	$statement = beckwealth_a11y_statement_url();
	?>
	<button type="button" id="a11y-toggle" class="a11y-toggle" aria-controls="a11y-panel" aria-expanded="false" aria-label="<?php esc_attr_e( 'פתיחת תפריט נגישות', 'beckwealth' ); ?>">
		<?php echo beckwealth_icon( 'a11y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
	</button>
	<div id="a11y-panel" class="a11y-panel" role="dialog" aria-modal="true" aria-labelledby="a11y-panel-title" hidden>
		<div class="a11y-panel__header">
			<h2 id="a11y-panel-title"><?php esc_html_e( 'הגדרות נגישות', 'beckwealth' ); ?></h2>
			<button type="button" class="a11y-panel__close" aria-label="<?php esc_attr_e( 'סגירת תפריט נגישות', 'beckwealth' ); ?>">
				<?php echo beckwealth_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
			</button>
		</div>
		<div class="a11y-panel__body">
			<p class="a11y-panel__group"><?php esc_html_e( 'גודל טקסט', 'beckwealth' ); ?> (<span data-a11y-size aria-live="polite">0</span>)</p>
			<?php foreach ( $buttons as [ $key, $label, $icon ] ) : ?>
				<?php $is_toggle = ! in_array( $key, array( 'font-up', 'font-down' ), true ); ?>
				<button type="button" class="a11y-btn" data-a11y="<?php echo esc_attr( $key ); ?>"<?php echo $is_toggle ? ' aria-pressed="false"' : ''; ?>>
					<?php echo beckwealth_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					<span><?php echo esc_html( $label ); ?></span>
				</button>
				<?php if ( 'font-down' === $key ) : ?>
					<p class="a11y-panel__group"><?php esc_html_e( 'תצוגה', 'beckwealth' ); ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
			<button type="button" class="a11y-btn a11y-btn--wide" data-a11y="reset">
				<?php echo beckwealth_icon( 'reset' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				<span><?php esc_html_e( 'איפוס הגדרות', 'beckwealth' ); ?></span>
			</button>
		</div>
		<div class="a11y-panel__footer">
			<?php if ( $statement ) : ?>
				<a href="<?php echo esc_url( $statement ); ?>"><?php esc_html_e( 'הצהרת נגישות', 'beckwealth' ); ?></a>
			<?php endif; ?>
			<p style="margin:.5rem 0 0;font-size:.8rem;color:#5b6472"><?php esc_html_e( 'ההעדפות נשמרות בדפדפן שלכם בלבד.', 'beckwealth' ); ?></p>
		</div>
	</div>
	<div id="a11y-guide" class="a11y-guide" aria-hidden="true" hidden></div>
	<?php
}

/**
 * שורטקוד לפרטי רכז הנגישות – משמש בעמוד ההצהרה ומתעדכן מההגדרות.
 *
 * @return string
 */
function beckwealth_a11y_contact_shortcode(): string {
	$name  = (string) beckwealth_get_setting( 'a11y_coordinator', '' );
	$phone = (string) beckwealth_get_setting( 'a11y_phone', '' ) ?: beckwealth_contact_details()['phone'];
	$email = (string) beckwealth_get_setting( 'a11y_email', '' ) ?: beckwealth_contact_details()['email'];

	$out = '<ul class="a11y-contact">';
	if ( $name ) {
		$out .= '<li>' . esc_html__( 'רכז/ת נגישות:', 'beckwealth' ) . ' ' . esc_html( $name ) . '</li>';
	}
	if ( $phone ) {
		$out .= '<li>' . esc_html__( 'טלפון:', 'beckwealth' ) . ' <a href="' . esc_attr( beckwealth_tel_href( $phone ) ) . '" dir="ltr">' . esc_html( $phone ) . '</a></li>';
	}
	if ( $email ) {
		$out .= '<li>' . esc_html__( 'אימייל:', 'beckwealth' ) . ' <a href="mailto:' . esc_attr( antispambot( $email ) ) . '" dir="ltr">' . esc_html( antispambot( $email ) ) . '</a></li>';
	}
	$out .= '</ul>';
	return $out;
}
add_shortcode( 'beckwealth_a11y_contact', 'beckwealth_a11y_contact_shortcode' );

/**
 * שורטקוד תאריך עדכון ההצהרה (תאריך העדכון האחרון של העמוד).
 *
 * @return string
 */
function beckwealth_a11y_updated_shortcode(): string {
	return esc_html( get_the_modified_date( 'd/m/Y' ) );
}
add_shortcode( 'beckwealth_a11y_updated', 'beckwealth_a11y_updated_shortcode' );

/**
 * תוכן ברירת מחדל לעמוד הצהרת הנגישות (בלוקים).
 *
 * @return string
 */
function beckwealth_a11y_statement_content(): string {
	$site = get_bloginfo( 'name' );
	$p    = static fn( string $text ): string => '<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph -->';
	$h    = static fn( string $text ): string => '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html( $text ) . '</h2><!-- /wp:heading -->';
	$list = static fn( array $items ): string => '<!-- wp:list --><ul class="wp-block-list">' . implode( '', array_map( static fn( $i ) => '<!-- wp:list-item --><li>' . esc_html( $i ) . '</li><!-- /wp:list-item -->', $items ) ) . '</ul><!-- /wp:list -->';

	return $p( sprintf( /* translators: %s: site name */ esc_html__( '%s רואה חשיבות רבה במתן שירות שוויוני לכלל הלקוחות והגולשים, ובכלל זה אנשים עם מוגבלות. אנו משקיעים משאבים בהנגשת האתר כדי לאפשר גלישה נוחה, יעילה ונעימה לכולם.', 'beckwealth' ), esc_html( $site ) ) )
		. $h( __( 'תקנים והתאמות שבוצעו', 'beckwealth' ) )
		. $p( esc_html__( 'האתר הונגש בהתאם לתקנות שוויון זכויות לאנשים עם מוגבלות (התאמות נגישות לשירות), התשע"ג-2013, לתקן הישראלי ת"י 5568 ולהנחיות WCAG 2.2 ברמה AA של ארגון W3C.', 'beckwealth' ) )
		. $list(
			array(
				__( 'ניווט מלא באמצעות מקלדת, כולל קישור "דלג לתוכן" וסימון פוקוס ברור.', 'beckwealth' ),
				__( 'מבנה HTML סמנטי, היררכיית כותרות תקינה ותוויות לכל שדה טופס.', 'beckwealth' ),
				__( 'טקסט חלופי לתמונות ותיאורים לקוראי מסך (NVDA, JAWS, VoiceOver, TalkBack).', 'beckwealth' ),
				__( 'ניגודיות צבעים העומדת בדרישות התקן.', 'beckwealth' ),
				__( 'סרגל נגישות: הגדלת טקסט, ניגודיות גבוהה, גווני אפור, הדגשת קישורים וכותרות, גופן קריא, עצירת אנימציות וסרגל קריאה.', 'beckwealth' ),
				__( 'תצוגה רספונסיבית המותאמת למחשב, טאבלט וסמארטפון, ותמיכה בהגדלה עד 200%.', 'beckwealth' ),
			)
		)
		. $h( __( 'סרגל הנגישות', 'beckwealth' ) )
		. $p( esc_html__( 'בפינת המסך מופיע כפתור נגישות הפותח תפריט התאמות. ההעדפות נשמרות בדפדפן שלכם ומוחלות בכל עמודי האתר.', 'beckwealth' ) )
		. $h( __( 'הסתייגות', 'beckwealth' ) )
		. $p( esc_html__( 'חרף מאמצינו להנגיש את כלל הדפים באתר, ייתכן שיתגלו חלקים שטרם הונגשו במלואם או שהונגשו בטכנולוגיה שאינה תואמת. אנו ממשיכים במאמצים לשפר את נגישות האתר ומתחייבים לתקן ליקויים בהקדם האפשרי.', 'beckwealth' ) )
		. $h( __( 'פנייה לרכז/ת הנגישות', 'beckwealth' ) )
		. $p( esc_html__( 'נתקלתם בבעיית נגישות? נשמח לקבל פנייה ולטפל בה במהירות:', 'beckwealth' ) )
		. '<!-- wp:shortcode -->[beckwealth_a11y_contact]<!-- /wp:shortcode -->'
		. $p( esc_html__( 'תאריך עדכון ההצהרה: ', 'beckwealth' ) . '[beckwealth_a11y_updated]' );
}

/**
 * יצירת עמוד הצהרת נגישות בהפעלת התבנית (אם לא קיים).
 */
function beckwealth_create_a11y_page(): void {
	$existing = (int) beckwealth_get_setting( 'a11y_statement_page', 0 );
	if ( $existing && get_post( $existing ) ) {
		return;
	}
	$page_id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => __( 'הצהרת נגישות', 'beckwealth' ),
			'post_name'    => 'accessibility-statement',
			'post_content' => beckwealth_a11y_statement_content(),
		)
	);
	if ( $page_id && ! is_wp_error( $page_id ) ) {
		$settings                        = (array) get_option( 'beckwealth_settings', array() );
		$settings['a11y_statement_page'] = (string) $page_id;
		update_option( 'beckwealth_settings', $settings );
	}
}
add_action( 'after_switch_theme', 'beckwealth_create_a11y_page' );
