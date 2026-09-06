<?php
/**
 * טופס יצירת קשר + הרשמה לניוזלטר – ללא תוספים.
 * הגנות: nonce, honeypot, זמן מילוי מינימלי, הגבלת קצב, Cloudflare Turnstile (אם הוגדר),
 * ניקוי מלא בצד השרת. הפניות נשמרות במיני-CRM ונשלחות במייל.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * הודעת סטטוס אחרי שליחה (מהפניה חזרה).
 *
 * @param string $context 'contact' | 'news'.
 * @param bool   $light   גרסה בהירה.
 */
function beckwealth_form_notice( string $context = 'contact', bool $light = false ): void {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- דגל תצוגה בלבד.
	$param  = 'news' === $context ? 'newsletter' : 'contact';
	$status = isset( $_GET[ $param ] ) ? sanitize_key( wp_unslash( $_GET[ $param ] ) ) : '';
	// phpcs:enable
	if ( '' === $status ) {
		return;
	}
	$messages = array(
		'success'   => 'news' === $context ? __( 'תודה! נרשמתם לניוזלטר.', 'beckwealth' ) : __( 'תודה! פנייתכם התקבלה ונחזור אליכם בתוך יום עסקים.', 'beckwealth' ),
		'error'     => __( 'אירעה שגיאה בשליחה. אנא בדקו את הפרטים ונסו שוב.', 'beckwealth' ),
		'expired'   => __( 'העמוד היה פתוח זמן רב. אנא רעננו את העמוד ונסו שוב.', 'beckwealth' ),
		'ratelimit' => __( 'נשלחו יותר מדי פניות. אנא נסו שוב בעוד מספר דקות.', 'beckwealth' ),
		'captcha'   => __( 'אימות האבטחה נכשל. אנא נסו שוב.', 'beckwealth' ),
	);
	if ( ! isset( $messages[ $status ] ) ) {
		return;
	}
	$is_ok = 'success' === $status;
	printf(
		'<div class="form-notice%1$s%2$s" role="%3$s" tabindex="-1">%4$s</div>',
		$is_ok ? '' : ' form-notice--error',
		$light ? ' form-notice--light' : '',
		$is_ok ? 'status' : 'alert',
		esc_html( $messages[ $status ] )
	);
}

/**
 * רשימת נושאי הפנייה: השירותים + נושאים נוספים מהקסטומייזר.
 *
 * @return string[]
 */
function beckwealth_contact_subjects(): array {
	$subjects = array();
	$services = get_posts(
		array(
			'post_type'              => 'service',
			'posts_per_page'         => 10,
			'orderby'                => 'menu_order title',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	foreach ( $services as $service ) {
		$subjects[] = $service->post_title;
	}
	return array_values( array_unique( array_merge( $subjects, beckwealth_mod_lines( 'contact_subjects' ) ) ) );
}

/**
 * הדפסת טופס יצירת הקשר.
 *
 * @param array $args light (bool) – גרסה בהירה לעמודים פנימיים; title (string).
 */
function beckwealth_contact_form( array $args = array() ): void {
	$args  = wp_parse_args( $args, array( 'light' => false, 'title' => '' ) );
	$light = (bool) $args['light'];
	$uid   = wp_unique_id( 'bw-' );

	beckwealth_enqueue_contact_form_assets();
	?>
	<div class="contact-form-wrap" id="contact-form">
		<?php if ( $args['title'] ) : ?>
			<h2 class="contact-form-title"><?php echo esc_html( $args['title'] ); ?></h2>
		<?php endif; ?>
		<?php beckwealth_form_notice( 'contact', $light ); ?>
		<form class="contact-form<?php echo $light ? ' contact-form--light' : ''; ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
			<input type="hidden" name="action" value="beckwealth_contact">
			<input type="hidden" name="bw_redirect" value="<?php echo esc_url( remove_query_arg( array( 'contact', 'newsletter' ), get_permalink() ?: home_url( '/' ) ) ); ?>">
			<?php wp_nonce_field( 'beckwealth_contact', 'bw_nonce' ); ?>
			<input type="hidden" name="bw_ts" value="<?php echo esc_attr( (string) time() ); ?>">

			<div class="contact-form__row">
				<div class="form-field">
					<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-name"><?php esc_html_e( 'שם מלא', 'beckwealth' ); ?></label>
					<input class="bw-input" type="text" id="<?php echo esc_attr( $uid ); ?>-name" name="bw_name" placeholder="<?php esc_attr_e( 'שם מלא', 'beckwealth' ); ?>" required autocomplete="name" maxlength="100">
				</div>
				<div class="form-field">
					<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-phone"><?php esc_html_e( 'טלפון', 'beckwealth' ); ?></label>
					<input class="bw-input" type="tel" id="<?php echo esc_attr( $uid ); ?>-phone" name="bw_phone" placeholder="<?php esc_attr_e( 'טלפון', 'beckwealth' ); ?>" required autocomplete="tel" inputmode="tel" maxlength="30" pattern="[0-9+\-\s()]{7,20}">
				</div>
				<div class="form-field">
					<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-email"><?php esc_html_e( 'אימייל', 'beckwealth' ); ?></label>
					<input class="bw-input" type="email" id="<?php echo esc_attr( $uid ); ?>-email" name="bw_email" placeholder="<?php esc_attr_e( 'אימייל', 'beckwealth' ); ?>" autocomplete="email" maxlength="150">
				</div>
				<div class="form-field">
					<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-wealth"><?php esc_html_e( 'היקף הון', 'beckwealth' ); ?></label>
					<select class="bw-input" id="<?php echo esc_attr( $uid ); ?>-wealth" name="bw_wealth">
						<option value=""><?php esc_html_e( 'היקף הון', 'beckwealth' ); ?></option>
						<?php foreach ( beckwealth_mod_lines( 'contact_wealth' ) as $option ) : ?>
							<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-field">
				<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>-subject"><?php esc_html_e( 'נושא הפנייה', 'beckwealth' ); ?></label>
				<select class="bw-input" id="<?php echo esc_attr( $uid ); ?>-subject" name="bw_subject">
					<option value=""><?php esc_html_e( 'נושא הפנייה', 'beckwealth' ); ?></option>
					<?php foreach ( beckwealth_contact_subjects() as $subject ) : ?>
						<option value="<?php echo esc_attr( $subject ); ?>"><?php echo esc_html( $subject ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<label class="bw-check">
				<input type="checkbox" name="bw_newsletter" value="1">
				<?php echo esc_html( beckwealth_mod( 'contact_news_label' ) ); ?>
			</label>

			<?php /* Honeypot – מוסתר ממשתמשים; בוטים ימלאו אותו. */ ?>
			<div class="bw-hp" aria-hidden="true">
				<label for="<?php echo esc_attr( $uid ); ?>-website">Website</label>
				<input type="text" id="<?php echo esc_attr( $uid ); ?>-website" name="bw_website" tabindex="-1" autocomplete="off">
			</div>

			<?php beckwealth_turnstile_widget(); ?>

			<div class="contact-form__actions">
				<button type="submit" class="bw-btn bw-btn--submit"><?php echo esc_html( beckwealth_mod( 'contact_submit' ) ); ?><span class="bw-dia bw-dia--6 bw-dia--cur" aria-hidden="true"></span></button>
				<?php
				$whatsapp = beckwealth_contact_details()['whatsapp'];
				if ( $whatsapp && beckwealth_mod( 'show_whatsapp' ) ) :
					?>
					<a class="bw-btn-outline" href="<?php echo esc_url( beckwealth_whatsapp_href( $whatsapp, (string) beckwealth_mod( 'contact_wa_msg' ) ) ); ?>" target="_blank" rel="noopener noreferrer"><span class="bw-dia bw-dia--5" aria-hidden="true"></span><?php echo esc_html( beckwealth_mod( 'contact_wa_label' ) ); ?></a>
				<?php endif; ?>
			</div>
			<p class="contact__consent">
				<?php
				$policy = get_privacy_policy_url();
				if ( $policy ) {
					printf(
						/* translators: %s: privacy policy link */
						esc_html__( 'בשליחת הטופס אתם מאשרים יצירת קשר בהתאם ל%s.', 'beckwealth' ),
						'<a href="' . esc_url( $policy ) . '">' . esc_html__( 'מדיניות הפרטיות', 'beckwealth' ) . '</a>'
					);
				} else {
					esc_html_e( 'בשליחת הטופס אתם מאשרים יצירת קשר בהתאם למדיניות הפרטיות.', 'beckwealth' );
				}
				?>
			</p>
			<p class="form-live screen-reader-text" aria-live="polite"></p>
		</form>
	</div>
	<?php
}

/**
 * טופס הרשמה לניוזלטר (מקטע הבלוג).
 */
function beckwealth_newsletter_form(): void {
	$uid = wp_unique_id( 'bw-news-' );
	?>
	<div class="newsletter">
		<p class="newsletter__title"><?php echo esc_html( beckwealth_mod( 'news_title' ) ); ?></p>
		<?php beckwealth_form_notice( 'news', true ); ?>
		<form class="newsletter__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="beckwealth_newsletter">
			<input type="hidden" name="bw_redirect" value="<?php echo esc_url( remove_query_arg( array( 'contact', 'newsletter' ), get_permalink() ?: home_url( '/' ) ) ); ?>">
			<?php wp_nonce_field( 'beckwealth_newsletter', 'bw_news_nonce' ); ?>
			<input type="hidden" name="bw_ts" value="<?php echo esc_attr( (string) time() ); ?>">
			<div class="bw-hp" aria-hidden="true"><label for="<?php echo esc_attr( $uid ); ?>-w">Website</label><input type="text" id="<?php echo esc_attr( $uid ); ?>-w" name="bw_website" tabindex="-1" autocomplete="off"></div>
			<label class="screen-reader-text" for="<?php echo esc_attr( $uid ); ?>"><?php esc_html_e( 'כתובת אימייל', 'beckwealth' ); ?></label>
			<input class="bw-input bw-input--light" type="email" id="<?php echo esc_attr( $uid ); ?>" name="bw_email" placeholder="<?php esc_attr_e( 'כתובת אימייל', 'beckwealth' ); ?>" required autocomplete="email" maxlength="150">
			<button type="submit" class="bw-btn bw-btn--news"><?php esc_html_e( 'הרשמה', 'beckwealth' ); ?></button>
		</form>
		<p class="newsletter__note"><?php echo esc_html( beckwealth_mod( 'news_note' ) ); ?></p>
	</div>
	<?php
}

/**
 * כתובת הפניה בטוחה (רק לדומיין של האתר).
 *
 * @return string
 */
function beckwealth_safe_redirect_target(): string {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- נבדק בפונקציה הקוראת.
	$redirect = isset( $_POST['bw_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['bw_redirect'] ) ) : '';
	if ( '' === $redirect || wp_parse_url( $redirect, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		$redirect = home_url( '/' );
	}
	return $redirect;
}

/**
 * הגבלת קצב לפי IP (transient). מחזיר false אם חרג.
 *
 * @param string $bucket שם.
 * @param int    $limit  מקסימום לשעה.
 * @return bool
 */
function beckwealth_rate_limit( string $bucket, int $limit = 5 ): bool {
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	$key  = 'bw_rl_' . $bucket . '_' . md5( $ip );
	$hits = (int) get_transient( $key );
	if ( $hits >= $limit ) {
		return false;
	}
	set_transient( $key, $hits + 1, HOUR_IN_SECONDS );
	return true;
}

/**
 * בדיקות אנטי-ספאם משותפות: honeypot, זמן מילוי, Turnstile.
 * מחזיר קוד שגיאה או '' אם תקין. 'silent' = בוט – מחזירים "הצלחה" בלי לשמור.
 *
 * @return string
 */
function beckwealth_spam_checks(): string {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- ה-nonce נבדק לפני הקריאה.
	if ( ! empty( $_POST['bw_website'] ) ) {
		return 'silent';
	}
	$ts = isset( $_POST['bw_ts'] ) ? absint( $_POST['bw_ts'] ) : 0;
	if ( ! $ts || ( time() - $ts ) < 3 ) {
		return 'silent';
	}
	$token = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) ) : '';
	// phpcs:enable
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( ! beckwealth_turnstile_verify( $token, $ip ) ) {
		return 'captcha';
	}
	return '';
}

/**
 * טיפול בשליחת טופס יצירת קשר.
 */
function beckwealth_handle_contact(): void {
	$redirect = beckwealth_safe_redirect_target();
	$fail     = static function ( string $code ) use ( $redirect ): void {
		wp_safe_redirect( add_query_arg( 'contact', $code, $redirect ) . '#contact-form' );
		exit;
	};

	if ( ! isset( $_POST['bw_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bw_nonce'] ), 'beckwealth_contact' ) ) {
		$fail( 'expired' );
	}

	$spam = beckwealth_spam_checks();
	if ( 'silent' === $spam ) {
		$fail( 'success' );
	}
	if ( '' !== $spam ) {
		$fail( $spam );
	}
	if ( ! beckwealth_rate_limit( 'contact', 5 ) ) {
		$fail( 'ratelimit' );
	}

	$name       = isset( $_POST['bw_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bw_name'] ) ) : '';
	$phone      = isset( $_POST['bw_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['bw_phone'] ) ) : '';
	$email      = isset( $_POST['bw_email'] ) ? sanitize_email( wp_unslash( $_POST['bw_email'] ) ) : '';
	$wealth     = isset( $_POST['bw_wealth'] ) ? sanitize_text_field( wp_unslash( $_POST['bw_wealth'] ) ) : '';
	$subject    = isset( $_POST['bw_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['bw_subject'] ) ) : '';
	$message    = isset( $_POST['bw_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['bw_message'] ) ) : '';
	$newsletter = ! empty( $_POST['bw_newsletter'] );

	if ( '' === $name || '' === $phone || ! preg_match( '/^[0-9+\-\s()]{7,20}$/', $phone ) ) {
		$fail( 'error' );
	}
	if ( '' !== $email && ! is_email( $email ) ) {
		$fail( 'error' );
	}
	// מאפשרים רק ערכים מהרשימות המוגדרות.
	if ( '' !== $wealth && ! in_array( $wealth, beckwealth_mod_lines( 'contact_wealth' ), true ) ) {
		$wealth = '';
	}
	if ( '' !== $subject && ! in_array( $subject, beckwealth_contact_subjects(), true ) ) {
		$subject = '';
	}

	$lead = array(
		'name'       => $name,
		'phone'      => $phone,
		'email'      => $email,
		'wealth'     => $wealth,
		'subject'    => $subject,
		'message'    => $message,
		'newsletter' => $newsletter,
		'page'       => $redirect,
	);

	$lead_id = beckwealth_create_lead( $lead );

	/**
	 * מאפשר ל-CRM חיצוני לקבל את הליד.
	 *
	 * @param array $lead    נתוני הליד.
	 * @param int   $lead_id מזהה הליד במיני-CRM (0 אם השמירה נכשלה).
	 */
	do_action( 'beckwealth_contact_lead', $lead, $lead_id );

	$sent = beckwealth_send_lead_email( $lead );
	if ( ! $sent && $lead_id ) {
		// הליד נשמר במערכת; כשל במייל נרשם ללוג השרת בלבד ואינו נחשף לגולש.
		error_log( sprintf( 'Beck Wealth: lead #%d saved but notification email failed.', $lead_id ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
	$fail( ( $sent || $lead_id ) ? 'success' : 'error' );
}
add_action( 'admin_post_nopriv_beckwealth_contact', 'beckwealth_handle_contact' );
add_action( 'admin_post_beckwealth_contact', 'beckwealth_handle_contact' );

/**
 * שליחת מייל התראה על ליד.
 *
 * @param array $lead ליד.
 * @return bool
 */
function beckwealth_send_lead_email( array $lead ): bool {
	$to = (string) beckwealth_get_setting( 'lead_notify_email', '' ) ?: beckwealth_contact_details()['email'] ?: get_option( 'admin_email' );

	$lines = array(
		__( 'התקבלה פנייה חדשה מאתר', 'beckwealth' ) . ' ' . get_bloginfo( 'name' ),
		'',
		__( 'שם:', 'beckwealth' ) . ' ' . $lead['name'],
		__( 'טלפון:', 'beckwealth' ) . ' ' . $lead['phone'],
		__( 'אימייל:', 'beckwealth' ) . ' ' . ( $lead['email'] ?: '-' ),
		__( 'היקף הון:', 'beckwealth' ) . ' ' . ( $lead['wealth'] ?: '-' ),
		__( 'נושא:', 'beckwealth' ) . ' ' . ( $lead['subject'] ?: '-' ),
		__( 'ניוזלטר:', 'beckwealth' ) . ' ' . ( $lead['newsletter'] ? __( 'כן', 'beckwealth' ) : __( 'לא', 'beckwealth' ) ),
	);
	if ( $lead['message'] ) {
		$lines[] = '';
		$lines[] = __( 'הודעה:', 'beckwealth' );
		$lines[] = $lead['message'];
	}
	$lines[] = '';
	$lines[] = '---';
	$lines[] = __( 'עמוד:', 'beckwealth' ) . ' ' . $lead['page'];
	$lines[] = __( 'תאריך:', 'beckwealth' ) . ' ' . wp_date( 'd/m/Y H:i' );
	$lines[] = __( 'לניהול הלידים:', 'beckwealth' ) . ' ' . admin_url( 'edit.php?post_type=bw_lead' );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $lead['email'] ) {
		$headers[] = 'Reply-To: ' . $lead['name'] . ' <' . $lead['email'] . '>';
	}
	$subject = sprintf(
		/* translators: 1: site name, 2: sender name */
		__( '[%1$s] פנייה חדשה מ-%2$s', 'beckwealth' ),
		get_bloginfo( 'name' ),
		$lead['name']
	);
	return (bool) wp_mail( $to, $subject, implode( "\n", $lines ), $headers );
}

/**
 * טיפול בהרשמה לניוזלטר.
 */
function beckwealth_handle_newsletter(): void {
	$redirect = beckwealth_safe_redirect_target();
	$fail     = static function ( string $code ) use ( $redirect ): void {
		wp_safe_redirect( add_query_arg( 'newsletter', $code, $redirect ) . '#blog' );
		exit;
	};

	if ( ! isset( $_POST['bw_news_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bw_news_nonce'] ), 'beckwealth_newsletter' ) ) {
		$fail( 'expired' );
	}
	$spam = beckwealth_spam_checks();
	if ( 'silent' === $spam ) {
		$fail( 'success' );
	}
	if ( 'captcha' === $spam && beckwealth_turnstile_enabled() && ! isset( $_POST['cf-turnstile-response'] ) ) {
		$spam = ''; // טופס הניוזלטר אינו מציג Turnstile; מסתמכים על honeypot והגבלת קצב.
	}
	if ( '' !== $spam ) {
		$fail( $spam );
	}
	if ( ! beckwealth_rate_limit( 'news', 5 ) ) {
		$fail( 'ratelimit' );
	}
	$email = isset( $_POST['bw_email'] ) ? sanitize_email( wp_unslash( $_POST['bw_email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		$fail( 'error' );
	}

	$lead = array(
		'name'       => $email,
		'phone'      => '',
		'email'      => $email,
		'wealth'     => '',
		'subject'    => __( 'הרשמה לניוזלטר', 'beckwealth' ),
		'message'    => '',
		'newsletter' => true,
		'page'       => $redirect,
	);
	beckwealth_create_lead( $lead );

	/**
	 * הרשמה לניוזלטר – חיבור למערכת דיוור.
	 *
	 * @param string $email אימייל.
	 */
	do_action( 'beckwealth_newsletter_signup', $email );

	$fail( 'success' );
}
add_action( 'admin_post_nopriv_beckwealth_newsletter', 'beckwealth_handle_newsletter' );
add_action( 'admin_post_beckwealth_newsletter', 'beckwealth_handle_newsletter' );
