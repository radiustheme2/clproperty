<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 * @var $id int
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty_Core\YelpReview;
use radiustheme\ClProperty\Listing_Functions;
global $listing;

if ( Functions::isEnableFb() && Listing_Functions::check_fb_custom_group_field_set() ) {
	$categories = get_post_meta( $listing->get_id(), "yelp_categories" );
} else {
	$categories = get_post_meta( $listing->get_id(), 'clproperty_yelp_categories', true );
}

$location   = implode( ', ', $listing->user_contact_location_at_single() );

?>
<?php if ( ! empty( $categories ) ) {  ?>
	<div class="clproperty-accordion-item section_yelp_nearby_places">
		<div class="accordion-header" id="clproperty_listing_yelp_heading">
			<h3 class="mb-0">
				<button class="btn" data-toggle="collapse" data-target="#clproperty_listing_yelp" aria-expanded="true" aria-controls="clproperty_listing_yelp">
				<?php esc_html_e( 'Yelp Nearby Places', 'clproperty' ); ?>
				</button>
			</h3>
		</div>
		<div id="clproperty_listing_yelp" class="collapse show">
			<?php
			if ( ! empty( $categories ) ){ ?>
				<div class="clproperty-accordion-content">
				<?php $yelp = new YelpReview();

				foreach ( $categories as $term ) {
					$businessList = $yelp->query_api( $term, $location );
					if ( empty( $businessList ) ) {
						continue;
					}

					?>
					<div class="media">
						<?php if ( $yelp->get_yelp_category_icon( $term ) ): ?>
							<div class="item-icon text-royalblue">
								<i class="<?php echo esc_attr( $yelp->get_yelp_category_icon( $term ) ); ?>"></i>
							</div>
						<?php endif; ?>
						<div class="media-body">
							<h4 class="item-title">
								<?php echo esc_html( $yelp->get_yelp_category_title( $term ) ); ?>
							</h4>
							<ul class="organization-list">
								<?php
								foreach ( $businessList as $business ) {
									$name        = $business->name;
									$rating      = $business->rating;
									$distance    = $business->distance * 0.00062137;
									$reviewCount = $business->review_count;
									?>
									<li>
										<div class="institute-info">
											<div class="institute-name">
												<h5><?php echo esc_html( $name ); ?></h5>
											</div>
											<div class="distance">
											    <?php /* translators: %s: distance in miles. */
										echo esc_html( sprintf( __( "(%s miles distance)", 'clproperty' ), number_format( $distance, 2 ) ) ); ?>
											</div>
										</div>
										<div class="institue-rating">
											<div class="item-rating">
												<?php YelpReview::print_yelp_rating( $rating ); ?>
											</div>
											<div class="item-reviews"><?php /* translators: %s: review count. */
										echo wp_kses_post( sprintf( __( "(<span>%s</span>) Reviews", 'clproperty' ), absint( $reviewCount ) ) ); ?>
											</div>
										</div>
									</li>
									<?php
								}
								?>
							</ul>
						</div>
					</div>
					<?php
				} ?>
				</div>	
			<?php }
			?>
		</div>
	</div>
<?php } ?>