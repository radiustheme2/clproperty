<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="cart-icon-area search-icon-wrapper">
    <a class="item-btn" href="javascript:void(0)">
        <i class="icon-rt-icon-search-line"></i>
    </a>
    <div class="cart-icon-products">
	    <?php get_search_form(); ?>
    </div>
</div>