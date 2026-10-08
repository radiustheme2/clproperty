<?php
/**
 * Single store product listing
 *
 * @author     RadiusTheme
 * @package    classified-listing-store/templates
 * @version    1.2.31
 *
 * @var Store    $store
 * @var WP_Query $store_ads_query
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Listing_Functions;
use Rtcl\Helpers\Pagination;
use RtclStore\Models\Store;
use radiustheme\ClProperty\RDTheme;
use radiustheme\ClProperty\Helper;
use RtclPro\Helpers\Fns;

global $store;

$args = array(
    'post_type'      => rtcl()->post_type,
    'post_status'    => 'publish',
    'posts_per_page' => Functions::get_option_item('rtcl_archive_listing_settings', 'listings_per_page', 20),
    'author'         => $store->owner_id(),
    'paged'          => Pagination::get_page_number(),
);
$store_ads_query = new \WP_Query(apply_filters('rtcl_store_listing_args', $args));

?>
<div class="store-listing-list store-ad-listing-wrapper">
    <h2 class="store-heading"><?php esc_html_e("Our Listings", "clproperty") ?></h2>
    <?php
    if ($store_ads_query->have_posts()) : ?>
        <div class="rtcl-listings single-store-listing rtcl-listing-wrapper"
             data-pagination='{"max_num_pages":<?php echo esc_attr($store_ads_query->max_num_pages) ?>, "current_page": 1, "found_posts":<?php echo esc_attr($store_ads_query->found_posts) ?>, "posts_per_page":<?php echo esc_attr($store_ads_query->query_vars['posts_per_page']) ?>}'>
            <!-- the loop -->
            <?php
            while ($store_ads_query->have_posts()) : $store_ads_query->the_post();
                $listing = rtcl()->factory->get_listing(get_the_ID());
                $listing_type       = Listing_Functions::get_listing_type( $listing );
                ?>
                <div class="item-block item-block-store-listing">
                    <div class="item-block__figure">
						<?php echo wp_kses_post($listing->get_the_thumbnail( 'rtcl-thumbnail' )); ?>
						<div class="item-block__tags">
							<a class="item-block__tag" href="#"><?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] )  ?></a>
						</div>
						<div class="item-block_listing-action">
							<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
							<?php if ( Fns::is_enable_compare() ) {
								$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
								$selected_class = '';
								if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
									$selected_class = ' selected';
								}
								?>
								<a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-toggle="tooltip" data-placement="top"
								title="<?php esc_attr_e( "Compare", "clproperty" ) ?>"
								data-original-title="<?php esc_attr_e( "Compare", "clproperty" ) ?>" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
									<i class="icon-rt-icon-compare-line"></i>
								</a>
							<?php } ?>
						</div>
                     
					</div>
                    <div class="item-block__content">
						<h3 class="item-block__heading"><a href="<?php $listing->the_permalink(); ?>"><?php $listing->the_title(); ?></a></h3>
						<div class="item-block__location">
							<i class="icon-rt-icon-location-solid"></i>
							<span class="item-block__location__name"><?php  $listing->the_locations(); ?></span>
						</div>
					 
						<p class="item-block__text"> 
							<?php 
								$length=RDTheme::$options['listing_arexcerpt_limit'];
								$excerpt=Helper::clproperty_excerpt($length);
								echo wp_kses_post( $excerpt );
							?>
						</p>
						<?php echo wp_kses_post( $listing->get_price_html() ); ?>
						<div class="item-block__features">
						<?php 
							Helper::clproperty_listing_listable_fields($listing);
						?>
						</div>
                    </div>
                </div>
            <?php endwhile; ?>
            <!-- end of the loop -->

            <!-- Use reset postdata to restore original query -->
            <?php wp_reset_postdata(); ?>
        </div>
    <?php else:
        do_action('rtcl_no_listings_found');
    endif; ?>
</div>
