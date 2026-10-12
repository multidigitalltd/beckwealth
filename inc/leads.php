<?php
/**
 * מיני-CRM ללידים: סוג תוכן פנימי bw_lead, סטטוסים, סינון, ייצוא CSV.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * סטטוסי ליד.
 *
 * @return array<string, string>
 */
function beckwealth_lead_statuses(): array {
	return array(
		'new'       => __( 'חדש', 'beckwealth' ),
		'contacted' => __( 'בטיפול', 'beckwealth' ),
		'won'       => __( 'נסגר – לקוח', 'beckwealth' ),
		'lost'      => __( 'לא רלוונטי', 'beckwealth' ),
	);
}

/**
 * רישום סוג התוכן (לא ציבורי, רק בניהול, בתפריט Beck Wealth).
 */
function beckwealth_register_lead_cpt(): void {
	register_post_type(
		'bw_lead',
		array(
			'labels'              => array(
				'name'          => __( 'לידים', 'beckwealth' ),
				'singular_name' => __( 'ליד', 'beckwealth' ),
				'edit_item'     => __( 'פרטי ליד', 'beckwealth' ),
				'search_items'  => __( 'חיפוש לידים', 'beckwealth' ),
				'not_found'     => __( 'עדיין אין לידים', 'beckwealth' ),
				'all_items'     => __( 'לידים', 'beckwealth' ),
				'menu_name'     => __( 'לידים', 'beckwealth' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'beckwealth',
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'supports'            => array( 'title', 'editor' ),
			'rewrite'             => false,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'beckwealth_register_lead_cpt' );

/**
 * יצירת ליד מנתוני טופס (נקרא מ-contact-form.php).
 *
 * @param array $lead נתונים מנוקים: name, phone, email, subject, message, page.
 * @return int מזהה הליד או 0.
 */
function beckwealth_create_lead( array $lead ): int {
	$lead_id = wp_insert_post(
		array(
			'post_type'    => 'bw_lead',
			'post_status'  => 'private',
			'post_title'   => $lead['name'],
			'post_content' => $lead['message'],
			'meta_input'   => array(
				'_bw_lead_phone'      => $lead['phone'],
				'_bw_lead_email'      => $lead['email'],
				'_bw_lead_subject'    => $lead['subject'],
				'_bw_lead_wealth'     => $lead['wealth'] ?? '',
				'_bw_lead_newsletter' => ! empty( $lead['newsletter'] ) ? '1' : '0',
				'_bw_lead_page'       => $lead['page'],
				'_bw_lead_status'     => 'new',
			),
		),
		false
	);
	if ( is_wp_error( $lead_id ) || ! $lead_id ) {
		return 0;
	}
	delete_transient( 'beckwealth_lead_counts' );
	return (int) $lead_id;
}

/**
 * ספירת לידים לפי סטטוס – שאילתה אחת, במטמון 5 דקות.
 *
 * @return array<string, int>
 */
function beckwealth_lead_counts(): array {
	$counts = get_transient( 'beckwealth_lead_counts' );
	if ( is_array( $counts ) ) {
		return $counts;
	}
	global $wpdb;
	$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->prepare(
			"SELECT pm.meta_value AS status, COUNT(*) AS total
			 FROM {$wpdb->posts} p
			 INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = %s
			 WHERE p.post_type = %s AND p.post_status = %s
			 GROUP BY pm.meta_value",
			'_bw_lead_status',
			'bw_lead',
			'private'
		),
		ARRAY_A
	);
	$counts = array_fill_keys( array_keys( beckwealth_lead_statuses() ), 0 );
	foreach ( (array) $rows as $row ) {
		if ( isset( $counts[ $row['status'] ] ) ) {
			$counts[ $row['status'] ] = (int) $row['total'];
		}
	}
	$counts['total'] = array_sum( $counts );
	set_transient( 'beckwealth_lead_counts', $counts, 5 * MINUTE_IN_SECONDS );
	return $counts;
}

/* -------------------------------------------------------------------------
 * ממשק ניהול
 * ---------------------------------------------------------------------- */

/**
 * עמודות ברשימת הלידים.
 *
 * @param array $columns עמודות.
 * @return array
 */
function beckwealth_lead_columns( array $columns ): array {
	return array(
		'cb'      => $columns['cb'] ?? '',
		'title'   => __( 'שם', 'beckwealth' ),
		'phone'   => __( 'טלפון', 'beckwealth' ),
		'email'   => __( 'אימייל', 'beckwealth' ),
		'subject' => __( 'נושא', 'beckwealth' ),
		'status'  => __( 'סטטוס', 'beckwealth' ),
		'page'    => __( 'מקור', 'beckwealth' ),
		'date'    => __( 'תאריך', 'beckwealth' ),
	);
}
add_filter( 'manage_bw_lead_posts_columns', 'beckwealth_lead_columns' );

/**
 * תוכן העמודות.
 *
 * @param string $column  עמודה.
 * @param int    $post_id מזהה.
 */
function beckwealth_lead_column_content( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'phone':
			$phone = (string) get_post_meta( $post_id, '_bw_lead_phone', true );
			echo $phone ? '<a href="' . esc_attr( beckwealth_tel_href( $phone ) ) . '" dir="ltr">' . esc_html( $phone ) . '</a>' : '&mdash;';
			break;
		case 'email':
			$email = (string) get_post_meta( $post_id, '_bw_lead_email', true );
			echo $email ? '<a href="mailto:' . esc_attr( $email ) . '" dir="ltr">' . esc_html( $email ) . '</a>' : '&mdash;';
			break;
		case 'subject':
			echo esc_html( (string) get_post_meta( $post_id, '_bw_lead_subject', true ) ?: '—' );
			break;
		case 'status':
			$status   = (string) get_post_meta( $post_id, '_bw_lead_status', true ) ?: 'new';
			$statuses = beckwealth_lead_statuses();
			printf( '<span class="bw-status bw-status--%1$s">%2$s</span>', esc_attr( $status ), esc_html( $statuses[ $status ] ?? $status ) );
			break;
		case 'page':
			$page = (string) get_post_meta( $post_id, '_bw_lead_page', true );
			echo $page ? '<a href="' . esc_url( $page ) . '" target="_blank" rel="noopener">' . esc_html( wp_parse_url( $page, PHP_URL_PATH ) ?: '/' ) . '</a>' : '&mdash;';
			break;
	}
}
add_action( 'manage_bw_lead_posts_custom_column', 'beckwealth_lead_column_content', 10, 2 );

/**
 * סינון לפי סטטוס ברשימה.
 *
 * @param string $post_type סוג תוכן.
 */
function beckwealth_lead_filters( string $post_type ): void {
	if ( 'bw_lead' !== $post_type ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter UI.
	$current = isset( $_GET['bw_status'] ) ? sanitize_key( $_GET['bw_status'] ) : '';
	echo '<label class="screen-reader-text" for="bw_status">' . esc_html__( 'סינון לפי סטטוס', 'beckwealth' ) . '</label>';
	echo '<select name="bw_status" id="bw_status">';
	echo '<option value="">' . esc_html__( 'כל הסטטוסים', 'beckwealth' ) . '</option>';
	foreach ( beckwealth_lead_statuses() as $key => $label ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $current, $key, false ), esc_html( $label ) );
	}
	echo '</select>';

	$export_url = wp_nonce_url( admin_url( 'admin-post.php?action=beckwealth_export_leads' ), 'beckwealth_export_leads' );
	echo '<a class="button" href="' . esc_url( $export_url ) . '" style="margin-inline-start:6px">' . esc_html__( 'ייצוא CSV', 'beckwealth' ) . '</a>';
}
add_action( 'restrict_manage_posts', 'beckwealth_lead_filters' );

/**
 * החלת הסינון על השאילתה.
 *
 * @param WP_Query $query שאילתה.
 */
function beckwealth_lead_filter_query( WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() || 'bw_lead' !== $query->get( 'post_type' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
	$status = isset( $_GET['bw_status'] ) ? sanitize_key( $_GET['bw_status'] ) : '';
	if ( $status && isset( beckwealth_lead_statuses()[ $status ] ) ) {
		$query->set( 'meta_key', '_bw_lead_status' ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		$query->set( 'meta_value', $status ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
	}
}
add_action( 'pre_get_posts', 'beckwealth_lead_filter_query' );

/**
 * Metabox פרטי ליד + סטטוס + הערות.
 */
function beckwealth_lead_meta_boxes(): void {
	add_meta_box( 'beckwealth_lead_details', __( 'פרטי הליד', 'beckwealth' ), 'beckwealth_lead_meta_box', 'bw_lead', 'side', 'high' );
}
add_action( 'add_meta_boxes_bw_lead', 'beckwealth_lead_meta_boxes' );

/**
 * הצגת ה-metabox.
 *
 * @param WP_Post $post ליד.
 */
function beckwealth_lead_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'beckwealth_lead_save', 'beckwealth_lead_nonce' );
	$phone   = (string) get_post_meta( $post->ID, '_bw_lead_phone', true );
	$email   = (string) get_post_meta( $post->ID, '_bw_lead_email', true );
	$subject = (string) get_post_meta( $post->ID, '_bw_lead_subject', true );
	$page    = (string) get_post_meta( $post->ID, '_bw_lead_page', true );
	$status  = (string) get_post_meta( $post->ID, '_bw_lead_status', true ) ?: 'new';
	$notes   = (string) get_post_meta( $post->ID, '_bw_lead_notes', true );
	?>
	<p><strong><?php esc_html_e( 'טלפון:', 'beckwealth' ); ?></strong> <?php echo $phone ? '<a href="' . esc_attr( beckwealth_tel_href( $phone ) ) . '" dir="ltr">' . esc_html( $phone ) . '</a>' : '&mdash;'; ?></p>
	<p><strong><?php esc_html_e( 'אימייל:', 'beckwealth' ); ?></strong> <?php echo $email ? '<a href="mailto:' . esc_attr( $email ) . '" dir="ltr">' . esc_html( $email ) . '</a>' : '&mdash;'; ?></p>
	<p><strong><?php esc_html_e( 'נושא:', 'beckwealth' ); ?></strong> <?php echo esc_html( $subject ?: '—' ); ?></p>
	<p><strong><?php esc_html_e( 'היקף הון:', 'beckwealth' ); ?></strong> <?php echo esc_html( (string) get_post_meta( $post->ID, '_bw_lead_wealth', true ) ?: '—' ); ?></p>
	<p><strong><?php esc_html_e( 'ניוזלטר:', 'beckwealth' ); ?></strong> <?php echo '1' === (string) get_post_meta( $post->ID, '_bw_lead_newsletter', true ) ? esc_html__( 'כן', 'beckwealth' ) : esc_html__( 'לא', 'beckwealth' ); ?></p>
	<p><strong><?php esc_html_e( 'מקור:', 'beckwealth' ); ?></strong> <?php echo $page ? '<a href="' . esc_url( $page ) . '" target="_blank" rel="noopener">' . esc_html( $page ) . '</a>' : '&mdash;'; ?></p>
	<p><strong><?php esc_html_e( 'התקבל:', 'beckwealth' ); ?></strong> <?php echo esc_html( get_the_date( 'd/m/Y H:i', $post ) ); ?></p>
	<hr>
	<p>
		<label for="bw_lead_status"><strong><?php esc_html_e( 'סטטוס', 'beckwealth' ); ?></strong></label><br>
		<select id="bw_lead_status" name="bw_lead_status" class="widefat">
			<?php foreach ( beckwealth_lead_statuses() as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $status, $key ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="bw_lead_notes"><strong><?php esc_html_e( 'הערות פנימיות', 'beckwealth' ); ?></strong></label><br>
		<textarea id="bw_lead_notes" name="bw_lead_notes" class="widefat" rows="5"><?php echo esc_textarea( $notes ); ?></textarea>
	</p>
	<?php
}

/**
 * שמירת סטטוס והערות.
 *
 * @param int $post_id מזהה.
 */
function beckwealth_lead_save( int $post_id ): void {
	if ( ! isset( $_POST['beckwealth_lead_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['beckwealth_lead_nonce'] ), 'beckwealth_lead_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['bw_lead_status'] ) ) {
		$status = sanitize_key( $_POST['bw_lead_status'] );
		if ( isset( beckwealth_lead_statuses()[ $status ] ) ) {
			update_post_meta( $post_id, '_bw_lead_status', $status );
			delete_transient( 'beckwealth_lead_counts' );
		}
	}
	if ( isset( $_POST['bw_lead_notes'] ) ) {
		$notes = sanitize_textarea_field( wp_unslash( $_POST['bw_lead_notes'] ) );
		if ( '' === $notes ) {
			delete_post_meta( $post_id, '_bw_lead_notes' );
		} else {
			update_post_meta( $post_id, '_bw_lead_notes', $notes );
		}
	}
}
add_action( 'save_post_bw_lead', 'beckwealth_lead_save' );

/**
 * הודעת הליד לקריאה בלבד בעורך (העורך הקלאסי, ללא בלוקים).
 *
 * @param bool   $use_block_editor האם עורך בלוקים.
 * @param string $post_type        סוג תוכן.
 * @return bool
 */
function beckwealth_lead_classic_editor( bool $use_block_editor, string $post_type ): bool {
	return 'bw_lead' === $post_type ? false : $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'beckwealth_lead_classic_editor', 10, 2 );

/**
 * ייצוא CSV (דורש הרשאה + nonce). מוגבל ל-5000 שורות בכל ייצוא.
 */
function beckwealth_export_leads(): void {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'אין לך הרשאה לבצע פעולה זו.', 'beckwealth' ), 403 );
	}
	check_admin_referer( 'beckwealth_export_leads' );

	$leads = get_posts(
		array(
			'post_type'              => 'bw_lead',
			'post_status'            => 'private',
			'posts_per_page'         => 5000,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);

	$statuses = beckwealth_lead_statuses();

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="leads-' . gmdate( 'Y-m-d' ) . '.csv"' );

	$out = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
	fwrite( $out, "\xEF\xBB\xBF" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite -- BOM לאקסל בעברית.
	fputcsv( $out, array( 'תאריך', 'שם', 'טלפון', 'אימייל', 'נושא', 'היקף הון', 'ניוזלטר', 'הודעה', 'סטטוס', 'מקור', 'הערות' ) );

	foreach ( $leads as $lead ) {
		$status = (string) get_post_meta( $lead->ID, '_bw_lead_status', true ) ?: 'new';
		$row    = array(
			get_the_date( 'Y-m-d H:i', $lead ),
			$lead->post_title,
			(string) get_post_meta( $lead->ID, '_bw_lead_phone', true ),
			(string) get_post_meta( $lead->ID, '_bw_lead_email', true ),
			(string) get_post_meta( $lead->ID, '_bw_lead_subject', true ),
			(string) get_post_meta( $lead->ID, '_bw_lead_wealth', true ),
			'1' === (string) get_post_meta( $lead->ID, '_bw_lead_newsletter', true ) ? 'כן' : 'לא',
			$lead->post_content,
			$statuses[ $status ] ?? $status,
			(string) get_post_meta( $lead->ID, '_bw_lead_page', true ),
			(string) get_post_meta( $lead->ID, '_bw_lead_notes', true ),
		);
		// מניעת CSV injection בתאים המתחילים בתווי נוסחה.
		$row = array_map( static fn( $v ) => preg_match( '/^[=+\-@\t\r]/', (string) $v ) ? "'" . $v : $v, $row );
		fputcsv( $out, $row );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
	exit;
}
add_action( 'admin_post_beckwealth_export_leads', 'beckwealth_export_leads' );

/**
 * CSS קטן לתגי סטטוס ברשימה.
 */
function beckwealth_lead_admin_css(): void {
	$screen = get_current_screen();
	if ( ! $screen || 'bw_lead' !== $screen->post_type ) {
		return;
	}
	echo '<style>.bw-status{display:inline-block;padding:2px 10px;border-radius:999px;font-size:12px;font-weight:600;background:#e5e7eb;color:#111}.bw-status--new{background:#dbeafe;color:#1e3a8a}.bw-status--contacted{background:#fef3c7;color:#92400e}.bw-status--won{background:#d1fae5;color:#065f46}.bw-status--lost{background:#f3f4f6;color:#6b7280}</style>';
}
add_action( 'admin_head', 'beckwealth_lead_admin_css' );
