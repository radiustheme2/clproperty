<?php
/**
 * Template Name: Listing Map
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

$map_listing_per_page=RDTheme::$options['listing_map_per_page'] ? RDTheme::$options['listing_map_per_page']:'-1';
?>
    <div id="primary" class="product-grid listing-inner">
        <div class="container-fluid full-width">
            <div class="clproperty-listing-map-wrapper">
				<?php
				if ( get_the_content() ) {
					the_content();
				} else {
					echo do_shortcode( '[rtcl_listings map="1" paginate="true" limit="' . $map_listing_per_page . '"]' );
				}
				?>
            </div>
        </div>
    </div>
	<?php get_footer(); ?>