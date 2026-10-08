<?php
/**
 * @var WP_Query $rtcl_related_query
 * @var array    $slider_options
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $rtcl_related_query->have_posts() ) {
	return;
}

?>
<div class="related-product product-grid mt-5">
    <!-- Header -->
    <div class="row">
        <div class="col-md-12">
            <div class="section-title-wrapper">
                <div class="title-inner-wrapper">
                    <div class="top-sub-title-wrap">
                        <span class="top-sub-title">
                            <i class="fas fa-circle" aria-hidden="true"></i>
                            <?php echo esc_html__( 'Similar Properties', 'clproperty' ); ?>
                        </span>
                    </div>
                    <h2 class="main-title"><?php echo esc_html__( 'Related Properties', 'clproperty' ); ?></h2>
                </div>

                <a class="clproperty-btn" href="<?php echo esc_url( get_post_type_archive_link( 'rtcl_listing' ) ) ?>"><?php echo esc_html__( 'All Properties',
						'clproperty' ) ?></a>
            </div>
        </div>
    </div>

    <!-- Properties Content -->
    <div class="row">
		<?php
		global $post;
		while ( $rtcl_related_query->have_posts() ):
			$rtcl_related_query->the_post();
			$listing = rtcl()->factory->get_listing( get_the_ID() );
			?>
            <div class="col-lg-4 col-md-6">
                <div class="product-box style2">
					<?php do_action( 'rtcl_listing_loop_item_start' ); ?>
					<?php do_action( 'rtcl_listing_loop_item' ); ?>
                </div>
            </div>
		<?php endwhile;
		wp_reset_postdata();
		?>
    </div>
</div>