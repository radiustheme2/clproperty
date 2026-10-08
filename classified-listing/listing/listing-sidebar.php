<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\RDTheme;
use radiustheme\ClProperty\Listing_Functions;

global $listing;

$enable_mortgage_calculator = Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_mortgage_calculator', false, 'checkbox' );
$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );

$sidebar_class = [
	'col-lg-3 col-md-10 offset-lg-0 offset-md-1',
	'order-2',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
} elseif ( $sidebar_position == "bottom" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-3 col-md-10 offset-lg-0 offset-md-1' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}
?>

<!-- Seller / User Information -->
<div id="sticky_sidebar" class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
    <div class="listing-sidebar">
		<?php
			if ( $listing->can_show_user() ) {
				if ( RDTheme::$options['show_user_info_on_details'] === 'show_store_info' ) {
					$listing->the_user_info();
				} else {
					Helper::get_custom_listing_template( 'listing-content-info' );
				}
			}
            if ( $enable_mortgage_calculator ) {
                clproperty_render_mortgage_calculator();
            }
		?>
        <!-- Social Profile  -->
	    <?php do_action( 'rtcl_single_listing_social_profiles'); ?>
        <!-- Business Hours  -->
        <?php do_action( 'rtcl_single_listing_business_hours' ); ?>

		<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>

		<?php if ( is_active_sidebar( 'single-property-sidebar' ) ): ?>
            <aside class="sidebar-widget">
				<?php dynamic_sidebar( 'single-property-sidebar' ); ?>
            </aside>
		<?php endif; ?>
    </div>
</div>
