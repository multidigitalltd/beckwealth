<?php
/**
 * דף הבית – מקטעים לפי העיצוב v6. כל מקטע בקובץ נפרד תחת template-parts/home/.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

get_header();

$beckwealth_sections = array( 'hero', 'trust', 'swiss', 'advantage', 'departments', 'offices', 'quote', 'legacy', 'process', 'team', 'testimonials', 'blog', 'faq', 'contact' );
foreach ( $beckwealth_sections as $beckwealth_section ) {
	get_template_part( 'template-parts/home/' . $beckwealth_section );
}

// תוכן חופשי שנכתב בעורך של עמוד הבית (אופציונלי).
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( trim( get_the_content() ) ) {
			echo '<section class="section"><div class="container container--narrow entry-content">';
			the_content();
			echo '</div></section>';
		}
	}
}

get_footer();
