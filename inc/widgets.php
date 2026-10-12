<?php
/**
 * אזורי ווידג'טים.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * רישום אזורי ווידג'טים.
 * סרגל הצד של הבלוג הוסר ב-1.2.2: וורדפרס מילאה אותו אוטומטית בווידג'טים באנגלית (Search, Recent Posts…),
 * ועמוד "פרסומים ומאמרים" מוצג ברוחב מלא לפי העיצוב. ניתן להחזיר אזור דרך הפילטר beckwealth_widget_areas.
 */
function beckwealth_widgets_init(): void {
	$areas = apply_filters( 'beckwealth_widget_areas', array() );

	foreach ( $areas as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'beckwealth_widgets_init' );
