<?php
/**
 * אזורי ווידג'טים.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * רישום אזורי ווידג'טים לפוטר ולסרגל צד.
 */
function beckwealth_widgets_init(): void {
	$areas = array(
		'sidebar-blog' => __( 'סרגל צד – בלוג', 'beckwealth' ),
	);

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
