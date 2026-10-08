<?php
/**
 * Price
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $listing;

if ( ! $listing->can_show_price() ) {
	return;
}
?>
<div class="item-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
