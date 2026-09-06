<?php
/**
 * דשבורד ייעודי בלוח הבקרה: סקירה, קיצורי עריכה, הגדרות.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * תפריט ניהול ראשי "Beck Wealth".
 */
function beckwealth_admin_menu(): void {
	add_menu_page(
		__( 'Beck Wealth', 'beckwealth' ),
		__( 'Beck Wealth', 'beckwealth' ),
		'edit_pages',
		'beckwealth',
		'beckwealth_render_dashboard',
		'dashicons-chart-area',
		3
	);
	add_submenu_page( 'beckwealth', __( 'סקירה', 'beckwealth' ), __( 'סקירה', 'beckwealth' ), 'edit_pages', 'beckwealth', 'beckwealth_render_dashboard' );
	add_submenu_page( 'beckwealth', __( 'הגדרות אתר', 'beckwealth' ), __( 'הגדרות', 'beckwealth' ), 'manage_options', 'beckwealth-settings', 'beckwealth_render_settings' );
}
add_action( 'admin_menu', 'beckwealth_admin_menu' );

/**
 * קישור לקסטומייזר עם פוקוס על סקשן.
 *
 * @param string $section שם הסקשן.
 * @return string
 */
function beckwealth_customizer_link( string $section ): string {
	return add_query_arg(
		array(
			'autofocus[section]' => $section,
			'return'             => rawurlencode( admin_url( 'admin.php?page=beckwealth' ) ),
		),
		admin_url( 'customize.php' )
	);
}

/**
 * ספירת פריטים לסוג תוכן (wp_count_posts – במטמון אובייקטים).
 *
 * @param string $type סוג תוכן.
 * @return int
 */
function beckwealth_count_published( string $type ): int {
	$counts = wp_count_posts( $type );
	return (int) ( $counts->publish ?? 0 );
}

/**
 * עמוד הסקירה.
 */
function beckwealth_render_dashboard(): void {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	$leads    = beckwealth_lead_counts();
	$statuses = beckwealth_lead_statuses();
	$recent   = get_posts(
		array(
			'post_type'              => 'bw_lead',
			'post_status'            => 'private',
			'posts_per_page'         => 5,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);
	?>
	<div class="wrap bw-admin">
		<h1><?php esc_html_e( 'Beck Wealth – סקירה', 'beckwealth' ); ?></h1>

		<div class="bw-cards">
			<div class="bw-card bw-card--highlight">
				<span class="bw-card__num"><?php echo esc_html( number_format_i18n( $leads['new'] ) ); ?></span>
				<span class="bw-card__label"><?php esc_html_e( 'לידים חדשים', 'beckwealth' ); ?></span>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=bw_lead&bw_status=new' ) ); ?>"><?php esc_html_e( 'לטיפול ›', 'beckwealth' ); ?></a>
			</div>
			<div class="bw-card">
				<span class="bw-card__num"><?php echo esc_html( number_format_i18n( $leads['total'] ) ); ?></span>
				<span class="bw-card__label"><?php esc_html_e( 'סה"כ לידים', 'beckwealth' ); ?></span>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=bw_lead' ) ); ?>"><?php esc_html_e( 'לכל הלידים ›', 'beckwealth' ); ?></a>
			</div>
			<div class="bw-card">
				<span class="bw-card__num"><?php echo esc_html( number_format_i18n( beckwealth_count_published( 'service' ) ) ); ?></span>
				<span class="bw-card__label"><?php esc_html_e( 'שירותים', 'beckwealth' ); ?></span>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=service' ) ); ?>"><?php esc_html_e( 'ניהול ›', 'beckwealth' ); ?></a>
			</div>
			<div class="bw-card">
				<span class="bw-card__num"><?php echo esc_html( number_format_i18n( beckwealth_count_published( 'team' ) ) ); ?></span>
				<span class="bw-card__label"><?php esc_html_e( 'אנשי צוות', 'beckwealth' ); ?></span>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=team' ) ); ?>"><?php esc_html_e( 'ניהול ›', 'beckwealth' ); ?></a>
			</div>
			<div class="bw-card">
				<span class="bw-card__num"><?php echo esc_html( number_format_i18n( beckwealth_count_published( 'testimonial' ) ) ); ?></span>
				<span class="bw-card__label"><?php esc_html_e( 'המלצות', 'beckwealth' ); ?></span>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=testimonial' ) ); ?>"><?php esc_html_e( 'ניהול ›', 'beckwealth' ); ?></a>
			</div>
			<div class="bw-card">
				<span class="bw-card__num"><?php echo esc_html( number_format_i18n( beckwealth_count_published( 'post' ) ) ); ?></span>
				<span class="bw-card__label"><?php esc_html_e( 'מאמרים', 'beckwealth' ); ?></span>
				<a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>"><?php esc_html_e( 'ניהול ›', 'beckwealth' ); ?></a>
			</div>
		</div>

		<div class="bw-columns">
			<div class="bw-panel">
				<h2><?php esc_html_e( 'עריכה מהירה של האתר', 'beckwealth' ); ?></h2>
				<ul class="bw-links">
					<li><a href="<?php echo esc_url( beckwealth_customizer_link( 'beckwealth_home' ) ); ?>"><span class="dashicons dashicons-admin-home"></span> <?php esc_html_e( 'טקסטים ומקטעי דף הבית', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( beckwealth_customizer_link( 'beckwealth_contact' ) ); ?>"><span class="dashicons dashicons-phone"></span> <?php esc_html_e( 'פרטי קשר (טלפון, וואטסאפ, כתובת)', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( beckwealth_customizer_link( 'beckwealth_social' ) ); ?>"><span class="dashicons dashicons-share"></span> <?php esc_html_e( 'רשתות חברתיות', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( beckwealth_customizer_link( 'beckwealth_design' ) ); ?>"><span class="dashicons dashicons-art"></span> <?php esc_html_e( 'צבעים וגופן', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( beckwealth_customizer_link( 'beckwealth_footer' ) ); ?>"><span class="dashicons dashicons-editor-alignleft"></span> <?php esc_html_e( 'פוטר והבהרה משפטית', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( beckwealth_customizer_link( 'title_tagline' ) ); ?>"><span class="dashicons dashicons-format-image"></span> <?php esc_html_e( 'לוגו ושם האתר', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><span class="dashicons dashicons-menu"></span> <?php esc_html_e( 'תפריטים', 'beckwealth' ); ?></a></li>
					<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=beckwealth-settings' ) ); ?>"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'הגדרות: Turnstile, פרטיות, נגישות', 'beckwealth' ); ?></a></li>
				</ul>
				<h2><?php esc_html_e( 'הוספת תוכן', 'beckwealth' ); ?></h2>
				<p>
					<a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=service' ) ); ?>"><?php esc_html_e( '+ שירות', 'beckwealth' ); ?></a>
					<a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=team' ) ); ?>"><?php esc_html_e( '+ איש צוות', 'beckwealth' ); ?></a>
					<a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=testimonial' ) ); ?>"><?php esc_html_e( '+ המלצה', 'beckwealth' ); ?></a>
					<a class="button" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( '+ מאמר', 'beckwealth' ); ?></a>
				</p>
			</div>

			<div class="bw-panel">
				<h2><?php esc_html_e( 'לידים אחרונים', 'beckwealth' ); ?></h2>
				<?php if ( $recent ) : ?>
					<table class="widefat striped">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'שם', 'beckwealth' ); ?></th>
								<th scope="col"><?php esc_html_e( 'טלפון', 'beckwealth' ); ?></th>
								<th scope="col"><?php esc_html_e( 'סטטוס', 'beckwealth' ); ?></th>
								<th scope="col"><?php esc_html_e( 'תאריך', 'beckwealth' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $recent as $lead ) : ?>
								<?php $status = (string) get_post_meta( $lead->ID, '_bw_lead_status', true ) ?: 'new'; ?>
								<tr>
									<td><a href="<?php echo esc_url( get_edit_post_link( $lead->ID ) ); ?>"><?php echo esc_html( $lead->post_title ); ?></a></td>
									<td dir="ltr"><?php echo esc_html( (string) get_post_meta( $lead->ID, '_bw_lead_phone', true ) ); ?></td>
									<td><?php echo esc_html( $statuses[ $status ] ?? $status ); ?></td>
									<td><?php echo esc_html( get_the_date( 'd/m/Y H:i', $lead ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p><?php esc_html_e( 'עדיין לא התקבלו לידים. כשגולשים ישלחו את טופס יצירת הקשר – הם יופיעו כאן.', 'beckwealth' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * עמוד ההגדרות (Settings API).
 */
function beckwealth_render_settings(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s          = wp_parse_args( (array) get_option( 'beckwealth_settings', array() ), beckwealth_default_settings() );
	$has_secret = '' !== (string) beckwealth_get_setting( 'turnstile_secret', '' );
	$const_site = defined( 'BECKWEALTH_TURNSTILE_SITE_KEY' );
	$const_sec  = defined( 'BECKWEALTH_TURNSTILE_SECRET' );
	?>
	<div class="wrap bw-admin">
		<h1><?php esc_html_e( 'Beck Wealth – הגדרות', 'beckwealth' ); ?></h1>
		<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
			<?php settings_fields( 'beckwealth_settings_group' ); ?>

			<h2 class="title"><?php esc_html_e( 'לידים', 'beckwealth' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="lead_notify_email"><?php esc_html_e( 'אימייל להתראות על ליד חדש', 'beckwealth' ); ?></label></th>
					<td>
						<input type="email" class="regular-text" id="lead_notify_email" name="beckwealth_settings[lead_notify_email]" value="<?php echo esc_attr( $s['lead_notify_email'] ); ?>" dir="ltr">
						<p class="description"><?php esc_html_e( 'ריק = האימייל שהוגדר בפרטי הקשר בקסטומייזר (או אימייל המנהל).', 'beckwealth' ); ?></p>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Cloudflare Turnstile (הגנה מספאם, נגיש)', 'beckwealth' ); ?></h2>
			<p class="description"><?php esc_html_e( 'צרו ווידג\'ט ב-Cloudflare Dashboard ‹ Turnstile והדביקו את המפתחות. ללא מפתחות – הטופס מוגן ב-Honeypot והגבלת קצב בלבד. ניתן להגדיר גם כקבועים ב-wp-config.php: BECKWEALTH_TURNSTILE_SITE_KEY / BECKWEALTH_TURNSTILE_SECRET.', 'beckwealth' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="turnstile_site_key"><?php esc_html_e( 'Site Key', 'beckwealth' ); ?></label></th>
					<td>
						<?php if ( $const_site ) : ?>
							<code><?php esc_html_e( 'מוגדר ב-wp-config.php', 'beckwealth' ); ?></code>
						<?php else : ?>
							<input type="text" class="regular-text" id="turnstile_site_key" name="beckwealth_settings[turnstile_site_key]" value="<?php echo esc_attr( $s['turnstile_site_key'] ); ?>" dir="ltr" autocomplete="off">
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="turnstile_secret"><?php esc_html_e( 'Secret Key', 'beckwealth' ); ?></label></th>
					<td>
						<?php if ( $const_sec ) : ?>
							<code><?php esc_html_e( 'מוגדר ב-wp-config.php', 'beckwealth' ); ?></code>
						<?php else : ?>
							<input type="password" class="regular-text" id="turnstile_secret" name="beckwealth_settings[turnstile_secret]" value="" dir="ltr" autocomplete="new-password" placeholder="<?php echo $has_secret ? esc_attr__( '•••••••• (שמור)', 'beckwealth' ) : ''; ?>">
							<?php if ( $has_secret ) : ?>
								<label style="margin-inline-start:8px"><input type="checkbox" name="beckwealth_settings[turnstile_secret_clear]" value="1"> <?php esc_html_e( 'מחיקת המפתח השמור', 'beckwealth' ); ?></label>
							<?php endif; ?>
							<p class="description"><?php esc_html_e( 'המפתח נשמר ואינו מוצג שוב. השאירו ריק כדי לא לשנות.', 'beckwealth' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'הודעת מדיניות פרטיות', 'beckwealth' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'הצגת ההודעה', 'beckwealth' ); ?></th>
					<td>
						<label><input type="checkbox" name="beckwealth_settings[privacy_popup]" value="1" <?php checked( '1', $s['privacy_popup'] ); ?>> <?php esc_html_e( 'הצג פופאפ מדיניות פרטיות בכניסה ראשונה מדפדפן חדש', 'beckwealth' ); ?></label>
						<p class="description">
							<?php
							printf(
								/* translators: %s: link to privacy settings */
								esc_html__( 'הקישור בהודעה מפנה לעמוד מדיניות הפרטיות שהוגדר ב%s.', 'beckwealth' ),
								'<a href="' . esc_url( admin_url( 'options-privacy.php' ) ) . '">' . esc_html__( 'הגדרות ‹ פרטיות', 'beckwealth' ) . '</a>'
							);
							?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="privacy_popup_text"><?php esc_html_e( 'טקסט ההודעה', 'beckwealth' ); ?></label></th>
					<td><textarea class="large-text" rows="3" id="privacy_popup_text" name="beckwealth_settings[privacy_popup_text]"><?php echo esc_textarea( $s['privacy_popup_text'] ); ?></textarea></td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'נגישות', 'beckwealth' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'סרגל נגישות', 'beckwealth' ); ?></th>
					<td><label><input type="checkbox" name="beckwealth_settings[a11y_toolbar]" value="1" <?php checked( '1', $s['a11y_toolbar'] ); ?>> <?php esc_html_e( 'הצג כפתור וסרגל נגישות באתר', 'beckwealth' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><label for="a11y_statement_page"><?php esc_html_e( 'עמוד הצהרת נגישות', 'beckwealth' ); ?></label></th>
					<td>
						<?php
						wp_dropdown_pages(
							array(
								'name'              => 'beckwealth_settings[a11y_statement_page]',
								'id'                => 'a11y_statement_page',
								'selected'          => (int) $s['a11y_statement_page'],
								'show_option_none'  => __( '— בחרו עמוד —', 'beckwealth' ),
								'option_none_value' => '0',
							)
						);
						?>
						<p class="description"><?php esc_html_e( 'העמוד נוצר אוטומטית בהפעלת התבנית. יש להשלים בו את הפרטים המסומנים ולפרסם.', 'beckwealth' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="a11y_coordinator"><?php esc_html_e( 'רכז/ת נגישות – שם', 'beckwealth' ); ?></label></th>
					<td><input type="text" class="regular-text" id="a11y_coordinator" name="beckwealth_settings[a11y_coordinator]" value="<?php echo esc_attr( $s['a11y_coordinator'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="a11y_phone"><?php esc_html_e( 'רכז/ת נגישות – טלפון', 'beckwealth' ); ?></label></th>
					<td><input type="text" class="regular-text" id="a11y_phone" name="beckwealth_settings[a11y_phone]" value="<?php echo esc_attr( $s['a11y_phone'] ); ?>" dir="ltr"></td>
				</tr>
				<tr>
					<th scope="row"><label for="a11y_email"><?php esc_html_e( 'רכז/ת נגישות – אימייל', 'beckwealth' ); ?></label></th>
					<td><input type="email" class="regular-text" id="a11y_email" name="beckwealth_settings[a11y_email]" value="<?php echo esc_attr( $s['a11y_email'] ); ?>" dir="ltr"></td>
				</tr>
			</table>

			<?php submit_button( __( 'שמירת הגדרות', 'beckwealth' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * CSS קטן לעמודי הניהול של התבנית (מוטמע, רק בעמודים שלנו).
 */
function beckwealth_admin_css(): void {
	$screen = get_current_screen();
	if ( ! $screen || ! str_contains( (string) $screen->id, 'beckwealth' ) ) {
		return;
	}
	echo '<style>
	.bw-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:14px;margin:20px 0}
	.bw-card{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px;display:flex;flex-direction:column;gap:4px}
	.bw-card--highlight{border-color:#0b2545;box-shadow:0 0 0 2px rgba(11,37,69,.08)}
	.bw-card__num{font-size:30px;font-weight:700;color:#0b2545;line-height:1}
	.bw-card__label{color:#50575e;font-weight:500}
	.bw-columns{display:grid;grid-template-columns:1fr 1.4fr;gap:20px}
	@media(max-width:960px){.bw-columns{grid-template-columns:1fr}}
	.bw-panel{background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px 22px}
	.bw-panel h2{margin-top:0;font-size:16px}
	.bw-links{margin:0 0 20px}
	.bw-links li{margin:0 0 8px}
	.bw-links a{text-decoration:none;display:inline-flex;align-items:center;gap:6px}
	</style>';
}
add_action( 'admin_head', 'beckwealth_admin_css' );

/**
 * טקסט בפוטר של ממשק הניהול.
 *
 * @return string
 */
function beckwealth_admin_footer(): string {
	return sprintf(
		/* translators: %s: agency link */
		esc_html__( 'עיצוב ופיתוח: %s', 'beckwealth' ),
		'<a href="https://m-d.co.il" target="_blank" rel="noopener">Multi Digital</a>'
	);
}
add_filter( 'admin_footer_text', 'beckwealth_admin_footer' );

/**
 * ווידג'ט לוח בקרה: לידים חדשים + קישור לדשבורד.
 */
function beckwealth_dashboard_widget(): void {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	wp_add_dashboard_widget(
		'beckwealth_overview',
		__( 'Beck Wealth – סקירה מהירה', 'beckwealth' ),
		static function (): void {
			$leads = beckwealth_lead_counts();
			echo '<p style="font-size:15px"><strong>' . esc_html( number_format_i18n( $leads['new'] ) ) . '</strong> ' . esc_html__( 'לידים חדשים ממתינים לטיפול.', 'beckwealth' ) . '</p>';
			echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'edit.php?post_type=bw_lead&bw_status=new' ) ) . '">' . esc_html__( 'לטיפול בלידים', 'beckwealth' ) . '</a> ';
			echo '<a class="button" href="' . esc_url( admin_url( 'admin.php?page=beckwealth' ) ) . '">' . esc_html__( 'לדשבורד Beck Wealth', 'beckwealth' ) . '</a></p>';
		}
	);
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'beckwealth_dashboard_widget' );

/**
 * עמודת תמונה ראשית ברשימות הניהול של סוגי התוכן.
 *
 * @param array $columns עמודות.
 * @return array
 */
function beckwealth_admin_columns( array $columns ): array {
	return array_slice( $columns, 0, 1, true ) + array( 'bw_thumb' => __( 'תמונה', 'beckwealth' ) ) + array_slice( $columns, 1, null, true );
}
foreach ( array( 'service', 'team', 'testimonial' ) as $beckwealth_type ) {
	add_filter( 'manage_' . $beckwealth_type . '_posts_columns', 'beckwealth_admin_columns' );
	add_action( 'manage_' . $beckwealth_type . '_posts_custom_column', 'beckwealth_admin_column_content', 10, 2 );
}
unset( $beckwealth_type );

/**
 * תוכן עמודת התמונה.
 *
 * @param string $column  שם עמודה.
 * @param int    $post_id מזהה.
 */
function beckwealth_admin_column_content( string $column, int $post_id ): void {
	if ( 'bw_thumb' === $column ) {
		echo get_the_post_thumbnail( $post_id, array( 60, 60 ), array( 'style' => 'border-radius:6px' ) ) ?: '&mdash;';
	}
}
