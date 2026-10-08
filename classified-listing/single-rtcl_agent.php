<?php
/**
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates
 * @version    1.0.0
 */

use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\RDTheme;
use Rtcl\Helpers\Functions as RtclFunctions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( 'agent' );

$agent_single_layout = RDTheme::$layout;

$content_column = "col-lg-9 col-sm-12 col-12";
if ( 'full-width' == $agent_single_layout ) {
	$content_column = "col-12";
}

/**
 * rtcl_before_main_content hook.
 *
 * @hooked rtcl_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked rtcl_breadcrumb - 20
 */
do_action( 'rtcl_before_main_content' );
?>
<div class="rtcl-agents-single-main rtcl-widget-is-sticky rtcl-widget-border-enable <?php echo esc_attr( $agent_single_layout ); ?>">
    <div class='container'>
        <div class='row agent-main-row'>
            <div class="<?php echo esc_attr( $content_column ); ?>">
                <?php
                    while ( have_posts() ) :
                        the_post();
                        RtclFunctions::get_template_part( 'content', 'single-agent' );
                    endwhile; // end of the loop.
                ?>
            </div>

            <?php
                /**
                 * rtcl_agent_sidebar hook.
                 *
                 * @hooked get_agent_sidebar - 10
                 */
                if ( 'full-width' !== $agent_single_layout ) {
            ?>
                <div id="sticky_sidebar" class="<?php Helper::the_sidebar_class(); ?>">
                    <aside class="sidebar-widget main-sidebar-wrapper">
                        <?php
                        do_action( 'rtcl_agent_contact_form' );
                        if ( RDTheme::$sidebar && is_active_sidebar( RDTheme::$sidebar ) ) {
                            dynamic_sidebar( RDTheme::$sidebar );
                        } elseif ( is_active_sidebar( 'sidebar' ) ) {
                            dynamic_sidebar( 'sidebar' );
                        }
                        ?>
                    </aside>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
/**
 * rtcl_after_main_content hook.
 *
 * @hooked rtcl_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'rtcl_after_main_content' );

get_footer( 'agent' );
