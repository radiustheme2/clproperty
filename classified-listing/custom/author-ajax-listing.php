<?php
/**
 * @author  RadiusTheme
 * @since   1.5
 * @version 1.5
 */

namespace radiustheme\ClProperty;
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (!class_exists( 'RtclPro' )) return;

use Rtcl\Models\Listing;
use RtclPro\Helpers\Fns;
use RtclPro\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;

if ( !isset( $listing  ) ) {
	$listing = new Listing( get_the_ID() );
}

$listing_post = $listing->get_listing();

$category = $listing->get_categories();
$category = end( $category );

$type = Listing_Functions::get_listing_type( $listing );

$class  = ' rtcl-listing-item';
$class .= isset( $top_listing ) ? ' rtin-top' : '';
$class .= $listing->is_featured() ? ' featured-listing' : '';
$class .= method_exists('RtclPro\Helpers\Fns', 'is_mark_as_sold') && Fns::is_mark_as_sold($listing->get_id()) ? ' is-sold' : '';

?>

<div class="author-listing listing-item listing-list-each-1<?php echo esc_attr( $class ); ?>">
	<div class="item-block">
        <div class="item-block__figure">
            <?php $listing->the_thumbnail(); ?>
            <?php
                if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
                    echo '<span class="rtcl-sold-out" style="display:inline">' . esc_html( apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'clproperty' ) ) ) . '</span>';
                }
			?>
            <div class="item-block__tags">
                <a class="item-block__tag" href="#"><?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $type['label'] )  ?></a>
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
                    <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                        <i class="icon-rt-icon-compare-line"></i>
                    </a>
                <?php } ?>
		    </div>
        </div>
		<div class="item-block__content">
			<div class="rtin-content">
				<h3 class="item-block__heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php if (  $listing->has_location() ): ?>
                <div class="item-block__location">
                    <i class="icon-rt-icon-location-solid"></i>
                    <span class="item-block__location__name"><?php $listing->the_locations( true, false ); ?></span>
                </div>
                <?php 
                    $length=RDTheme::$options['listing_arexcerpt_limit'];
                    $excerpt=Helper::clproperty_excerpt($length);
                    echo "<div class='listing-excerpt'>";
                    echo wp_kses_post( $excerpt );
                    echo "</div>";
                ?>
                <?php endif; ?>
				<?php
					$listing->the_badges();
				?>
                <?php echo wp_kses_post( $listing->get_price_html() ); ?>
                    <div class="item-block__features">
                    <?php 
                        Helper::clproperty_listing_listable_fields($listing);
                    ?>
				</div>
			</div>
			
		</div>
	</div>

</div>

