<?php 
use Rtcl\Helpers\Functions as RtclFunctions;
use RtclStore\Helpers\Functions as StoreFunctions;
use \radiustheme\ClProperty\RDTheme;
use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;

defined( 'ABSPATH' ) || exit;

get_header( 'agent' );
$agent_archive_layout = RDTheme::$layout;
$search_item= isset($_GET['q']) ? $_GET['q']:'';
$content_column = "col-lg-8 col-sm-12 col-12";
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

$agent_query=new WP_Query(
    array(
        'post_type' =>'rtcl_agent',
        'post_status' =>'publish',
        'posts_per_page' =>-1,
        's'             =>$search_item
    )
);

if($agent_query->have_posts()){
    do_action( 'rtcl_before_agent_loop' );
	rtcl_agent_loop_start();

    while ( $agent_query->have_posts() ) {
        $agent_query->the_post();
        $user_id = get_post_meta( get_the_ID(), '_rtcl_user_id', true );
        if ( ! $user = get_user_by( 'id', $user_id ) ) {
            return;
        }
        $store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
        $name     = trim((string) implode(' ', array_filter([ $user->first_name, $user->last_name ])));
        $name     = $name ? $name : $user->display_name;
        $phone    = get_user_meta( $user_id, '_rtcl_phone', true );
        $pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
        $store    = new Store( $store_id );
        ?>
        <div class="agent-holder">
            <div class="agent-block">
                <div class="item-img">
                    <?php echo wp_kses_post($pp_id ? wp_get_attachment_image( $pp_id,
                        [
                            340,
                            340,
                        ] ) : get_avatar( $user_id, 340 )); ?>
                    <span class="listing-count">
                        <?php
                        $count = intval( count( $store->get_manager_listing_ids( $user_id ) ) );
                        $count = $count == 0 ? 1 : $count;
                        /* translators: %s: number of listings. */
                        printf( esc_html( _n( '%s Listing', '%s Listings', $count, 'clproperty' ) ),
                            absint( count( $store->get_manager_listing_ids( $user_id ) ) ) ); ?>
                    </span>
                </div>
                <div class="item-content">
                    <div class="item-title">
                        <h3 class="agent-name"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( $name ); ?></h3></a>
                        <h5 class="agency-name">
                            <a href="<?php echo esc_url( get_the_permalink( $store_id ) ); ?>"><?php echo esc_html( get_the_title( $store_id ) ); ?></a>
                        </h5>
                    </div>
                    <?php if ( $phone ): ?>
                        <div class="item-phone">
                            <i class="rtcl-icon rtcl-icon-phone"></i>
                            <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="item-contact">
                        <!--<i class="rtcl-icon rtcl-icon-envelope-open"></i>-->
                        <i class="rtcl-icon fas fa-envelope"></i>
                        <a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a>
                    </div>

                    <div class="details-btn">
                        <a class="btn btn-details mt-3" href=""><?php echo esc_html__( 'Details', 'clproperty' ) ?></a>
                    </div>
                </div>
                <div class="social-icon">
                    <?php
                    $social_list = Functions::get_user_social_profile( $user_id );
                    if ( ! empty( $social_list ) ) {
                        ?>
                        <a href="#" class="social-hover-icon social-link">
                            <i class="fas fa-share-alt"></i>
                        </a>
                        <?php
                        foreach ( $social_list as $item => $value ) { ?>
                            <a target="_blank" href="<?php echo esc_url( $value ) ?>">
                                <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
                            </a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php }
    rtcl_agent_loop_end();

    do_action( 'rtcl_after_agent_loop' );
}else {
	/**
	 * Hook: rtcl_no_agent_found.
	 *
	 * @hooked no_agent_found - 10
	 */
	?>
    <h2><?php echo esc_html("There is no agent by this name",'clproperty') ?></h2>
<?php }

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
wp_reset_postdata();

