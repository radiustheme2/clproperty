<?php
/**
 * This file is for showing listing header
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use radiustheme\ClProperty\Listing_Functions;
use Rtcl\Helpers\Functions;
global $listing;

if ( Functions::isEnableFb() && Listing_Functions::check_fb_custom_group_field_set() ) {
	$panoramaImg = get_post_meta( $listing->get_id(), "panorama_img", true );
    if (!empty($panoramaImg)) {
	    $panorama = $panoramaImg[0];
    } else {
	    $panorama = '';
    }
} else {
	$panorama = get_post_meta( $listing->get_id(), 'clproperty_panorama_img', true );
}

$single_listing = Functions::get_option( 'rtcl_single_listing_settings' );
$text = isset( $single_listing['panorama_section_label'] ) && ! empty( $single_listing['panorama_section_label'] ) ? $single_listing['panorama_section_label'] : '';

if ( $panorama ) { ?>
    <div class="clproperty-accordion-item section_panorama_img">
		<?php if ( $text ){ ?>
            <div class="accordion-header" id="clproperty_listing_panorama_heading">
                <h3 class="mb-0">
                    <button class="btn collapsed" data-toggle="collapse" data-target="#panorama" aria-expanded="false" aria-controls="panorama">
                        <?php echo esc_html( $text ); ?>
                    </button>
                </h3>
        </div>
		<?php } ?>
        <div id="panorama" class="collapse"></div>
    </div>
<?php } ?>