<?php
/**
 * @var WP_Query $rtcl_query
 * @var array    $instance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use radiustheme\ClProperty\Listing_Functions;


?>
<div class="rtcl rtcl-widget-listings">
    <div class="<?php echo esc_attr( $instance['wrapper_classes'] ); ?>"
         data-options="<?php echo esc_attr( wp_json_encode( $instance['slider_options'] ) ); ?>">
		<?php
		while ( $rtcl_query->have_posts() ):
			$rtcl_query->the_post();
			$listing            = rtcl()->factory->get_listing( get_the_ID() );
			$listing_type       = Listing_Functions::get_listing_type( $listing );
			$listing_meta       = $img = $labels = $uInfo = $time = $location = $category = $price = null;
			$img_position_class = $instance['show_image'] && $instance['image_position'] == "left" ? 'rtcl-flex' : '';
			$listing_type       = Listing_Functions::get_listing_type( $listing );
			?>
            <div <?php Functions::listing_class( [ 'rtcl-widget-listing-item', 'listing-item', $img_position_class ] ); ?>>
				<?php
				if ( $instance['show_image'] ) {
					$img = sprintf( "<div class='listing-thumb %s'><a href='%s' title='%s'>%s</a><span class='listing-type-badge'>%s %s</span></div>",
					    esc_attr($instance['image_position']),
						get_the_permalink(),
						esc_html( get_the_title() ),
						$listing->get_the_thumbnail( 'rtcl-thumbnail' ),
						apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ),
						isset($listing_type['label']) ? $listing_type['label']:''
					);
				}
				if ( $instance['show_labels'] ) {
					$labels = $listing->badges( false );
				}
				if ( $instance['show_date'] ) {
					$time = sprintf( '<li class="date"><i class="rtcl-icon rtcl-icon-clock" aria-hidden="true"></i>%s</li>',
						$listing->get_the_time()
					);
				}
				if ( $instance['show_location'] ) {
					$location = sprintf( '<li class="location"><i class="icon-rt-icon-location-solid" aria-hidden="true"></i>%s</li>',
						$listing->the_locations( false )
					);
				}
				if ( $instance['show_price'] ) {
					$price = sprintf( '<div class="listing-price">%s</div>', $listing->get_price_html() );
				}
				$author_html = '';
				if ( $instance['show_user'] ) {
					$author_html = sprintf( '<span><i class="rtcl-icon rtcl-icon-user" aria-hidden="true"></i>%s</span>', get_the_author() );
				}
				$views_html = '';
				if ( $instance['show_views'] ) {
					$views      = absint( get_post_meta( get_the_ID(), '_views', true ) );
					$views_html = sprintf( '<span><i class="rtcl-icon rtcl-icon-eye" aria-hidden="true"></i>%s</span>',
						sprintf(
					/* translators: %s: view count. */
					_n( "%s view", "%s views", $views, 'clproperty' ),
					number_format_i18n( $views )
				)
					);
				}
				if ( $author_html || $views_html ) {
					$uInfo = sprintf( '<li class="info">%s</li>',
						$author_html . $views_html
					);
				}

				if ( $uInfo || $time || $location ) {
					$listing_meta = sprintf( '<ul class="listing-meta">%s%s%s</ul>', $uInfo, $time,
						$location );
				}

				if ( $instance['show_category'] && $listing->has_category() ) {
					$categories = $listing->get_categories();
					$categories = end( $categories );

					$category = sprintf( '<div class="property-type"><a href="%s">%s</a></div>',
						esc_url( get_term_link( $categories->term_id, $categories->taxonomy ) ),
						$listing->the_categories( false )
					);
				}

				$title = sprintf( '<h3 class="listing-title rtcl-listing-title"><a href="%1$s" title="%2$s">%2$s</a></h3>',
					get_the_permalink(),
					esc_html( get_the_title() )
				);

				$item_content = sprintf( '<div class="item-content">%s %s %s %s %s</div>',
					$labels,
					$category,
					$title,
					$listing_meta,
					$price );
				echo wp_kses_post( $img ) . wp_kses_post( $item_content );
				?>

            </div>
		<?php
		endwhile;
		wp_reset_postdata();
		?>
    </div>
	<?php if ( $instance['pagination'] && in_array( $instance['view'], [ 'grid', 'list' ] ) ) {
		Pagination::pagination( $rtcl_query );
	} ?>
</div>