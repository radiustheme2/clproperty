<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.2.31
 */

use Rtcl\Helpers\Functions as RtclFunctions;
use RtclStore\Helpers\Functions as StoreFunctions;
use radiustheme\ClProperty\Helper;

defined( 'ABSPATH' ) || exit;

get_header( 'store' );

/**
 * Hook: rtcl_before_main_content.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'rtcl_before_main_content' );

?>
<div class="container rtcl-widget-border-enable rtcl-widget-is-sticky">
	<div class="row">
		<div class="<?php Helper::the_layout_class(); ?>">
			<?php if ( rtcl()->wp_query()->have_posts() ) {?>
					<div class="product-wrap">
						<?php
						/**
						 * Hook: rtcl_before_listing_loop.
						 *
						 * @hooked TemplateHooks::output_all_notices() - 10
						 * @hooked TemplateHooks::listings_actions - 20
						 *
						 */
						do_action( 'rtcl_before_store_loop' );

						StoreFunctions::store_loop_start();

						while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

							/**
							 * Hook: rtcl_listing_loop.
							 */
							do_action( 'rtcl_store_loop' );

							RtclFunctions::get_template_part( 'content', 'store' );

						endwhile;

						StoreFunctions::store_loop_end();
						?>
					</div>
					<?php
					/**
					 * Hook: rtcl_after_store_loop.
					 *
					 * @hooked TemplateHook::pagination() - 10
					 */
					do_action( 'rtcl_after_store_loop' );
				} else {
					/**
					 * Hook: rtcl_no_stores_found.
					 *
					 * @hooked no_listings_found - 10
					 */
					do_action( 'rtcl_no_stores_found' );
				}
			?>
		</div>
		<?php
		/**
		 * rtcl_store_sidebar hook.
		 *
		 * @hooked get_store_sidebar - 10
		 */
		if ( Helper::has_sidebar() ) {
			do_action( 'rtcl_store_sidebar' );
		}
		?>
	</div>
</div>
<?php

/**
 * Hook: rtcl_after_main_content.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rtcl_after_main_content' );

get_footer( 'store' );
