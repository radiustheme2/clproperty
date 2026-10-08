<?php
/**
 * Listing Form builder
 *
 * @author    RadiusTheme
 * @package   classified-listing/templates
 * @version   3.0.0
 *
 * @var int $post_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div id="rtcl-form-builder-container">
    <div class="row">
        <div class="container">

            <div id="rtcl-form-builder">
                <div class="rtcl-fb-loader-container">
                    <div class="rtcl-fb-loader"></div>
                </div>
            </div>
            <?php if ( has_action( 'rtcl_fb_extra_form' ) ) { ?>
                <div id="rtcl-fb-extra-from-wrap">
                    <form enctype="multipart/form-data" id="rtcl-fb-extra-form">
                        <?php do_action( 'rtcl_fb_extra_form', $post_id ); ?>
                        <button type="submit" class="rtcl-btn rtcl-btn-primary rtcl-submit-btn">
                            <?php
                            if ( $post_id > 0 ) {
                                echo esc_html( apply_filters( 'rtcl_listing_form_update_btn_text', esc_html__( 'Update', 'clproperty' ) ) );
                            } else {
                                echo esc_html( apply_filters( 'rtcl_listing_form_submit_btn_text', esc_html__( 'Submit', 'clproperty' ) ) );
                            } ?>
                        </button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</div>