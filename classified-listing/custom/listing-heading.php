<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Text;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\RDTheme;
use RtclMarketplace\Hooks\ActionHooks;
use Rtcl\Controllers\Hooks\TemplateHooks;
use radiustheme\ClProperty\Listing_Functions;
use RtclClaimListing\Helpers\Functions as ClaimFunctions;

global $listing;

if ( ! $listing ) {
	return;
}

$style            = Helper::listing_single_style();
$average_rating   = $listing->get_average_rating();
$rating_count     = $listing->get_rating_count();
$can_report_abuse = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_report_abuse', '', 'checkbox' ) ? true : false;
$enable_printer_button = Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_printer_button', false, 'checkbox' );
$listing_type     = Listing_Functions::get_listing_type( $listing );

?>
<div class="product-heading" id="listing-home">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="header-info">
                <div class="product-condition">
		            <?php
                        if ( $listing->has_category() && $listing->can_show_category() ):
                            $category = $listing->get_categories();
                            $category = end( $category );
                            ?>
                            <a class="category" href="<?php echo esc_url(
                                get_term_link(
                                    $category->term_id,
                                    $category->taxonomy
                                )
                            ); ?>"><?php echo esc_html( $category->name ) ?></a>
                        <?php endif;
		            ?>
		            <?php if ( ! empty( $listing_type ) && $listing->can_show_ad_type() ) : ?>
                        <span class="listing-type-badge">
                            <?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] ); ?>
                        </span>
		            <?php endif; ?>
		            <?php if ( $listing->is_featured() && Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', 'featured', 'multi_checkbox' ) ) : ?>
			            <?php TemplateHooks::listing_featured_badge( $listing ); ?>
		            <?php endif; ?>
		            <?php
		            if ( Functions::is_listing() && function_exists( 'rtclClaimListing' ) && ClaimFunctions::is_enable_claim_badge() ) {
			            $claimed = get_post_meta( $listing->get_id(), 'rtcl_claimed_listing', true );
			            if ( 'yes' === $claimed ) {
				            ?>
                            <span class="badge rtcl-claim-badge"><?php esc_html_e( 'Claimed', 'clproperty' ); ?></span>
				            <?php
			            }
		            } ?>
                </div>
                <h2 class="product-title"><?php $listing->the_title(); ?></h2>
                <ul class="entry-meta">
                    <?php if ( $listing->can_show_location() && current( $listing->user_contact_location_at_single() ) ): ?>
                        <li>
                            <i class="icon-rt-icon-location-solid"></i><?php echo wp_kses_post( implode( '<span class="rtcl-delimiter">,</span> ', array_map( 'esc_html', $listing->user_contact_location_at_single() ) ) ); ?>
                        </li>
                    <?php endif; ?>
                    <?php if ( $listing->can_show_date() ): ?>
                        <li><i class="icon-rt-icon-clock"></i><?php $listing->the_time(); ?></li>
                    <?php endif; ?>
                    <?php if ( $listing->can_show_views() ): ?>
                        <li>
                            <i class="fa-solid fa-eye"></i><?php echo wp_kses_post( sprintf(
                            /* translators: %s: view count. */
                            _n( 'View: <span>%s</span>', 'Views: <span>%s</span>', $listing->get_view_counts(), 'clproperty' ),
                                                                            number_format_i18n( $listing->get_view_counts() ) ) ); ?>
                        </li>
                    <?php endif; ?>
                    <?php if ( ! empty( $rating_count ) ): ?>
                        <li class="product-rating">
                            <div class="item-icon">
                                <?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
                            </div>
                            <div class="item-text"><?php echo wp_kses_post( apply_filters( 'clproperty_rating_count_format',
                                                                            sprintf(
                            /* translators: %s: review count. */
                            __( '(<span>%s</span>) Reviews', 'clproperty' ),
                            esc_html( $rating_count )
                        ) ) ); ?></div>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="product-price-wrap">
                    <?php if ( $listing->can_show_price() ): ?>
                        <div class="product-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <?php if ( RDTheme::$options['show_listing_button_area'] ) : ?>
                <div class="btn-area">
	                <?php
                        if ( class_exists('RtclMarketplace') ) {
                            ActionHooks::add_buy_button();
                        }
	                ?>
                    <ul>
                        <li>
                            <?php if ( Fns::is_enable_compare() ) {
                                $compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
                                $selected_class = '';
                                if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
                                    $selected_class = ' selected';
                                }
                                ?>
                                <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#"
                                    data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                                    <i class="icon-rt-icon-compare-line"></i>
                                </a>
                            <?php } ?>
                        </li>
                        <li><?php echo wp_kses_post( Functions::get_favourites_link( $listing->get_id() ) ); ?></li>
                        <?php if ( $can_report_abuse ): ?>
                            <li>
                                <?php if ( is_user_logged_in() ): ?>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#rtcl-report-abuse-modal">
                                        <i class='fas fa-bug'></i>
                                        <?php echo esc_html( Text::report_abuse() ); ?>
                                    </a>
                                <?php else: ?>
                                    <a href="javascript:void(0)" class="rtcl-require-login">
                                        <i class='fas fa-bug'></i>
                                        <?php echo esc_html( Text::report_abuse() ); ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                        <?php if( $listing->the_social_share(false) ) : ?>
                        <li>
                            <a href="#" id="share-btn"><i class="icon-rt-icon-share-line"></i></a>
                            <div class="share-icon">
                                <?php $listing->the_social_share(); ?>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php if ( $enable_printer_button ) : ?>
                        <li><a href="#" onclick="window.print();"><i class="icon-rt-icon-fax"></i></a></li>
                        <?php endif; ?>
                        <?php
                        if ( function_exists( 'rtclClaimListing' ) && ClaimFunctions::claim_listing_enable() ){ ?>
                            <li class='report-abuse-li'>
                                <?php if ( is_user_logged_in() ): ?>
                                    <span data-toggle="tooltip" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#rtcl-claim-listing-modal">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </a>
                                    </span>
                                <?php else: ?>
                                    <a href="javascript:void(0)" data-toggle="tooltip" class="rtcl-require-login" data-original-title="<?php echo esc_html( ClaimFunctions::get_claim_action_title() ); ?>">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="rtcl-report-abuse-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="rtcl-report-abuse-form" class="form-vertical">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="rtcl-report-abuse-modal-label"><?php esc_html_e( 'Report Abuse', 'clproperty' ); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rtcl-report-abuse-message"><?php esc_html_e( 'Your Complaint', 'clproperty' ); ?>
                            <span class="rtcl-star">*</span></label>
                        <textarea class="form-control" id="rtcl-report-abuse-message" rows="3"
                                  placeholder="<?php esc_attr_e( 'Message... ', 'clproperty' ); ?>"
                                  required></textarea>
                    </div>
                    <div id="rtcl-report-abuse-g-recaptcha"></div>
                    <div id="rtcl-report-abuse-message-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-primary"><?php esc_html_e( 'Submit', 'clproperty' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php do_action( 'rtcl_single_listing_after_action', $listing->get_id() ); ?>