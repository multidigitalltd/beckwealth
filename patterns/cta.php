<?php
/**
 * Title: קריאה לפעולה
 * Slug: beckwealth/cta
 * Categories: beckwealth
 * Description: מקטע כהה עם כותרת, טקסט וכפתור זהב.
 *
 * @package BeckWealth
 */

?>
<!-- wp:group {"align":"full","backgroundColor":"dark","textColor":"paper","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignfull has-paper-color has-dark-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:heading {"textAlign":"center","textColor":"paper"} --><h2 class="wp-block-heading has-text-align-center has-paper-color has-text-color"><?php esc_html_e( 'מוכנים להתחיל?', 'beckwealth' ); ?></h2><!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center"><?php esc_html_e( 'תאמו שיחת היכרות חסויה, ללא התחייבות.', 'beckwealth' ); ?></p><!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"gold","textColor":"ink-dark"} --><div class="wp-block-button"><a class="wp-block-button__link has-ink-dark-color has-gold-background-color has-text-color has-background wp-element-button" href="#contact"><?php esc_html_e( 'תיאום שיחת היכרות', 'beckwealth' ); ?></a></div><!-- /wp:button --></div><!-- /wp:buttons -->
</div><!-- /wp:group -->
