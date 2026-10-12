<?php
/**
 * Title: שאלות ותשובות
 * Slug: beckwealth/faq
 * Categories: beckwealth
 * Description: כותרת ושלוש שאלות נפתחות (בלוק Details).
 *
 * @package BeckWealth
 */

$beckwealth_items = array(
	array( __( 'מה סף הכניסה?', 'beckwealth' ), __( 'הליווי מתאים לרוב מהיקף נכסים פנוי של כמיליון דולר ומעלה. בשיחת ההיכרות נבין יחד אם יש התאמה.', 'beckwealth' ) ),
	array( __( 'מה העלויות?', 'beckwealth' ), __( 'דמי ניהול שקופים הנגזרים מהיקף הנכסים, ללא עמלות נסתרות.', 'beckwealth' ) ),
	array( __( 'מה קורה בשיחה הראשונה?', 'beckwealth' ), __( '30 דקות, בזום או במשרד. בלי התחייבות ובלי מצגות מכירה.', 'beckwealth' ) ),
);
?>
<!-- wp:heading --><h2 class="wp-block-heading"><?php esc_html_e( 'שאלות נפוצות', 'beckwealth' ); ?></h2><!-- /wp:heading -->
<?php foreach ( $beckwealth_items as [ $beckwealth_q, $beckwealth_a ] ) : ?>
<!-- wp:details --><details class="wp-block-details"><summary><?php echo esc_html( $beckwealth_q ); ?></summary><!-- wp:paragraph --><p><?php echo esc_html( $beckwealth_a ); ?></p><!-- /wp:paragraph --></details><!-- /wp:details -->
<?php endforeach; ?>
