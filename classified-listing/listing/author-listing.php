<?php
/**
 * Author Listing
 *
 * @author     RadiusTheme
 * @package    ClassifiedListing/Templates
 * @version    2.2.1.1
 */

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Pagination;
use radiustheme\ClProperty\Listing_Functions;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$author  = get_user_by( 'slug', get_query_var( 'author_name' ) );
$user_id = $author->ID;

$archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
// Define the query
$paged = Pagination::get_page_number();

$args = array(
	'post_type'      => rtcl()->post_type,
	'posts_per_page' => ! empty( $archive_settings['listings_per_page'] ) ? absint( $archive_settings['listings_per_page'] ) : 10,
	'paged'          => $paged,
	'author'         => $user_id,
	'meta_query'     => [
		[
			'key'     => '_rtcl_manager_id',
			'compare' => 'NOT EXISTS'
		]
	]
);

$user_ads_query = new \WP_Query( apply_filters( 'rtcl_user_listing_args', $args ) );


?>

<div class="rtcl-user-ad-listing-wrapper">
	<h2 class="mb-3">
		<?php
		/* translators: %s: author display name. */
		printf( esc_html__( "All ads from %s", "clproperty" ), esc_html( $author->display_name ) ) ?>
	</h2>
	<?php if ( $user_ads_query->have_posts() ) : ?>
		<div class="rtcl-listings rtcl-list-view rtcl-listing-wrapper"
			data-pagination='{"max_num_pages":<?php echo esc_attr( $user_ads_query->max_num_pages ) ?>, "current_page": 1, "found_posts":<?php echo esc_attr( $user_ads_query->found_posts ) ?>, "posts_per_page":<?php echo esc_attr( $user_ads_query->query_vars['posts_per_page'] ) ?>}'>
			<!-- the loop -->
			<?php
			Listing_Functions::listing_query( 'list', $user_ads_query );
			?>
			<!-- end of the loop -->

			<!-- Use reset postdata to restore original query -->
			<?php wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>
</div>
