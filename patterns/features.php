<?php
/**
 * Title: שלושה יתרונות
 * Slug: beckwealth/features
 * Categories: beckwealth
 * Description: שלוש עמודות של כותרת וטקסט.
 *
 * @package BeckWealth
 */

$beckwealth_cols = array(
	array( __( 'איש קשר אחד', 'beckwealth' ), __( 'מנהל תיק קבוע בתל אביב שמדבר עם ציריך בשבילכם.', 'beckwealth' ) ),
	array( __( 'שקיפות מלאה', 'beckwealth' ), __( 'דיווח ברור על עלויות, ביצועים וסיכונים.', 'beckwealth' ) ),
	array( __( 'פיקוח שוויצרי', 'beckwealth' ), __( 'הנכסים מוחזקים על שמכם בבנק שוויצרי תחת FINMA.', 'beckwealth' ) ),
);
?>
<!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide">
<?php foreach ( $beckwealth_cols as [ $beckwealth_title, $beckwealth_text ] ) : ?>
<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><?php echo esc_html( $beckwealth_title ); ?></h3><!-- /wp:heading --><!-- wp:paragraph --><p><?php echo esc_html( $beckwealth_text ); ?></p><!-- /wp:paragraph --></div><!-- /wp:column -->
<?php endforeach; ?>
</div><!-- /wp:columns -->
