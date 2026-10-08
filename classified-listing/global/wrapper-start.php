<?php
/**
 * Content wrappers
 *
 * @package     ClassifiedListing/Templates
 * @version     1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\RDTheme;

$templateClass = 'content-area';
if ( Functions::is_listing() ) {
	$style         = ! empty( RDTheme::$options['single_listing_style'] ) ? RDTheme::$options['single_listing_style'] : '1';
	$templateClass .= ' single-product';
	switch ( $style ) {
		case '2':
			$templateClass .= ' single-product-style2';
			break;
		case '3':
			$templateClass .= ' single-product-style3';
			break;
	}
}
?>

<div id="primary" class="<?php echo esc_attr( $templateClass ); ?>">