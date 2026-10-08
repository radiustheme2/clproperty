<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.2.31
 */

use Rtcl\Helpers\Functions as RtclFunctions;
use RtclStore\Helpers\Functions as StoreFunctions;
use \radiustheme\ClProperty\RDTheme;
use radiustheme\ClProperty\Helper;


defined( 'ABSPATH' ) || exit;

get_header( 'agent' );


$search_item= isset($_GET['q']) ? $_GET['q']:'';
if(isset($search_item) && !empty($search_item)){
	get_template_part('agent-search-form');
	return;
}

$agent_archive_layout = RDTheme::$layout;

$content_column = "col-lg-9 col-sm-12 col-12";
if('full-width' == $agent_archive_layout) {
	$content_column = "col-12";
}

/**
 * Hook: rtcl_before_main_content.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'rtcl_before_main_content' );

?>
<div class="rtcl-agents-archive-main rtcl-widget-is-sticky rtcl-widget-border-enable <?php echo esc_attr( $agent_archive_layout ) ?>">


<?php
echo "<div class='container'>";
echo "<div class='row agent-main-row'>";
echo "<div class='" . esc_attr( $content_column ) . "'>";

if ( rtcl()->wp_query()->have_posts()  ) {
	do_action( 'rtcl_before_agent_loop' );

	rtcl_agent_loop_start();

	while ( rtcl()->wp_query()->have_posts() ) : rtcl()->wp_query()->the_post();

		/**
		 * Hook: rtcl_agent_loop.
		 */
		do_action( 'rtcl_agent_loop' );
		RtclFunctions::get_template_part( 'content', 'agent' );

	endwhile;

	rtcl_agent_loop_end();


	/**
	 * Hook: rtcl_after_store_loop.
	 *
	 * @hooked TemplateHook::pagination() - 10
	 */
	do_action( 'rtcl_after_agent_loop' );
} else {
	/**
	 * Hook: rtcl_no_agent_found.
	 *
	 * @hooked no_agent_found - 10
	 */
	do_action( 'rtcl_no_agent_found' );
}

echo "</div>";
/**
 * rtcl_agent_sidebar hook.
 *
 * @hooked get_agent_sidebar - 10
 */
if('full-width' !== $agent_archive_layout) {
	do_action( 'rtcl_agent_sidebar' );
}

echo "</div>";
echo "</div>";

/**
 * Hook: rtcl_after_main_content.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
echo "</div>";

do_action( 'rtcl_after_main_content' );


get_footer( 'agent' );