<?php
/**
 * שורת "מהשוק" – מרקיזה אינסופית (תוכן מוכפל), נעצרת במעבר עכבר.
 *
 * @package BeckWealth
 */

defined( 'ABSPATH' ) || exit;

$bw_items = beckwealth_mod_lines( 'ticker_items' );
if ( ! $bw_items ) {
	return;
}
$bw_group = '';
foreach ( $bw_items as $bw_item ) {
	$bw_group .= '<span>' . esc_html( $bw_item ) . '</span><span class="bw-dia bw-dia--5" aria-hidden="true"></span>';
}
?>
<div class="bw-ticker" role="region" aria-label="<?php echo esc_attr( beckwealth_mod( 'ticker_label' ) ); ?>">
	<div class="bw-ticker__inner">
		<div class="bw-ticker__label"><span class="bw-dia bw-dia--6" aria-hidden="true"></span><?php echo esc_html( beckwealth_mod( 'ticker_label' ) ); ?></div>
		<div class="bw-ticker__track">
			<div class="bw-ticker__marquee">
				<div class="bw-ticker__group"><?php echo $bw_group; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
				<div class="bw-ticker__group" aria-hidden="true"><?php echo $bw_group; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></div>
			</div>
		</div>
	</div>
</div>
