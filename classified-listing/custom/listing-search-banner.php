<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;

$currency = Functions::get_currency_symbol();
extract( $data );
$loc_text = esc_attr__( 'Select Location', 'clproperty' );
$typ_text = esc_attr__( 'Select Type', 'clproperty' );
$cat_text = esc_attr__( 'Select Category', 'clproperty' );

$is_form_visible = ( $can_search_by_category || $can_search_by_keyword || $can_search_by_listing_types || $can_search_by_location || $can_search_by_radius_search );
if ( $is_form_visible === false ) {
	printf( '<div class="alert alert-danger text-center" role="alert"><i class="fas fa-info-circle mr-2"></i>%s</div>',
		esc_html__( 'Please choose at least one or more fields for showing the widget',
			'clproperty' ) );

	return;
}
$layout=$layout ? $layout:'default';
?>

<div class="banner-box banner-layout-<?php echo esc_attr( $layout ); ?>">
    <form action="<?php echo esc_url( Functions::get_filter_form_url() ); ?>"
          class="advance-search-form rtcl-widget-search-form">
		<?php $permalink_structure = get_option( 'permalink_structure' ); ?>
		<?php if ( ! $permalink_structure ) : ?>
            <input type="hidden" name="post_type" value="rtcl_listing">
		<?php endif; ?>

		<?php if ( $can_search_by_category &&  $layout =='default' ) : ?>
            <div class="listing-category-list">
                <div class="search-item rtin-category search-radio search-radio-check">
                    <ul class="list-inline">
						<?php
						$terms = get_terms( [
							'taxonomy'   => rtcl()->category,
							'hide_empty' => true,
						] );
						foreach ( $terms as $term ) {
							$term_icon     = get_term_meta( $term->term_id, '_rtcl_icon', true );
							$term_img      = get_term_meta( $term->term_id, '_rtcl_image', true );
							$term_img_html = wp_get_attachment_image( $term_img, 'full' );
							?>
                            <li class="<?php echo esc_attr( $term->slug ) ?>">
                                <label for="<?php echo esc_attr( $term->slug ) ?>"
                                       >
									<?php
									if ( 'image' == $icon && $term_img_html ) {
										echo "<div class='category-image'>" . wp_kses_post( $term_img_html ) . "</div>";
									} elseif ( $term_icon ) {
										printf( "<i class='rtcl-icon rtcl-icon-%s'></i>", esc_attr( $term_icon ) );
									}
									?>
                                    <span><?php echo esc_html( $term->name ) ?></span>
                                    <input
										<?php //echo esc_attr( $is_checked ); ?>
                                            type="radio"
                                            name="<?php echo esc_attr( 'rtcl_category' ) ?>"
                                            id="<?php echo esc_attr( $term->slug ) ?>"
                                            value="<?php echo esc_attr( $term->slug ) ?>"
                                    >
                                </label>
                            </li>
							<?php
						}
						?>
                    </ul>
                </div>

            </div>
		<?php endif; ?>

		<?php if ( $can_search_by_listing_types && ( 'home1' == $layout ) ): ?>
            <div class="ad-type-wrapper search-radio-check">
                <ul class="list-inline">
					<li><?php echo esc_html('Select by Type','clproperty'); ?></li>
					<?php
					$listing_types = Functions::get_listing_types();
					$listing_types = empty( $listing_types ) ? [] : $listing_types;
					?>
					<?php foreach ( $listing_types as $key => $listing_type ): ?>
                        <li>
                            <label for="<?php echo esc_attr( $key ); ?>">
                                <input
									type="radio"
									name="<?php echo esc_attr( 'filters[ad_type]' ) ?>"
									id="<?php echo esc_attr( $key ); ?>"
									value="<?php echo esc_attr( $key ); ?>"
                                >
								<span><?php echo esc_html( $listing_type ); ?></span>
                            </label>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
		<?php endif; ?>
        <div class="listing-filter-area">
			<div class="search-area">
				<?php if ( $can_search_by_keyword ): ?>
					<div class="search-item search-keyword search-select">
						<div class="input-group">
							<input type="text" data-type="listing" name="s" class="rtcl-autocomplete form-control"
								placeholder="<?php esc_attr_e( 'What are you looking for!', 'clproperty' ); ?>"
								value="<?php if ( isset( $_GET['s'] ) ) {
									echo esc_attr( $_GET['s'] );
								} ?>"/>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $can_search_by_category && (  'default' != $layout ) ): ?>
					<div class="search-item search-select rtin-category">
						<?php
						wp_dropdown_categories( [
							'show_option_none'  => $cat_text,
							'option_none_value' => '',
							'taxonomy'          => rtcl()->category,
							'name'              => 'rtcl_category',
							'id'                => 'rtcl-category-search-' . wp_rand(),
							'class'             => 'select2 rtcl-category-search',
							'selected'          => get_query_var( 'rtcl_category' ),
							'hierarchical'      => true,
							'value_field'       => 'slug',
							'depth'             => Functions::get_category_depth_limit(),
							'show_count'        => false,
							'hide_empty'        => false,
						] );
						?>
					</div>
				<?php endif; ?>
				

				<?php if ( $can_search_by_listing_types && ( 'home1' != $layout ) ): ?>
					<div class="search-item search-select">
						<select class="select2" name="filters[ad_type]"
								data-placeholder="<?php echo esc_attr( $typ_text ); ?>">
							<option value=""><?php echo esc_html( $typ_text ); ?></option>
							<?php
							$listing_types = Functions::get_listing_types();
							$listing_types = empty( $listing_types ) ? [] : $listing_types;
							?>
							<?php foreach ( $listing_types as $key => $listing_type ): ?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $listing_type ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && $can_search_by_location && 'local' === Functions::location_type() ): ?>
					<div class="search-item search-select rtin-location">
						<?php
						wp_dropdown_categories( [
							'show_option_none'  => $loc_text,
							'option_none_value' => '',
							'taxonomy'          => rtcl()->location,
							'name'              => 'rtcl_location',
							'id'                => 'rtcl-location-search-' . wp_rand(),
							'class'             => 'select2 rtcl-location-search',
							'selected'          => get_query_var( 'rtcl_location' ),
							'hierarchical'      => true,
							'value_field'       => 'slug',
							'depth'             => Functions::get_location_depth_limit(),
							'show_count'        => false,
							'hide_empty'        => true,
						] );
						?>
					</div>
				<?php endif; ?>

				<?php if ( $can_search_by_radius_search ): ?>
					<div class="search-item search-keyword rtcl-radius-group">
						<div class="input-group rtcl-geo-address-field">
							<input id="rtcl-geo-address-search" type="text" name="geo_address" autocomplete="off"
								value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( $_GET['geo_address'] ) : '' ?>"
								placeholder="<?php esc_attr_e( "Enter location", "clproperty" ) ?>"
								class="form-control rtcl-geo-address-input"/>
							<i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
							<input type="hidden" class="latitude" name="center_lat"
								value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( $_GET['center_lat'] ) : '' ?>">
							<input type="hidden" class="longitude" name="center_lng"
								value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( $_GET['center_lng'] ) : '' ?>">
						</div>
					</div>
					<?php if ( $can_search_by_radius_distance ) :
						$default_radius = apply_filters('clproperty_widget_default_radius', '');
						?>
						<div class="search-item search-radius">
							<div class="input-group">
								<div class="rtcl-search-input-button">
									<input type="number" class="form-control" name="distance"
										value="<?php echo ! empty( $_GET['distance'] ) ? absint( $_GET['distance'] ) : esc_attr( $default_radius ) ?>"
										placeholder="<?php esc_attr_e( "Radius", "clproperty" ); ?>">
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endif ?>
			</div>
            <div class="search-item search-btn">
				<?php if ( $can_search_by_custom_field ): ?>
                    <button class="advanced-btn collapsed" type="button"><i class="icon-rt-icon-filter-line"></i></button>
				<?php endif; ?>
                <button type="submit" class="submit-btn">
					<?php esc_html_e( 'Search', 'clproperty' ); ?>
                    <i class="icon-rt-icon-search-line"></i>
                </button>
            </div>
        </div>

        <div class="advanced-search-box" id="advanced-search">
            <div class="advanced-box advanced-banner-box">
                <?php
                    if ( $can_search_by_custom_field ):
                        $args      = [
                            'is_searchable' => true,
                        ];
                        $fields_id = Functions::get_cf_ids( $args );

                        $html = '';
                        foreach ( $fields_id as $field ) {
                            $html .= Listing_Functions::get_advanced_search_field_html( $field );
                        }
                        echo wp_kses_post( $html );
                    endif;
                ?>
	            <?php if ( $can_search_by_price ): ?>
                    <div class="search-item">
                        <div class="price-range">
                            <label><?php esc_html_e( 'Price Range', 'clproperty' ); ?></label>

                            <!-- RTCL-এর মতো NoUISlider HTML -->
                            <div class="rtcl-price-range-wrap">
                                <div class="rtcl-price-range-slider rtcl-noUiSlider"
                                     data-min="<?php echo esc_attr( $min_price ?? 0 ); ?>"
                                     data-max="<?php echo esc_attr( $max_price ?? 100000 ); ?>"
                                     data-step="500">
                                </div>
                                <div class="rtcl-range-slider-input-wrap">
						            <?php
						            $get_min = isset($_GET['filter_price']) ? explode(',', $_GET['filter_price'])[0] : '';
						            $get_max = isset($_GET['filter_price']) ? explode(',', $_GET['filter_price'])[1] : '';
						            ?>
                                    <input type="number"
                                           name="filter_price_min"
                                           class="rtcl-form-control rtcl-range-slider-input min"
                                           placeholder="Min"
                                           value="<?php echo esc_attr($get_min); ?>">

                                    <span class="separator">—</span>

                                    <input type="number"
                                           name="filter_price_max"
                                           class="rtcl-form-control rtcl-range-slider-input max"
                                           placeholder="Max"
                                           value="<?php echo esc_attr($get_max); ?>">
                                </div>
                            </div>

                            <!-- Hidden input for RTCL Ajax Filter -->
                            <input type="hidden" name="filter_price" value="<?php echo esc_attr($_GET['filter_price'] ?? ''); ?>">
                        </div>
                    </div>
	            <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php
//    add_action('wp_footer', function() {
//    if (!is_admin() && (is_post_type_archive('rtcl_listing') || is_tax(rtcl()->category) || is_tax(rtcl()->location))) {
//    ?>
<!--    <script>-->
<!--        document.addEventListener('DOMContentLoaded', function () {-->
<!--            const sliders = document.querySelectorAll('.rtcl-price-range-slider');-->
<!--            sliders.forEach(slider => {-->
<!--                const min = parseInt(slider.dataset.min);-->
<!--                const max = parseInt(slider.dataset.max);-->
<!--                const step = parseInt(slider.dataset.step) || 1000;-->
<!--                const wrap = slider.closest('.rtcl-price-range-wrap');-->
<!--                const inputMin = wrap.querySelector('.rtcl-range-slider-input.min');-->
<!--                const inputMax = wrap.querySelector('.rtcl-range-slider-input.max');-->
<!--                const hiddenInput = wrap.closest('.search-item').querySelector('input[name="filter_price"]');-->
<!---->
<!--                noUiSlider.create(slider, {-->
<!--                    start: [-->
<!--                        inputMin.value ? parseInt(inputMin.value) : min,-->
<!--                        inputMax.value ? parseInt(inputMax.value) : max-->
<!--                    ],-->
<!--                    connect: true,-->
<!--                    step: step,-->
<!--                    range: { min: min, max: max },-->
<!--                    format: { to: value => Math.round(value), from: value => value }-->
<!--                });-->
<!---->
<!--                slider.noUiSlider.on('update', function (values, handle) {-->
<!--                    const val = values[handle];-->
<!--                    if (handle === 0) inputMin.value = val;-->
<!--                    else inputMax.value = val;-->
<!---->
<!--                    // Update hidden filter_price = min,max-->
<!--                    hiddenInput.value = values[0] + ',' + values[1];-->
<!--                });-->
<!---->
<!--                // Input change → slider-->
<!--                [inputMin, inputMax].forEach(input => {-->
<!--                    input.addEventListener('change', function () {-->
<!--                        slider.noUiSlider.set(handle === 0 ? [this.value, null] : [null, this.value]);-->
<!--                    });-->
<!--                });-->
<!--            });-->
<!--        });-->
<!--    </script>-->
<!--	--><?php
//}
//});
?>
