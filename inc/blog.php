<?php
/**
 * בלוג: מאמרים פנימיים וכתבות חיצוניות.
 * כתבה חיצונית = פוסט רגיל (כותרת, תמונה, קטגוריה, תקציר) עם "קישור לכתבה חיצונית" – הכרטיס
 * נראה זהה, אך הלחיצה מובילה לכתובת החיצונית (בלשונית חדשה). פתיחה ישירה של הפוסט מפנה לכתבה.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

const BECKWEALTH_EXTERNAL_META = '_bw_external_url';

/**
 * כתובת הכתבה החיצונית של פוסט (ריק = מאמר פנימי).
 *
 * @param int|WP_Post|null $post פוסט.
 * @return string
 */
function beckwealth_external_url( $post = null ): string {
	$post = get_post( $post );
	if ( ! $post || 'post' !== $post->post_type ) {
		return '';
	}
	return (string) get_post_meta( $post->ID, BECKWEALTH_EXTERNAL_META, true );
}

/**
 * כתובת היעד של כרטיס כתבה: חיצונית אם הוגדרה, אחרת הפוסט עצמו.
 *
 * @param int|WP_Post|null $post פוסט.
 * @return string
 */
function beckwealth_post_url( $post = null ): string {
	$external = beckwealth_external_url( $post );
	return '' !== $external ? $external : (string) get_permalink( $post );
}

/**
 * מאפייני קישור לכרטיס כתבה (href + target/rel לכתבה חיצונית), מוכנים להדפסה.
 *
 * @param int|WP_Post|null $post פוסט.
 * @return string
 */
function beckwealth_post_link_attrs( $post = null ): string {
	$external = beckwealth_external_url( $post );
	if ( '' !== $external ) {
		return 'href="' . esc_url( $external ) . '" target="_blank" rel="noopener noreferrer"';
	}
	return 'href="' . esc_url( (string) get_permalink( $post ) ) . '"';
}

/**
 * רישום המטא (זמין גם בעורך הבלוקים דרך REST).
 */
function beckwealth_register_external_meta(): void {
	register_post_meta(
		'post',
		BECKWEALTH_EXTERNAL_META,
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
		)
	);
}
add_action( 'init', 'beckwealth_register_external_meta' );

/**
 * Metabox "סוג הכתבה" בעריכת פוסט.
 */
function beckwealth_external_meta_box(): void {
	add_meta_box( 'beckwealth_external', __( 'סוג הכתבה', 'beckwealth' ), 'beckwealth_render_external_meta_box', 'post', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'beckwealth_external_meta_box' );

/**
 * תוכן ה-metabox.
 *
 * @param WP_Post $post הפוסט.
 */
function beckwealth_render_external_meta_box( WP_Post $post ): void {
	$url = beckwealth_external_url( $post );
	wp_nonce_field( 'beckwealth_save_external', 'beckwealth_external_nonce' );
	?>
	<p class="description" style="margin-top:0"><?php esc_html_e( 'מאמר פנימי – השאירו ריק. כתבה באתר אחר – הדביקו את הכתובת המלאה; הכרטיס בבלוג ייראה זהה, והלחיצה תוביל לכתבה החיצונית.', 'beckwealth' ); ?></p>
	<p>
		<label for="bw_external_url"><strong><?php esc_html_e( 'קישור לכתבה חיצונית', 'beckwealth' ); ?></strong></label><br>
		<input type="url" class="widefat" id="bw_external_url" name="bw_external_url" value="<?php echo esc_attr( $url ); ?>" placeholder="https://" dir="ltr">
	</p>
	<p class="description"><?php esc_html_e( 'טיפ: גם לכתבה חיצונית כדאי להוסיף תמונה ראשית, קטגוריה ותקציר קצר – הם מוצגים בכרטיס.', 'beckwealth' ); ?></p>
	<?php
}

/**
 * שמירת הקישור החיצוני.
 *
 * @param int $post_id מזהה פוסט.
 */
function beckwealth_save_external_meta( int $post_id ): void {
	if ( ! isset( $_POST['beckwealth_external_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['beckwealth_external_nonce'] ), 'beckwealth_save_external' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$url = isset( $_POST['bw_external_url'] ) ? esc_url_raw( trim( wp_unslash( $_POST['bw_external_url'] ) ) ) : '';
	if ( '' === $url || ! in_array( wp_parse_url( $url, PHP_URL_SCHEME ), array( 'http', 'https' ), true ) ) {
		delete_post_meta( $post_id, BECKWEALTH_EXTERNAL_META );
		return;
	}
	update_post_meta( $post_id, BECKWEALTH_EXTERNAL_META, $url );
}
add_action( 'save_post_post', 'beckwealth_save_external_meta' );

/**
 * פתיחה ישירה של פוסט "חיצוני" מפנה לכתבה החיצונית.
 */
function beckwealth_external_redirect(): void {
	if ( ! is_singular( 'post' ) || is_preview() ) {
		return;
	}
	$external = beckwealth_external_url( get_queried_object_id() );
	if ( '' !== $external ) {
		wp_redirect( $external, 302, 'BeckWealth' ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- יעד חיצוני מכוון, מנוקה ב-esc_url_raw.
		exit;
	}
}
add_action( 'template_redirect', 'beckwealth_external_redirect' );

/**
 * כתבות חיצוניות לא נכללות במפת האתר (הפוסט עצמו רק מפנה החוצה).
 *
 * @param array  $args      ארגומנטים של WP_Query.
 * @param string $post_type סוג תוכן.
 * @return array
 */
function beckwealth_external_sitemap_args( array $args, string $post_type ): array {
	if ( 'post' === $post_type ) {
		$args['meta_query'] = array( array( 'key' => BECKWEALTH_EXTERNAL_META, 'compare' => 'NOT EXISTS' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- מפת אתר, לא עמוד קדמי.
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'beckwealth_external_sitemap_args', 10, 2 );

/**
 * עמודה "סוג" ברשימת הפוסטים בניהול.
 *
 * @param array $columns עמודות.
 * @return array
 */
function beckwealth_external_column( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['bw_type'] = __( 'סוג', 'beckwealth' );
		}
	}
	return $new;
}
add_filter( 'manage_post_posts_columns', 'beckwealth_external_column' );

/**
 * תוכן העמודה.
 *
 * @param string $column  עמודה.
 * @param int    $post_id פוסט.
 */
function beckwealth_external_column_content( string $column, int $post_id ): void {
	if ( 'bw_type' !== $column ) {
		return;
	}
	$external = beckwealth_external_url( $post_id );
	if ( '' === $external ) {
		echo '<span class="dashicons dashicons-media-text" style="color:#50575e"></span> ' . esc_html__( 'מאמר פנימי', 'beckwealth' );
		return;
	}
	printf(
		'<span class="dashicons dashicons-external" style="color:#96793a"></span> <a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
		esc_url( $external ),
		esc_html__( 'כתבה חיצונית', 'beckwealth' )
	);
}
add_action( 'manage_post_posts_custom_column', 'beckwealth_external_column_content', 10, 2 );
