<?php
/**
 * הודעת מדיניות פרטיות בכניסה ראשונה (JS/localStorage – ידידותי ל-Page Cache).
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

/**
 * הדפסת ההודעה (מוסתרת ב-hidden; JS מציג אותה רק אם לא אושרה).
 */
function beckwealth_privacy_notice(): void {
	if ( ! beckwealth_get_setting( 'privacy_popup', '1' ) ) {
		return;
	}
	$text   = (string) beckwealth_get_setting( 'privacy_popup_text', '' );
	$policy = get_privacy_policy_url();
	if ( '' === $text ) {
		return;
	}
	?>
	<div id="privacy-notice" class="privacy-notice" role="dialog" aria-labelledby="privacy-notice-title" aria-describedby="privacy-notice-text" hidden>
		<h2 id="privacy-notice-title" class="screen-reader-text"><?php esc_html_e( 'הודעה על מדיניות פרטיות', 'beckwealth' ); ?></h2>
		<p id="privacy-notice-text" class="privacy-notice__text">
			<?php echo esc_html( $text ); ?>
			<?php if ( $policy ) : ?>
				<a href="<?php echo esc_url( $policy ); ?>"><?php esc_html_e( 'למדיניות הפרטיות', 'beckwealth' ); ?></a>
			<?php endif; ?>
		</p>
		<div class="privacy-notice__actions">
			<button type="button" class="btn btn--primary" data-privacy-accept><?php esc_html_e( 'הבנתי', 'beckwealth' ); ?></button>
		</div>
	</div>
	<?php
}
