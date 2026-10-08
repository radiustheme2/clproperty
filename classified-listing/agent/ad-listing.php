<?php
/**
 * Single Agent Listing
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates/agent
 * @version    1.0.0
 *
 * @var $store_id
 * @var $user_id
 */

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Listing_Functions;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;
use radiustheme\ClProperty\RDTheme;
use radiustheme\ClProperty\Helper;
use RtclPro\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp;

$agent_store_per_page=RDTheme::$options['listing_agent_listing_per'] ? RDTheme::$options['listing_agent_listing_per']:10;

$args = array(
	'post_type'      => rtcl()->post_type,
	'posts_per_page' => $agent_store_per_page,
    'author'        =>$user_id
);

$listing_data=[
    'author_id'=>$user_id
];

$args2 = array(
	'post_type'      => rtcl()->post_type,
	'posts_per_page' => $agent_store_per_page,
    'meta_query'    =>[
        [
			'key'   => '_rtcl_manager_id',
			'value' => $user_id
		]
    ]
);

$agent_ads_query = new \WP_Query( apply_filters( 'rtcl_agent_listing_args', $args ) );
?>

<div class="rtcl-agent-ad-listing-wrapper">
    <h2 class="agent-listing-title"><?php esc_html_e( 'My Listing', 'clproperty' ); ?></h2>
	<?php
	if ( $agent_ads_query->have_posts() ) : ?>
        <div class="rtcl-listings single-agent-listing rtcl-listing-wrapper">
            <!-- the loop -->
			<?php
			while ( $agent_ads_query->have_posts() ) : $agent_ads_query->the_post();
				$listing = rtcl()->factory->get_listing( get_the_ID() );
				$listing_type       = Listing_Functions::get_listing_type( $listing );
				?>
				<div class="item-block item-block-agent-listing">
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
		<div class="text-center">
				<a href="#" id="loadMore_agent_listing" class="loadMore btn-text" data-listing-items='<?php echo wp_json_encode($listing_data);?>'><?php esc_html_e( 'More Listing', 'clproperty' ) ?></a>
		</div>
	<?php else: ?>
        <div class="no-listing-found">
			<?php do_action( 'rtcl_no_listings_found' ); ?>
        </div>
	<?php endif; ?>
</div>

<?php  $agent_ads_query2 = new \WP_Query( apply_filters( 'rtcl_agent_listing_store_args', $args2 ) );?>

<div class="rtcl-agent-store-ad-listing-wrapper">
	<h2 class="agent-listing-title"><?php esc_html_e( 'Store Listing', 'clproperty' ); ?></h2>
	<?php
	if ( $agent_ads_query2->have_posts() ) : ?>
        <div class="single-agent-store-listing rtcl-listing-wrapper">
            <!-- the loop -->
			<?php
			while ( $agent_ads_query2->have_posts() ) : $agent_ads_query2->the_post();
				$listing = rtcl()->factory->get_listing( get_the_ID() );
				$listing_type       = Listing_Functions::get_listing_type( $listing );
			?>
			<div class="item-block item-block-agent-listing">
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
		<div class="text-center">
				<a href="#" id="loadMore_agent_store_listing" class="loadMore btn-text" data-listing-items='<?php echo wp_json_encode($listing_data);?>'><?php esc_html_e( 'More Listing', 'clproperty' ) ?></a>
		</div>
	<?php else: ?>
        <div class="no-listing-found">
			<?php do_action( 'rtcl_no_listings_found' ); ?>
        </div>
	<?php endif; ?>
</div>


