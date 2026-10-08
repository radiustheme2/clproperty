<?php
/**
 * Listing Thumbnail
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Helper;
use Rtcl\Controllers\Hooks\TemplateHooks;
use radiustheme\ClProperty\Listing_Functions;
use RtclStore\Helpers\Functions as StoreFunction;

global $listing;
global $rtclIsAjax;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

$listing_type = Listing_Functions::get_listing_type( $listing );

?>
<div class="product-thumb">
    <a class="thumb-link" target="_blank" href="<?php echo esc_url($listing->get_the_permalink()); ?>"></a>
	<?php $images = Functions::get_listing_images( $listing->get_id() ); ?>
	<?php
	if ( StoreFunction::is_single_store() || ! empty( $rtclIsAjax)) {
		$listing->the_thumbnail();
		?>
		<div class="item-block__tags">
			<?php if ( ! empty( $listing_type && $listing->can_show_ad_type() ) ) : ?>
				<span class="listing-type-badge">
					<?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] ); ?>
				</span>
			<?php endif; ?>
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
	<?php } else {
		if ( is_page_template( 'templates/listing-map.php' )  ) { 
			 foreach ( $images as $index => $image ):
				echo wp_get_attachment_image( $image->ID, 'rtcl-thumbnail' );
				break;
			endforeach;
		} elseif('list' == $view) {
			Helper::clproperty_thumb_carousel( $listing->get_id() );
            ?>
            <div class="product-type">
                <?php if ( ! empty( $listing_type && $listing->can_show_ad_type() ) ) : ?>
                    <span class="listing-type-badge">
                        <?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] ); ?>
                    </span>
                <?php endif; ?>
            </div>
		<?php } else {
			foreach ( $images as $index => $image ):
				echo wp_get_attachment_image( $image->ID, 'rtcl-thumbnail' );
				break;
			endforeach;
		}
		if ( 'list' == $view ) {
            ?>
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
		<?php }
		if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
			echo '<span class="rtcl-sold-out">' . esc_html( apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'clproperty' ) ) ) . '</span>';
		}
		?>
		<?php if( 'grid' == $view ){
			$listing_type = Listing_Functions::get_listing_type( $listing );	
			?>
			<div class="product-badge-type">
				<?php if ( ! empty( $listing_type ) && $listing->can_show_ad_type()) : ?>
					<span class="listing-type-badge">
						<?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] ); ?>
					</span>
				<?php endif; ?>

                <div class="rtcl-listing-badge-wrap">
					<?php TemplateHooks::loop_item_badges(); ?>
                </div>
        	</div>
			<div class="listing-action">
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
		<?php } ?>
	<?php } ?>
</div>
