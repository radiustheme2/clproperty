<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Helper;

global $listing;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

$listing_class = ' product-box style2';

?>

<div <?php Functions::listing_class( $listing_class, $listing ) ?> <?php Functions::listing_data_attr_options() ?>>
	<?php
        if ( $view == 'grid' ) {
            Helper::get_custom_listing_template( 'grid' );
        } else {
            Helper::get_custom_listing_template( 'list' );
        }
	?>
</div>
