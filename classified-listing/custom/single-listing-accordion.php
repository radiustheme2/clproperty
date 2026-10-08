<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\Listing_Functions;
use radiustheme\ClProperty\RDTheme;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;

global $listing;
$custom_field_ids = Functions::get_custom_field_ids( $listing->get_last_child_category_id() );
$video_urls       = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}

$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );

?>
<div class="clproperty-listing-single-accordion single-product" id="clproperty_listing_single_accordion">
    <!-- Description -->
    <?php if ( $listing->get_the_content() ) { ?>
        <div class="clproperty-accordion-item">
            <div class="accordion-header" id="clproperty_listing_des_heading">
                <h3 class="mb-0">
                    <button class="btn" data-toggle="collapse" data-target="#clproperty_listing_des" aria-expanded="true" aria-controls="clproperty_listing_des">
                        <?php esc_html_e( 'Description', 'clproperty' ); ?>
                    </button>
                </h3>
            </div>
            <div id="clproperty_listing_des" class="collapse accordion-collapse show">
                <div class="clproperty-accordion-content">
                    <?php $listing->the_content(); ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if( Functions::isEnableFb() ){

        Helper::get_custom_listing_template( 'form-builder-cfg' );
    } else {
        ?>
        <!-- overview -->
        <?php if ( RDTheme::$options['overview_show_hide'] && ! empty( $custom_field_ids ) ){ ?>
        <div class="clproperty-accordion-item">
            <?php if ( RDTheme::$options['overview_text'] ) { ?>
                <div class="accordion-header" id="clproperty_listing_cfg_heading">
                    <h3 class="mb-0">
                        <button class="btn" data-toggle="collapse" data-target="#clproperty_listing_cfg" aria-expanded="true" aria-controls="clproperty_listing_cfg">
                            <?php echo esc_html( RDTheme::$options['overview_text'] ); ?>
                        </button>
                    </h3>
                </div>
            <?php } ?>
            <div id="clproperty_listing_cfg" class="collapse accordion-collapse show">
                <div class="clproperty-accordion-content">
                    <?php Helper::get_custom_listing_template( 'cfg-amenities' ); ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- Features & Amenities -->
        <?php if ( RDTheme::$options['feature_aminities_show_hide'] && ! empty( $custom_field_ids ) ){ ?>
        <div class="clproperty-accordion-item">
            <?php if ( RDTheme::$options['feature_text'] ) { ?>
                <div class="accordion-header" id="clproperty_listing_features_heading">
                    <h3 class="mb-0">
                        <button class="btn" data-toggle="collapse" data-target="#clproperty_listing_features" aria-expanded="true" aria-controls="clproperty_listing_features">
                            <?php echo esc_html( RDTheme::$options['feature_text'] ); ?>
                        </button>
                    </h3>
                </div>
            <?php } ?>
            <div id="clproperty_listing_features" class="collapse accordion-collapse show">
                <div class="clproperty-accordion-content">
                    <?php $listing->the_custom_fields(); ?>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php } ?>

    <!-- Floor Plan -->
    <?php Helper::get_custom_listing_template( 'floor-plan' ); ?>

    <!-- Video -->
    <?php if ( ! empty( $video_urls ) ) {?>
        <div class="clproperty-accordion-item">
            <div class="accordion-header" id="clproperty_listing_video_heading">
                <h3 class="mb-0">
                    <button class="btn accordion-collapse" data-toggle="collapse" data-target="#clproperty_listing_video" aria-expanded="true" aria-controls="clproperty_listing_video">
                    <?php esc_html_e( 'Property Video', 'clproperty' ); ?>
                    </button>
                </h3>
            </div>
            <div id="clproperty_listing_video" class="collapse accordion-collapse show">
                <div class="clproperty-accordion-content">
                    <?php $listing->the_thumbnail( 'rtcl-gallery' ); ?>
                    <div class="video-icon">
                        <a class="play-btn popup-youtube" href="<?php echo esc_url( $video_urls[0] ); ?>">
                            <i class="rt-play-circle"></i>
                        </a>
                    </div>
                </div>
            </div>  
        </div>
    <?php } ?>
    <!-- yelp review -->
    <?php
    if ( Listing_Functions::is_enable_yelp_review() ) {
        Helper::get_custom_listing_template( 'yelp-review' );
    }
    ?>
    <!-- 360 degree view -->
    <?php
    if ( Listing_Functions::is_enable_panorama_view() ) {
        Helper::get_custom_listing_template( 'panorama-view' );
    }
    ?>
    <!-- Map -->
    <?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
    <div class="clproperty-accordion-item">
        <div class="accordion-header" id="clproperty_listing_map_heading">
            <h3 class="mb-0">
                <button class="btn" data-toggle="collapse" data-target="#clproperty_listing_map" aria-expanded="true" aria-controls="clproperty_listing_map">
                    <?php esc_html_e( 'Map Location', 'clproperty' ); ?>
                </button>
            </h3>
        </div>
        <div id="clproperty_listing_map" class="collapse accordion-collapse show">
            <div class="clproperty-accordion-content">
                <?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
            </div>
        </div>    
    </div>   
    <?php } ?>
	<?php do_action( 'rtcl_single_listing_review' ); ?>
</div>
