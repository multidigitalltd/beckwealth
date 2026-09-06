<?php
/**
 * SEO בסיסי: Open Graph וסכמה. מושבת אוטומטית אם תוסף SEO פעיל.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * האם תוסף SEO פעיל?
 *
 * @return bool
 */
function beckwealth_has_seo_plugin(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * תגיות Open Graph / Twitter.
 */
function beckwealth_og_tags(): void {
	if ( beckwealth_has_seo_plugin() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = get_bloginfo( 'description' );
	$url         = home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
	$image       = '';

	if ( is_singular() ) {
		$post_id     = get_queried_object_id();
		$url         = get_permalink( $post_id );
		$excerpt     = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 30 );
		$description = $excerpt ?: $description;
		if ( has_post_thumbnail( $post_id ) ) {
			$image = (string) get_the_post_thumbnail_url( $post_id, 'large' );
		}
	}

	if ( ! $image && has_custom_logo() ) {
		$image = (string) wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'full' );
	}

	echo "\n<!-- Beck Wealth OG -->\n";
	echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	}
	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
}
add_action( 'wp_head', 'beckwealth_og_tags', 5 );

/**
 * סכמת JSON-LD לעסק פיננסי.
 */
function beckwealth_schema(): void {
	if ( beckwealth_has_seo_plugin() && ! is_front_page() ) {
		return;
	}
	$contact = beckwealth_contact_details();
	$schema  = array(
		'@context' => 'https://schema.org',
		'@type'    => 'FinancialService',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
	);
	if ( get_bloginfo( 'description' ) ) {
		$schema['description'] = get_bloginfo( 'description' );
	}
	if ( has_custom_logo() ) {
		$schema['logo'] = wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'full' );
	}
	if ( $contact['phone'] ) {
		$schema['telephone'] = $contact['phone'];
	}
	if ( $contact['email'] ) {
		$schema['email'] = $contact['email'];
	}
	if ( $contact['address'] ) {
		$schema['address'] = array(
			'@type'         => 'PostalAddress',
			'streetAddress' => $contact['address'],
			'addressCountry' => 'IL',
		);
	}
	$same_as = array_column( beckwealth_social_links(), 'url' );
	if ( $same_as ) {
		$schema['sameAs'] = $same_as;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'beckwealth_schema', 6 );

/**
 * noindex לעמודי חיפוש ו-404 (מפחית עמודים דלים באינדקס).
 */
function beckwealth_noindex(): void {
	if ( beckwealth_has_seo_plugin() ) {
		return;
	}
	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'beckwealth_noindex', 1 );
