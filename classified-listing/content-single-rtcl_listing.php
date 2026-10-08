<?php
/**
 * The template for displaying product content in the single-rtcl_listing.php template
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/content-single-rtcl_listing.php.
 *
 * @package ClassifiedListing/Templates
 * @version 1.5.56
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;

if ( post_password_required() ) {
	echo wp_kses_post( get_the_password_form() );

	return;
}

global $listing;
$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
$sidebar_class    = [
	'col-lg-3 col-md-12',
	'order-2',
];
$content_class    = [
	'col-lg-9 col-md-12',
	'order-1',
	'listing-content',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
	$content_class   = array_diff( $content_class, [ 'order-1' ] );
	$content_class[] = 'order-2';
} elseif ( $sidebar_position == "bottom" ) {
	$content_class   = array_diff( $content_class, ['col-lg-9 col-md-12','col-lg-12'] );
	$sidebar_class   = array_diff( $sidebar_class, ['col-lg-3 col-md-12','col-lg-12' ] ); 
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

if(!RDTheme::$options['listing_detail_sidebar']) {
	$content_class    = [
		'col-lg-10 offset-lg-1 col-md-12',
		'order-1',
		'listing-content',
	];
}

$style = Helper::listing_single_style();

/**
 * Hook: rtcl_before_single_product.
 *
 * @hooked rtcl_print_notices - 10
 */
do_action( 'rtcl_before_single_listing' );

Helper::get_custom_listing_template( 'content-single-' . $style, true, compact( 'sidebar_position', 'content_class' ) );

do_action( 'rtcl_after_single_listing' );
