<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Controllers\Ajax\PublicUser;
use RtclMarketplace\Hooks\ActionHooks;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Controllers\Hooks\TemplateHooks;
use radiustheme\ClProperty_Core\YelpReview;
use RtclStore\Controllers\Hooks\RtclApplyHook;
use RtclStore\Helpers\Functions as StoreFunctions;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as NewTemplateHooks;


class Listing_Functions {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_filter' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_action' ] );
		add_action( 'template_redirect', [ $this, 'disable_lazy_load' ] );
		add_action( 'the_content', [ $this, 'rt_set_post_view_count' ], 9999 );
		add_action('rtcl_listing_form_after_save_or_update',[$this,'listing_form_save'],12,5); // save extra listing form fields
		add_action( 'wp_ajax_delete_panorama_attachment', [ $this, 'delete_panorama_attachment' ] );
		add_action( 'clproperty_listing_grid_search_filer', [ $this, 'listing_map_filter' ] );
		add_action( 'rtcl_shortcode_before_listings_loop_start', [ $this, 'listing_map_filter' ] );
		add_action( 'wp_ajax_delete_floor_attachment', [ $this, 'delete_floor_attachment' ] );
		remove_action( 'rtcl_single_listing_inner_sidebar', [ ActionHooks::class, 'add_buy_button' ], 5 );
        
		// Change Store Breadcrumb Title
        add_filter( 'rtcl_store_register_post_type_args', [ $this, 'modify_store_post_breadcrumb_args' ] );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify2' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_get_icon_list_modify2' ] );
		add_filter( 'rtcl_store_the_excerpt', [ $this, 'rtcl_store_the_excerpt' ] );
		add_filter( 'rtcl_format_price_range', [ $this, 'rtcl_format_price_range' ], 10, 3 );
		add_filter( 'rtcl_type_prefix', [ $this, 'rtcl_type_prefix' ] );
		add_filter( 'rtcl_general_settings_options', [ $this, 'rtcl_general_settings_options' ] );

		// Change Store Category label
		add_filter( 'rtcl_register_store_category_args',
			function ( $label ) {
				$label['labels']['name']      = esc_html_x( 'Agency Categories', 'Agency Category Name', 'clproperty' );
				$label['labels']['menu_name'] = esc_html_x( 'Agency Categories', 'Agency Category Name', 'clproperty' );

				return $label;
			} );

		// Change Store Category label
		add_filter( 'rtcl_store_myaccount_store_title',
			function ( $store_label ) {
				$store_label = esc_html__( 'Agency', 'clproperty' );

				return $store_label;
        } );

		add_filter( 'rtcl_single_listing_settings_options', [ __CLASS__, 'single_listing_mortgage_option' ] );
		add_filter( 'rtcl_single_listing_settings_options', [ __CLASS__, 'single_listing_printer_button_option' ] );
//        add_filter( 'rtcl_force_classic_style', '__return_true' );
	}


	public static function render() {
		$filter_price = $_GET['filter_price'] ?? '';
		$vals = $filter_price ? explode(',', $filter_price) : [0, 100000];
		$min_price = 0;
		$max_price = 100000;
		$step = 100;

		echo '<div class="rtcl-price-range-wrap">
                <div class="rtcl-price-range-slider rtcl-noUiSlider" 
                     data-min="'.esc_attr($min_price).'" 
                     data-max="'.esc_attr($max_price).'" 
                     data-step="'.esc_attr($step).'">
                </div>
                <div class="rtcl-range-slider-input-wrap">
                    <input type="number" class="rtcl-range-slider-input min" value="'.esc_attr($vals[0]).'" placeholder="Min">
                    <span class="separator">—</span>
                    <input type="number" class="rtcl-range-slider-input max" value="'.esc_attr($vals[1]).'" placeholder="Max">
                </div>
              </div>
              <input type="hidden" name="filter_price" value="'.esc_attr($filter_price).'">';
	}

	
	function rtcl_general_settings_options( $options ) {
		unset( $options['load_bootstrap'] );

		return $options;
	}

	function rtcl_type_prefix( $prefix ) {
		if ( RDTheme::$options['remove_listing_type_prefix'] ) {
			return null;
		}
		if ( RDTheme::$options['listing_type_prefix_text'] ) {
			return esc_html( RDTheme::$options['listing_type_prefix_text'] );
		}

		return $prefix;
	}

	function rtcl_format_price_range( $price, $from, $to ) {
		/* translators: %1$s: price from, %2$s: price to. */
		$price = sprintf(
			_x(
				'<div class="rtcl-price-range"><span class="price-from">%1$s</span> <span class="dash">&ndash;</span> <span class="price-to">%2$s</span></div>',
				'Price range: from-to',
				'clproperty'
			),
			is_numeric( $from ) ? Functions::price( $from ) : $from,
			is_numeric( $to ) ? Functions::price( $to ) : $to
		);

		return $price;
	}

	function disable_lazy_load() {
		$is_listing_archive = Functions::is_listings() || Functions::is_listing_taxonomy();
		if ( $is_listing_archive || is_singular( 'rtcl_listing' ) ) {
			add_filter( 'wp_lazy_loading_enabled', '__return_false' );
		}
	}

	public function rtcl_store_the_excerpt( $excerpt ) {
		$excerpt = wp_trim_words( $excerpt, 20, '' );

		return $excerpt;
	}

	public function rt_set_post_view_count( $content ) {
		//Set store View Count 
		if ( is_singular() ) {
			$rt_store_views_key = 'rt_post_views_count';
			$store_view_count   = get_post_meta( get_the_ID(), $rt_store_views_key, true );
			if ( '' == $store_view_count ) {
				$store_view_count = 0;
				delete_post_meta( get_the_ID(), $rt_store_views_key );
				add_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			} else {
				$store_view_count ++;
				update_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			}
		}

		return $content;
	}

	public static function rt_get_post_view_count( $storeID ) {
		//Get store View Count 
		$rt_store_views_key = 'rt_post_views_count';
		$store_view_count   = get_post_meta( $storeID, $rt_store_views_key, true );

		return $store_view_count;
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function modify_store_post_breadcrumb_args( $args ) {
		$labels = [
			'name'               => _x( 'Agencies', 'Post Type General Name', 'clproperty' ),
			'singular_name'      => _x( 'Agency', 'Post Type Singular Name', 'clproperty' ),
			'menu_name'          => esc_html__( 'Agency', 'clproperty' ),
			'name_admin_bar'     => esc_html__( 'Agency', 'clproperty' ),
			'all_items'          => esc_html__( 'Agencies', 'clproperty' ),
			'add_new_item'       => esc_html__( 'Add New Agency', 'clproperty' ),
			'add_new'            => esc_html__( 'Add New', 'clproperty' ),
			'new_item'           => esc_html__( 'New Agency', 'clproperty' ),
			'edit_item'          => esc_html__( 'Edit Agency', 'clproperty' ),
			'update_item'        => esc_html__( 'Update Agency', 'clproperty' ),
			'view_item'          => esc_html__( 'View Agency', 'clproperty' ),
			'search_items'       => esc_html__( 'Search Agency', 'clproperty' ),
			'not_found'          => esc_html__( 'No stores found', 'clproperty' ),
			'not_found_in_trash' => esc_html__( 'No stores found in Trash', 'clproperty' ),
		];

		$args['label']       = esc_html__( 'Agency', 'clproperty' );
		$args['description'] = esc_html__( 'Agency Description', 'clproperty' );
		$args['labels']      = $labels;

		return $args;
	}

	public function rtcl_get_icon_list_modify2( $icons_lists ){
		$new_icons = [
			" icon-rt-icon-motel",
			" icon-rt-icon-phone2",
			" icon-rt-icon-phone-line",
			" icon-rt-icon-phone-line2",
			" icon-rt-icon-play",
			" icon-rt-icon-plus-line",
			" icon-rt-icon-property",
			" icon-rt-icon-property2",
			" icon-rt-icon-quotes",
			" icon-rt-icon-quotes2",
			" icon-rt-icon-quotes3",
			" icon-rt-icon-search-line",
			" icon-rt-icon-share-line",
			" icon-rt-icon-shop-line",
			" icon-rt-icon-support",
			" icon-rt-icon-twitter-solid",
			" icon-rt-icon-user-line",
			" icon-rt-icon-user-line2",
			" icon-rt-icon-verified",
			" icon-rt-icon-villa",
			" icon-rt-icon-web",
			" icon-rt-icon-whatsapp-solid",
			" icon-rt-icon-youtube-solid",
			" icon-rt-icon-angel-down-tiny",
			" icon-rt-icon-angel-left-tiny",
			" icon-rt-icon-angel-right-tiny",
			" icon-rt-icon-apartments",
			" icon-rt-icon-arrow-right",
			" icon-heart-empty",
			" icon-rt-icon-awards",
			" icon-rt-icon-awards2",
			" icon-rt-icon-bath-area",
			" icon-rt-icon-bath-line",
			" icon-rt-icon-bed-line",
			" icon-rt-icon-calendar",
			" icon-rt-icon-certified",
			" icon-rt-icon-check-line",
			" icon-rt-icon-clock",
			" icon-rt-icon-commercial",
			" icon-rt-icon-compare-line",
			" icon-rt-icon-email",
			" icon-rt-icon-experiences",
			" icon-rt-icon-facebook-solid",
			" icon-rt-icon-fax",
			" icon-rt-icon-filter-line",
			" icon-rt-icon-grid",
			" icon-rt-icon-heart-line",
			" icon-rt-icon-home",
			" icon-rt-icon-hotel",
			" icon-rt-icon-hotel2",
			" icon-rt-icon-instagram-solid",
			" icon-rt-icon-land",
			" icon-rt-icon-listing-line",
			" icon-rt-icon-list-view",
			" icon-rt-icon-location2",
			" icon-rt-icon-location-line",
			" icon-rt-icon-location-line-2",
			" icon-rt-icon-message",
			" icon-rt-icon-message2",
			" icon-angle-down",
		];
		return array_merge( $new_icons, $icons_lists );
	}

	public function classifiedads_rtcl_filter() {
		// Change Title Class
		add_filter(
			'rtcl_listing_loop_title_classes',
			function () {
				return $classes = 'item-title';
			}
		);

		// Change Store Category label
		add_filter( 'rtcl_store_category_label',
			function ( $label ) {
				return __( "Agency Categories", "clproperty" );
			} );

		// Override Related Listing Item Number
		add_filter(
			'rtcl_related_slider_options',
			function ( $options ) {
				$options['items']  = 3;
				$options['margin'] = 30;

				return $options;
			}
		);
		// Modify Add to favorite text
		add_filter(
			'rtcl_text_add_to_favourite',
			function ( $text ) {
				$text = '';

				return $text;
			}
		);
		// Modify remove from favorite text
		add_filter(
			'rtcl_text_remove_from_favourite',
			function ( $text ) {
				$text = '';

				return $text;
			}
		);
		// Remove report abuse text
		add_filter(
			'rtcl_text_report_abuse',
			function ( $text ) {
				return '';
			}
		);
		// Loop item wrapper start
		add_filter(
			'rtcl_loop_item_wrapper_start',
			function ( $content ) {
				global $listing;
				$content = sprintf( '<div class="product-content%s">', esc_attr( $listing->can_show_price() ? '' : ' no-price' ) );

				return $content;
			}
		);

		add_filter(
			'get_the_archive_title',
			function ( $title ) {
				if ( is_post_type_archive( 'rtcl_listing' ) ) {
					$title = esc_html__( 'Our Latest Properties', 'clproperty' );
				}
				if ( is_post_type_archive( 'store' ) ) {
					$title = esc_html__( 'Agency', 'clproperty' );
				}
				if ( is_post_type_archive( 'rtcl_agent' ) ) {
					$title = esc_html__( 'Agents', 'clproperty' );
				}
				return $title;
			}
		);

		// Override page template
		add_filter( 'template_include', [ $this, 'template_include' ] );

		// Override Store Ad count
		add_filter(
			'rtcl_store_get_ad_count_html',
			function ( $content, $obj, $count ) {
				$count_string = $count <= 0
					? apply_filters( 'clproperty_store_no_ad_text', __( "No Property", "clproperty" ), $this, $count )
					: sprintf(
						/* translators: %s: property count. */
						_n( "%s Property", "%s Properties", $count, 'clproperty' ),
						number_format_i18n( $count )
					);
				$content      = sprintf( '<span class="ads-count">%s</span>', $count_string );

				return $content;
			},
			10,
			3
		);

		// Change Unit Text
		add_filter(
			'rtcl_get_price_unit_list',
			function ( $unitList ) {
				$unitList['year']['short']  = esc_html__( "yr", 'clproperty' );
				$unitList['month']['short'] = esc_html__( "mo", 'clproperty' );
				$unitList['week']['short']  = esc_html__( "week", 'clproperty' );
				$unitList['day']['short']   = esc_html__( "day", 'clproperty' );
				$unitList['hour']['short']  = esc_html__( "hr", 'clproperty' );
				$unitList['sqft']['short']  = esc_html__( "sqft", 'clproperty' );
				$unitList['total']['short'] = esc_html__( "total", 'clproperty' );

				return $unitList;
			}
		);

		// Yelp Settings // Should place this hook. // rtcl_single_listing_settings_options //rtcl_general_settings_options
		add_filter( 'rtcl_single_listing_settings_options', [ $this, 'yelp_options' ] );

		// Panorama Settings
		add_filter( 'rtcl_single_listing_settings_options', [ $this, 'panorama_options' ] );

		// Floor Plan Settings
		add_filter( 'rtcl_single_listing_settings_options', [ $this, 'floor_plan_options' ] );

		// account page bootstrap
		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );

		add_action('admin_init', [$this, 'set_default_listings_per_row'], 1 );

		add_action( 'admin_init', function() {
			if ( ! get_option( 'rtcl_yelp_settings_migrated' ) ) {
				// data get from old options
				$old_yelp_options = get_option( 'rtcl_general_settings', [] );
				if ( ! empty( $old_yelp_options ) ) {
					$yelp_keys = [
						'enable_yelp_review',
						'yelp_api_key',
						'yelp_business_limit',
						'yelp_search_radius',
						'business_sort_by',
					];

					// Data get from new options if have
					$new_yelp_options = get_option( 'rtcl_single_listing_settings', [] );

					foreach ( $yelp_keys as $key ) {
						if ( isset( $old_yelp_options[ $key ] ) ) {
							$new_yelp_options[ $key ] = $old_yelp_options[ $key ];
						}
					}

					// Data save in new options
					update_option( 'rtcl_single_listing_settings', $new_yelp_options );
				}
				// Flag set
				update_option( 'rtcl_yelp_settings_migrated', 1 );
			}

			if ( ! get_option( 'rtcl_panorama_settings_migrated' ) ) {
				// data get from old options
				$old_panorama_options = get_option( 'rtcl_general_settings', [] );
				if ( ! empty( $old_panorama_options ) ) {
					$panorama_keys = [
						'enable_panorama',
						'panorama_section_label',
						'enable_panorama_autoload',
						'enable_panorama_control',
					];

					// Data get from new options if have
					$new_panorama_options = get_option( 'rtcl_single_listing_settings', [] );

					foreach ( $panorama_keys as $key ) {
						if ( isset( $old_panorama_options[ $key ] ) ) {
							$new_panorama_options[ $key ] = $old_panorama_options[ $key ];
						}
					}

					// Data save in new options
					update_option( 'rtcl_single_listing_settings', $new_panorama_options );
				}
				// Flag set
				update_option( 'rtcl_panorama_settings_migrated', 1 );
			}

			if ( ! get_option( 'rtcl_floor_plan_settings_migrated' ) ) {
				// data get from old options
				$old_floor_options = get_option( 'rtcl_general_settings', [] );
				if ( ! empty( $old_floor_options ) ) {
					$floor_keys = [
						'enable_floor_plan',
						'floor_plan_section_label',
					];

					// Data get from new options if have
					$new_floor_options = get_option( 'rtcl_single_listing_settings', [] );

					foreach ( $floor_keys as $key ) {
						if ( isset( $old_floor_options[ $key ] ) ) {
							$new_floor_options[ $key ] = $old_floor_options[ $key ];
						}
					}

					// Data save in new options
					update_option( 'rtcl_single_listing_settings', $new_floor_options );
				}
				// Flag set
				update_option( 'rtcl_floor_plan_settings_migrated', 1 );
			}
		});
	}

	public function set_default_listings_per_row() {
		// Get existing options
		$options = get_option('rtcl_archive_listing_settings', []);

		// Check if listings_per_row is already set - if yes, don't modify
		if (isset($options['listings_per_row'])) {
			return;
		}

		// Initialize with default values
		$default_values = [
			'desktop' => 2, // Default desktop value
			'tablet'  => 2, // Default tablet value
			'mobile'  => 1  // Default mobile value
		];

		// Only update if we don't have existing settings
		if (empty($options) || !isset($options['listings_per_row'])) {
			$options['listings_per_row'] = $default_values;
			update_option('rtcl_archive_listing_settings', $options);
		}
	}

	public function delete_panorama_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'clproperty_panorama_img' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function delete_floor_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			$floor_data                                 = get_post_meta( $_POST['post_id'], 'clproperty_floor_plan' );
			$floor_data[ $_POST['index'] ]['floor_img'] = '';
			delete_post_meta( $_POST['post_id'], 'clproperty_floor_plan' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	public function listing_map_filter( $atts ) {
		$loc_text = esc_attr__( 'Select Location', 'clproperty' );
		$cat_text = esc_attr__( 'Select Category', 'clproperty' );
		$typ_text = esc_attr__( 'Select Type', 'clproperty' );
		global $wp;
		?>
        <div class="listing-grid-box">
            <form action="<?php echo esc_url( home_url( $wp->request ) ); ?>" class="advance-search-form map-search-form rtcl-widget-search-form is-preloader">
                <div class="search-box">
                    <div class="search-item search-keyword w100">
                        <div class="input-group">
                            <input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
                                   placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'clproperty' ); ?>"
                                   value="<?php if ( isset( $_GET['q'] ) ) {
								       echo esc_attr( $_GET['q'] );
							       } ?>"/>
                        </div>
                    </div>

                    <div class="search-item search-select">
                        <select class="select2" name="filters[ad_type]"
                                data-placeholder="<?php echo esc_attr( $typ_text ); ?>">
							<?php
							$listing_types = Functions::get_listing_types();
							$listing_types = empty( $listing_types ) ? [] : $listing_types;
							$get_ad_type   = isset( $_REQUEST['filters']['ad_type'] ) ? $_REQUEST['filters']['ad_type'] : '';
							?>
                            <option selected="selected"><?php echo esc_html( $typ_text ); ?></option>
							<?php foreach ( $listing_types as $key => $listing_type ):
								$selected = null;
								if ( $get_ad_type == $key ) {
									$selected = 'selected=selected';
								}
								?>
                                <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ) ?>><?php echo esc_html( $listing_type ); ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>

					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && 'local' === Functions::location_type() ): ?>
                        <div class="search-item search-select rtin-location">
							<?php
							wp_dropdown_categories(
								[
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
									'hide_empty'        => false,
								]
							);
							?>
                        </div>
					<?php endif; ?>

                    <div class="search-item search-select rtin-category">
						<?php
						wp_dropdown_categories(
							[
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
							]
						);
						?>
                    </div>
                </div>

                <div class="search-box-2">
                    <div class="search-item price-item-box">
                        <div class="price-range">
                            <label><?php esc_html_e( 'Price:', 'clproperty' ); ?></label>
							<?php
							$currency  = Functions::get_currency_symbol();
							$data_form = '';
							$data_to   = '';
							if ( isset( $_GET['filters']['price']['min'] ) ) {
								$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
							}
							if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
								$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
							}
							$min_price = RDTheme::$options['listing_widget_min_price'];
							$max_price = RDTheme::$options['listing_widget_max_price'];
							?>
                            <input type="number"
                                   class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
                                   data-min="<?php echo isset( $min_price ) && ! empty( $min_price ) ? esc_attr( $min_price ) : 0; ?>"
                                   data-max="<?php echo isset( $max_price ) && ! empty( $max_price ) ? esc_attr( $max_price ) : 80000; ?>"
                                   data-prefix="<?php echo esc_html( $currency ) ?>"/>
                            <input type="hidden" class="min-volumn" name="filters[price][min]"
                                   value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
								       echo absint( $_GET['filters']['price']['min'] );
							       } ?>">
                            <input type="hidden" class="max-volumn" name="filters[price][max]"
                                   value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
								       echo absint( $_GET['filters']['price']['max'] );
							       } ?>">
                        </div>
                    </div>

                    <div class="search-item search-button">
                        <button class="rtcl-item-visible-btn">
                            <i class="icon-rt-icon-filter-line"></i>
                        </button>
                    </div>

                    <div class="search-item search-btn">
                        <button type="submit" class="submit-btn">
							<?php esc_html_e( 'Search', 'clproperty' ); ?>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="form-cf-items expanded-wrap">
                    <div class="cf-inner">
						<?php
						$group_id = [];
						if ( isset( RDTheme::$options['custom_group_individual'] ) ) {
							$group_id[] = RDTheme::$options['custom_group_individual'];
						}
						$args      = [
							'is_searchable'     => true,
							'exclude_group_ids' => $group_id,
						];
						$fields_id = Functions::get_cf_ids( $args );

						$html = '';
						foreach ( $fields_id as $field ) {
							$html .= self::get_advanced_search_field_html( $field );
						}
						Functions::print_html( $html, true );
						?>
                    </div>

                    <div class="search-item search-btn">
                        <button class="advanced-btn collapsed" type="button">
                            <i class="fas fa-plus"></i>
							<?php esc_html_e( 'Others Features', 'clproperty' ); ?>
                        </button>
                    </div>
                </div>

				<?php if ( ! empty( $group_id[0] ) ): ?>
					<?php
					$args      = [
						'is_searchable' => true,
						'group_ids'     => $group_id,
					];
					$fields_id = Functions::get_cf_ids( $args );
					?>
                    <div class="advanced-search-box" id="advanced-search">
                        <button class="button-times">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="advanced-box">
							<?php
							$html = '';
							foreach ( $fields_id as $field ) {
								$html .= self::get_advanced_search_field_html( $field );
							}
							Functions::print_html( $html, true );
							?>
                        </div>
                    </div>
				<?php endif; ?>
            </form>
        </div>
		<?php
	}

	public function yelp_options( $options ) {
		$options['yelp_section']        = [
			'title' => esc_html__( 'Yelp Settings', 'clproperty' ),
			'type'  => 'section',
		];
		$options['enable_yelp_review']  = [
			'title' => esc_html__( 'Enable Yelp Review', 'clproperty' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show yelp review in listing details page.', 'clproperty' ),
		];
		$options['yelp_api_key']        = [
			'title'       => esc_html__( 'Yelp API Key', 'clproperty' ),
			'type'        => 'text',
			'description' => esc_html__( 'Create API key from yelp and enter here', 'clproperty' ),
		];
		$options['yelp_business_limit'] = [
			'title'       => esc_html__( 'Business Limit', 'clproperty' ),
			'type'        => 'number',
			'default'     => 3,
			'css'         => 'width:100px',
			'description' => esc_html__( 'Number of business in per category', 'clproperty' ),
		];
		$options['yelp_search_radius']  = [
			'title'       => esc_html__( 'Radius', 'clproperty' ),
			'type'        => 'number',
			'default'     => 2000,
			'css'         => 'width:100px',
			'description' => esc_html__( 'A suggested search radius in meters', 'clproperty' ),
		];
		$options['business_sort_by']    = [
			'title'       => esc_html__( 'Business Sort by', 'clproperty' ),
			'description' => esc_html__( 'Suggestion to the search algorithm that the results be sorted by one of the these modes', 'clproperty' ),
			'type'        => 'select',
			'options'     => [
				'best_match'   => esc_html__( 'Best Match', 'clproperty' ),
				'rating'       => esc_html__( 'Rating', 'clproperty' ),
				'review_count' => esc_html__( 'Review Count', 'clproperty' ),
				'distance'     => esc_html__( 'Distance', 'clproperty' ),
			],
			'default'     => 'rating',
		];

		return $options;
	}

	public function panorama_options( $options ) {
		$options['panorama_section']         = [
			'title' => esc_html__( 'Panorama Settings', 'clproperty' ),
			'type'  => 'section',
		];
		$options['enable_panorama']          = [
			'title' => esc_html__( 'Enable Panorama', 'clproperty' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Show 360 degree view in listing details page.', 'clproperty' ),
		];
		$options['panorama_section_label']   = [
			'title'   => esc_html__( 'Section Label', 'clproperty' ),
			'type'    => 'text',
			'default' => '360° Virtual Tour',
		];
		$options['enable_panorama_autoload'] = [
			'title' => esc_html__( 'Autoload', 'clproperty' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Check this to load automatically', 'clproperty' ),
		];
		$options['enable_panorama_control']  = [
			'title' => esc_html__( 'Show Controls', 'clproperty' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Check this to show controls', 'clproperty' ),
		];

		return $options;
	}

	public function floor_plan_options( $options ) {
		$options['floor_plan_section']       = [
			'title' => esc_html__( 'Floor Plan Settings', 'clproperty' ),
			'type'  => 'section',
		];
		$options['enable_floor_plan']        = [
			'title' => esc_html__( 'Enable Floor Plan', 'clproperty' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Add floor plan features.', 'clproperty' ),
		];
		$options['floor_plan_section_label'] = [
			'title'   => esc_html__( 'Section Label', 'clproperty' ),
			'type'    => 'text',
			'default' => 'Floor Plan',
		];

		return $options;
	}

	public function classifiedads_rtcl_action() {
		$show_listing_custom_field = RDTheme::$options['show_listing_custom_fields'];
		add_filter(
			'rtcl_loop_listing_per_page',
			function ( $per_page ) {
				$post_per_pare = Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_top_per_page', 2 );
				if ( isset( $_GET['layout'] ) && 'fullwidth' == $_GET['layout'] ) {
					$per_page = $per_page < 10 ? 9 : 12;
					$per_page -= absint( $post_per_pare );
				}

				return $per_page;
			}
		);
		remove_action('wp_ajax_rtcl_user_ad_load_more', [PublicUser::class, 'rtcl_user_ad_load_more']);
		remove_action('wp_ajax_nopriv_rtcl_user_ad_load_more', [PublicUser::class, 'rtcl_user_ad_load_more']);
		remove_action( 'rtcl_edit_account_form_end', [ TemplateHooks::class, 'edit_account_form_submit_button' ], 10 );
		add_action( 'rtcl_edit_account_form_end', [ $this, 'edit_account_form_submit_button' ], 10 );
		remove_action( 'rtcl_after_listing_loop_item', [ NewTemplateHooks::class, 'sold_out_banner' ] );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 40 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_listing_title' ], 20 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_meta_buttons' ], 60 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_meta' ], 50 );
		remove_action( 'rtcl_listing_loop_action', [ NewTemplateHooks::class, 'view_switcher' ], 30 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );
		
		add_action( 'rtcl_listing_loop_action', [ NewTemplateHooks::class, 'view_switcher' ], 30 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_list_view_listing_type' ], 10 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_title_new' ], 20 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_meta_location' ], 50 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_excerpt' ], 55 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_listing_price_new' ], 60 );

		//author loadmore ajax listing
		add_action('wp_ajax_rtcl_user_ad_load_more', [$this, 'rtcl_user_ad_load_more']);
		add_action('wp_ajax_nopriv_rtcl_user_ad_load_more', [$this, 'rtcl_user_ad_load_more']);
		if ( $show_listing_custom_field ) {
			add_action( 'rtcl_listing_loop_item', [ NewTemplateHooks::class, 'loop_item_listable_fields' ], 60 );
		}
		add_action( 'rtcl_listing_loop_item', [ $this, 'listing_loop_item_author_meta' ], 70 );
		add_action( 'clproperty_listing_loop_top_actions', [ $this, 'top_action_wrapper_start' ], 10 );
		add_action( 'clproperty_listing_loop_top_actions', [ $this, 'top_action_wrapper_end' ], 40 );

		// Listing Form
		if ( self::is_enable_yelp_review() ) {
			add_action( "rtcl_listing_form", [ $this, 'listing_yelp_category' ], 25 );
		}
		remove_action( 'rtcl_listing_form_end', [ TemplateHooks::class, 'listing_form_submit_button' ], 50 );
		// Single Listing Hook
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_title' ], 5 );
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_meta' ], 10 );
		remove_action(
			'rtcl_single_listing_inner_sidebar',
			[
				TemplateHooks::class,
				'add_single_listing_inner_sidebar_custom_field',
			],
			10
		);
		remove_action(
			'rtcl_single_listing_inner_sidebar',
			[
				TemplateHooks::class,
				'add_single_listing_inner_sidebar_action',
			],
			20
		);

		// Store Archive Hooks
		remove_action( 'rtcl_before_store_loop_item', [ StoreHooks::class, 'open_store_link' ], 10 );
		remove_action( 'rtcl_after_store_loop_item', [ StoreHooks::class, 'close_store_link' ], 5 );
		remove_action( 'rtcl_after_store_loop_item', [ StoreHooks::class, 'rtcl_after_store_loop_item' ], 5 );
		remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'loop_item_store_title' ], 10 );
		remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'store_meta' ], 20 );

		//Store Settings rename to Agent Settings
		remove_filter( 'rtcl_registered_only_options', [ RtclApplyHook::class, 'add_registered_only_store_contact_option' ] );
		add_filter('rtcl_registered_only_options', [__CLASS__, 'add_registered_only_store_contact_option']);

		remove_filter( 'rtcl_get_admin_email_notification_options', [ RtclApplyHook::class, 'add_store_update_admin_email_notification' ] );
		add_filter( 'rtcl_get_admin_email_notification_options', [ __CLASS__, 'add_store_update_admin_email_notification' ] );

		remove_filter( 'rtcl_misc_settings_options', [ RtclApplyHook::class, 'add_misc_options' ] );
		add_filter( 'rtcl_misc_settings_options', [ __CLASS__, 'add_misc_options' ] );

		remove_filter('rtcl_recaptcha_form_list', [RtclApplyHook::class, 'add_recaptcha_store_contact_form']);
		add_filter('rtcl_recaptcha_form_list', [__CLASS__, 'add_recaptcha_store_contact_form']);

//		add_filter( 'rtcl_membership_settings_options', [ $this, 'rtcl_membership_settings_options' ] );

		//...End Store Settings rename to Agent Settings

		add_action( 'rtcl_store_loop_item', [ $this, 'store_top_content_start' ], 5 );
		add_action( 'rtcl_store_loop_item', [ $this, 'loop_item_store_title' ], 10 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_meta' ], 20 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_excerpt' ], 25 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_top_content_end' ], 25 );
		add_action( 'rtcl_store_loop_item', [ $this, 'store_bottom_content' ], 40 );

		//Hook for rtcl-agents
		add_filter( 'rtcl_agents_grid_columns_class', [ $this, 'rtcl_agents_grid_columns_class' ] );
	}

	//user loadmore listing function
	public static function rtcl_user_ad_load_more(){
		$complete = false;
		$html = '';
		$current_page = isset($_POST['current_page']) ? absint($_POST['current_page']) : 0;
		$max_num_pages = isset($_POST['max_num_pages']) ? absint($_POST['max_num_pages']) : 0;
		$user_id = isset($_POST['user_id']) ? absint($_POST['user_id']) : 0;
		$posts_per_page = isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : -1;

		if ($current_page && $max_num_pages && $user_id && $max_num_pages > $current_page) {
			$current_page++;
			$complete = true;
			$args = array(
				'post_type'      => rtcl()->post_type,
				'post_status'    => 'publish',
				'posts_per_page' => $posts_per_page ? $posts_per_page : -1,
				'author'         => $user_id,
				'paged'          => $current_page,
				'meta_query'     => [
					[
						'key'     => '_rtcl_manager_id',
						'compare' => 'NOT EXISTS'
					]
				]
			);
			$user_ads_query = new \WP_Query($args);

			if (!empty($user_ads_query->posts)) {
				ob_start();
				    Listing_Functions::listing_query( 'list', $user_ads_query );
				$html .= ob_get_clean();

			}
		} else {
			$current_page = $max_num_pages;
		}

		wp_send_json(array(
			'complete'     => $complete,
			'current_page' => $current_page,
			'html'         => $html
		));
	}
	public static function listing_query( $view, $rtcl_query, $rtcl_top_query = false, $map = false ) {
			if ( Fns::is_enable_top_listings() ) {
				if ( is_object( $rtcl_top_query ) && $rtcl_top_query->have_posts() ) {
					$top_listing = true;
					while ( $rtcl_top_query->have_posts() ) : $rtcl_top_query->the_post();
						Helper::get_custom_listing_template( 'author-ajax-listing',true, compact('top_listing') );
					endwhile;
					wp_reset_postdata();
				}
			}
			while ( $rtcl_query->have_posts() ) : $rtcl_query->the_post();
				Helper::get_custom_listing_template( 'author-ajax-listing');
			endwhile;
			wp_reset_postdata();
	}

	public static function add_registered_only_store_contact_option( $options ) {
		$options['store_contact'] = esc_html__( 'Agency contact information', 'clproperty' );

		return $options;
	}

	public static function add_store_update_admin_email_notification($options) {

		$options['store_update'] = esc_html__('Agency Update', 'clproperty');

		return $options;

	}

	public static function add_recaptcha_store_contact_form($list) {
		$list['store_contact'] = esc_html__('Agency contact form', 'clproperty');

		return $list;
	}

	public static function add_misc_options($options) {
		$position = array_search('image_size_thumbnail', array_keys($options));
		if ($position > -1) {
			$option = array(
				'store_logo_size'   => array(
					'title'       => esc_html__('Agency Logo', 'clproperty'),
					'type'        => 'image_size',
					'default'     => array('width' => 200, 'height' => 150, 'crop' => 'yes'),
					'options'     => array(
						'width'  => esc_html__('Width', 'clproperty'),
						'height' => esc_html__('Height', 'clproperty'),
						'crop'   => esc_html__('Hard Crop', 'clproperty'),
					),
					'description' => esc_html__('This image size is being used at the agency detail page and where agency link is given.', "clproperty")
				)
			);
			Functions::array_insert($options, $position, $option);
		}

		return $options;
	}

	public function rtcl_membership_settings_options( $settings ) {
		$fields = [
			'enable'                              => [
				'title'       => esc_html__( 'Membership', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'Enable Membership option', 'clproperty' ),
			],
			'enable_store'                        => [
				'title'       => esc_html__( 'Agency', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'All Agency functionality will be active', 'clproperty' ),
			],
			'enable_store_rating'                 => [
				'title'       => esc_html__( 'Agency rating', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'default'     => 'yes',
				'description' => esc_html__( 'Enable Agency rating. ', 'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable_store' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'enable_store_only_membership'        => [
				'title'       => esc_html__( 'Agency only for membership', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'Agency menu at My Account page will visible only for the valid membership users. ', 'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable_store' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'display_store_only_valid_membership' => [
				'title'       => esc_html__( 'Single agency only for membership', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'Single sgency page will display only for valid membership owner. If enable, sgency single page will be only visible until membership is active.',
					'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable_store' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'enable_store_manager'                => [
				'title'      => esc_html__( 'Agency Manager', 'clproperty' ),
				'label'      => esc_html__( 'Enable', 'clproperty' ),
				'type'       => 'checkbox',
				'dependency' => [
					'rules' => [
						'#rtcl_membership_settings-enable_store' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'enable_free_ads'                     => [
				'title'       => esc_html__( 'Free ads', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'description' => esc_html__( 'Enable free ad posting', 'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'number_of_free_ads'                  => [
				'title'       => esc_html__( 'Number of free ads', 'clproperty' ),
				'type'        => 'number',
				'default'     => 3,
				'description' => esc_html__( 'Number of ads to post as free with out membership, if membership is enabled.<br>If this field is blank dy default it will be 3',
					'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable'          => [
							'type'  => 'equal',
							'value' => 'yes',
						],
						'#rtcl_membership_settings-enable_free_ads' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'renewal_days_for_free_ads'           => [
				'title'       => esc_html__( 'Renewal days for Free ads number', 'clproperty' ),
				'type'        => 'number',
				'default'     => 30,
				'description' => esc_html__( 'Free ads number will be renew after this days.<br>If this field is blank it will be 30', 'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable'          => [
							'type'  => 'equal',
							'value' => 'yes',
						],
						'#rtcl_membership_settings-enable_free_ads' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
			'unlimited_free_ads_membership'       => [
				'title'       => esc_html__( 'Unlimited free ads for membership', 'clproperty' ),
				'label'       => esc_html__( 'Enable', 'clproperty' ),
				'type'        => 'checkbox',
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable'          => [
							'type'  => 'equal',
							'value' => 'yes',
						],
						'#rtcl_membership_settings-enable_free_ads' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
				'description' => esc_html__( 'Enable unlimited free ad posting for membership user.', 'clproperty' ),
			],
			'categories_of_free_ads'              => [
				'title'       => esc_html__( 'Allowed category for free ads', 'clproperty' ),
				'type'        => 'multi_checkbox',
				'options'     => StoreFunctions::get_first_level_category_array(),
				'description' => esc_html__( 'Select the specific category for free ads, Leave it un select to allow any category.', 'clproperty' ),
				'dependency'  => [
					'rules' => [
						'#rtcl_membership_settings-enable'          => [
							'type'  => 'equal',
							'value' => 'yes',
						],
						'#rtcl_membership_settings-enable_free_ads' => [
							'type'  => 'equal',
							'value' => 'yes',
						],
					],
				],
			],
		];

		return $fields;
	}

	public function edit_account_form_submit_button() {
		?>
        <div class="form-group row">
            <div class="col-sm-9">
                <input type="submit" name="submit" class="btn btn-primary" value="<?php esc_html_e( 'Update Account', 'clproperty' ); ?>"/>
            </div>
        </div>
		<?php
	}
	/**
	 * Agent archive page grid column
	 *
	 * @return string
	 */
	public function rtcl_agents_grid_columns_class() {
		$agent_archive_layout = RDTheme::$options['agent_archive_layout'];
		if ( 'full-width' == $agent_archive_layout ) {
			return 'columns-3';
		}

		return 'columns-2';
	}
	public function loop_item_excerpt() {
		global $listing;
		if ( $listing->can_show_excerpt() ) {
			$length=RDTheme::$options['listing_arexcerpt_limit'];
			$excerpt=Helper::clproperty_excerpt($length);
			echo "<div class='listing-excerpt'>";
			echo wp_kses_post( $excerpt );
			echo "</div>";
		}
		
	}
	public function loop_item_list_view_listing_type(){
		global $listing;
		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}
		if('list'==$view){
			$listing_type = Listing_Functions::get_listing_type( $listing );
			?>
			<div class="product-type">
				<?php if ( ! empty( $listing_type ) && $listing->can_show_ad_type()) : ?>
					<span class="listing-type-badge">
						<?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] ); ?>
					</span>
				<?php endif; ?>
        	</div>	
		<?php }
	}
	public function loop_item_listing_title_new() {
		global $listing;

		if ( $listing->has_category() ):
			$category = $listing->get_categories();
			$category = end( $category );
			?>
		   <?php if($listing->can_show_category() && ! Functions::is_listing() ) { ?>
				<div class="product-category">
					<a href="<?php echo esc_url(
						get_term_link(
							$category->term_id,
							$category->taxonomy
						)
					); ?>"><?php echo esc_html( $category->name ) ?></a>
				</div>
			<?php } ?>
		<?php endif;
		echo '<h3 class="' . esc_attr( apply_filters( 'rtcl_listing_loop_title_classes', 'listing-title rtcl-listing-title' ) ) . '"><a href="' . esc_url( get_the_permalink( $listing->get_id() ) ) . '">'
		     . esc_html( get_the_title( $listing->get_id() ) ) . '</a></h3>';
	}
	public function loop_item_meta_location() {
		global $listing;
		?>
        <ul class="entry-meta">
			<?php if ( $listing->has_location() && $listing->can_show_location() ): ?>
                <li><i class="icon-rt-icon-location-solid"></i><?php $listing->the_locations(); ?></li>
			<?php endif; ?>

	        <?php if ( $listing->can_show_date() ): ?>
                <li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
	        <?php endif; ?>

        </ul>
		<?php
	}
	public static function loop_item_listing_listing_price_new(){
		global $listing;
		global $rtclIsAjax;
		if((StoreFunctions::is_single_store() || ! empty( $rtclIsAjax))){
			$length=RDTheme::$options['listing_arexcerpt_limit'];
			$excerpt = explode(' ', get_the_excerpt($listing->get_id()), $length);
			if (count($excerpt)>=$length) {
				array_pop($excerpt);
				$excerpt = implode(" ",$excerpt).'';
			} else {
				$excerpt = implode(" ",$excerpt);
			}

			$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
			echo "<div class='listing-excerpt'>";
			echo wp_kses_post( $excerpt );
			echo "</div>";
		}
		 if ( $listing->can_show_price() ): ?>
            <div class="product-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
		<?php endif; 

	}
	public static function is_enable_yelp_review() {
		return Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_yelp_review', false, 'checkbox' );
	}

	public static function is_enable_panorama_view() {
		return Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_panorama', false, 'checkbox' );
	}

	public static function is_enable_floor_plan() {
		return Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_floor_plan', false, 'checkbox' );
	}

	public function listing_yelp_category( $post_id ) {
		$selectedCat = get_post_meta( $post_id, "clproperty_yelp_categories", true );
		$selectedCat = empty( $selectedCat ) ? [] : $selectedCat;
		$categories  = YelpReview::yelp_categories();
		Functions::get_template( "listing-form/yelp-category", compact( 'post_id', 'selectedCat', 'categories' ) );
	}

	public function listing_loop_item_author_meta() {
		global $listing;
		$show_listing_author_mata = RDTheme::$options['listing_author_meta'];
		$author_btn_text 		  = RDTheme::$options['listing_author_btn_text'];
		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}
		?>
		<?php if($show_listing_author_mata){ ?>
			<div class="product-bottom-content">
				<ul>
					<?php if ( $listing->can_show_user() && method_exists( $listing, 'get_the_author_url' ) ): ?>
						<li class="item-author">
							<div class="media">
								<div class="item-img">
									<?php
									$avatar_attr = [
										'class'      => 'author-avatar',
										'extra_attr' => 'data-toggle="tooltip" data-placement="right" data-original-title="' . $listing->get_author_name() . '"',
									];
									Helper::get_listing_author_iamge( $listing, 40, 'Author', $avatar_attr );
									?>
								</div>
								<div class="media-body">
									<div class="item-title">
										<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
											<a class="author-link" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
												<?php echo esc_html( $listing->get_author_name() ); ?>
											</a>
										<?php else: ?>
											<?php $listing->the_author(); ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</li>
					<?php endif; ?>

					<li class="action-btn">
						<a class="btn btn-primary" href="<?php $listing->the_permalink(); ?>">
							<?php echo esc_html($author_btn_text); ?>
						</a>
					</li>

				</ul>
			</div>
		<?php }
	}

	public function get_listing_floor_attachment_id( $post_id, $file ) {
		if ( $file['error'] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			get_template_part( ABSPATH . "/wp-admin" . '/includes/image.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/file.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/media.php' );
		}

		Filters::beforeUpload();
		// you can use WP's wp_handle_upload() function:
		$status = wp_handle_upload(
			$file,
			[
				'test_form' => false,
			]
		);
		Filters::afterUpload();

		if ( $status && ! isset( $status['error'] ) ) {
			// $filename should be the path to a file in the upload directory.
			$filename = $status['file'];

			// Check the type of tile. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = [
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			];
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename );
		}

		return isset( $attach_id ) ? $attach_id : 0;
	}

	public function listing_form_save( $listing, $type, $cat_id, $new_listing_status, $data ) {
		$files = $data['files']; 
		if ( isset( $_POST['clproperty_floor_plan'] )) {
			$raw_data = $_POST['clproperty_floor_plan'];
			$sanitized_data = [];
			$count          = 0;
			foreach ( $raw_data as $floors ) {
				$floor_plan = [];
				$attach_id  = 0;
				foreach ( $floors as $key => $value ) {
					if ( $key === 'title' || $key === 'size') {
						$floor_plan[ $key ] = sanitize_text_field( $value );
					} elseif ( $key === 'bed' || $key === 'bath' || $key === 'parking' ) {
						$floor_plan[ $key ] = absint( $value );
					} elseif ( $key === 'description' ) {
						$floor_plan[ $key ] = sanitize_textarea_field( $value );
					}
				}
				if ( ! empty( $files['clproperty_floor_img'] ) && empty( $floors['attachment_id'] ) ) {
					$attachmentData = $files['clproperty_floor_img'];
					foreach ( $attachmentData as $attachmentKey => $attachmentValue ) {
						$image[ $attachmentKey ] = $attachmentValue[ $count ];
					}
					if ( ! empty( $image['name'] ) ) {
						$attach_id = $this->get_listing_floor_attachment_id( $listing->get_id(), $image );
					}
					$count ++;
				} elseif ( ! empty( $floors['attachment_id'] ) ) {
					$attach_id = $floors['attachment_id'];
					$count ++;
				}
				if ( ! empty( $attach_id ) ) {
					$floor_plan['floor_img'] = $attach_id;
				}
				if ( ! empty( $floor_plan ) ) {
					$sanitized_data[] = $floor_plan;
				}
			} 
			if ( ! empty( $sanitized_data ) ) {
				update_post_meta( $listing->get_id(), 'clproperty_floor_plan', $sanitized_data );
			}
		}

		if ( self::is_enable_yelp_review() ) {
			if ( isset( $_POST['clproperty_yelp_categories'] ) ) {
				$raw_data = $_POST['clproperty_yelp_categories'];

				$sanitized_data = [];

				foreach ( $raw_data as $category ) {
					$sanitized_data[] = $category;
				}

				update_post_meta( $listing->get_id(), 'clproperty_yelp_categories', $sanitized_data );
			} else {
				delete_post_meta( $listing->get_id(), 'clproperty_yelp_categories' );
			}
		}

		if ( self::is_enable_panorama_view() ) {
			$panoramaImage = $files['clproperty_panorama_img'];

			if ( ! empty( $panoramaImage ) && empty( $_POST['panorama_attachment_id'] ) ) {
				$panoramaID = $this->get_listing_floor_attachment_id( $listing->get_id(), $panoramaImage );
			} elseif ( isset( $_POST['panorama_attachment_id'] ) ) {
				$panoramaID = $_POST['panorama_attachment_id'];
			}

			if ( $panoramaID ) {
				update_post_meta( $listing->get_id(), 'clproperty_panorama_img', $panoramaID );
			}
		}
	}

	public function store_top_content_start() {
		?>
        <div class="top-content">
		<?php
	}
	public function loop_item_store_title() {
		global $store;
		?>
        <h3 class="rtcl-store-title">
            <a href="<?php $store->the_permalink(); ?>"><?php $store->the_title(); ?></a>
        </h3>
		<?php
	}
	public function store_meta() {
		global $store;
		?>
		<?php if ( $store->get_address() || $store->get_website() || $store->get_phone()  ): ?>
            <ul class="entry-meta">
				<?php if ( $store->get_address() && RDTheme::$options['show_agency_location']): ?>
                    <li><i class="icon-rt-icon-location-solid"></i><?php echo esc_html( $store->get_address() ); ?></li>
				<?php endif; ?>

				<?php if ( $stor_view_count = self::rt_get_post_view_count( $store->get_id() ) ) : ?>
					<?php if(RDTheme::$options['show_agency_views']){ ?>
						<?php if ( $stor_view_count > 999 ): ?>
							<li data-toggle="tooltip" data-placement="top" title="<?php
								/* translators: %s: view count number. */
								echo esc_attr( sprintf( __( 'Total Views: %s', 'clproperty' ), $stor_view_count ) ); ?>">
						<?php else : ?>
							<li>
						<?php endif; ?>
						<i class="fas fa-eye"></i>
						<?php
						$label = $stor_view_count < 10 ? esc_html__( 'View: ', 'clproperty' ) : esc_html__( 'Views: ', 'clproperty' );
						echo '<span class="count-label">' . esc_html( $label ) . '</span>'
							. '<span class="count-number">' . esc_html( Helper::rt_number_shorten( $stor_view_count, 1 ) ) . '</span>';
						?>
						</li>
					<?php } ?>
				<?php endif; ?>

				<?php if ( $store->is_rating_enable() && $store->get_review_counts() && RDTheme::$options['show_agency_ratings']): ?>
                    <li>
                        <div class="store-rating">
							<?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
                            <span class="reviews-rating-count">(<?php echo absint( $store->get_review_counts() ); ?>)</span>
                        </div>
                    </li>
				<?php endif; ?>
            </ul>
		<?php endif; ?>
		<?php
	}

	public function store_excerpt() {
		global $store;
		?>
		<?php if(RDTheme::$options['show_agency_excerpt']): ?>
			<div class='store-excerpt'>
				<?php $store->the_excerpt() ?>
			</div>
		<?php endif; ?>
		<?php
	}
	public function store_top_content_end() {
		?>
        </div>
		<?php
	}
	public function store_bottom_content() {
		global $store;
		?>
        <div class="bottom-content">
            <div class="store-social-info">
                <ul>
					<?php if ( $store->get_phone() && RDTheme::$options['show_agency_phone']): ?>
                        <li>
                            <a class="item-btn"  href="tel:<?php echo esc_html( $store->get_phone() ); ?>">
                                <i class="icon-rt-icon-phone-line2"></i>
								<span><?php echo esc_html($store->get_phone()); ?></span>
                            </a>
                        </li>
					<?php endif; ?>
					<?php if ( $store->get_email() && RDTheme::$options['show_agency_mail']): ?>
                        <li>
                            <a class="item-btn" href="mailto:<?php echo esc_attr( $store->get_email() ); ?>" >
                                <i class="fas fa-envelope"></i>
								<span><?php echo esc_html($store->get_email()); ?></span>
                            </a>
                        </li>
					<?php endif; ?>
					<?php if ( $store->get_website() && RDTheme::$options['show_agency_webaddress']): ?>
                        <li>
                            <a class="item-btn" href="<?php echo esc_url( $store->get_website() ); ?>">
                                <i class="fas fa-globe"></i>
                            </a>
							<span><?php echo esc_html($store->get_website()); ?></span>
                        </li>
					<?php endif; ?>
                </ul>
            </div>
			<?php if(RDTheme::$options['show_agency_social_share']):
                $t = $store->get_the_title();
				$uid = strtolower(str_replace(array(':', '\\', '/', '*', '?', '&', '.', ';', ' '), '', $t));
				?>
				<button class="store-share" data-toggle="modal" data-target="#agencySocialShare-<?php echo esc_attr($uid); ?>">
					<i class="icon-rt-icon-share-line"></i><?php esc_html_e('Share','clproperty'); ?>
				</button>
				<div class="modal fade" id="agencySocialShare-<?php echo esc_attr($uid); ?>" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php esc_html_e( 'Social Share', 'clproperty' ); ?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="share-icon">
								<?php \ClProperty_Core::social_share( Helper::post_share_on_social() ); ?>
							</div>
						</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
        </div>
		<?php
	}

	public function loop_item_listing_category() {
		global $listing;
		if ( $listing->has_category() && $listing->can_show_category() && ! Functions::is_listing() ):
			$category = $listing->get_categories();
			$category = end( $category );
			?>
            <div class="product-category">
                <a href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
            </div>
		<?php endif;
	}

	public function top_action_wrapper_start() {
		?>
        <div class="product-filter product-heading-filter">
        <div class="row">
		<?php
	}

	public function listing_archive_title() {
		?>
        <div class="col-8">
            <h2 class="heading-title"><?php echo wp_kses_post( get_the_archive_title() ); ?></h2>
        </div>
		<?php
	}

	public function top_action_wrapper_end() {
		?>
        </div>
        </div>
		<?php
	}

	public function template_include( $template ) {
		if ( Functions::is_account_page() ) {
			$new_template = Helper::get_custom_listing_template( 'listing-account', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}
		if(Functions::is_checkout_page()){
			$new_template = Helper::get_custom_listing_template( 'checkout-page', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}

		return $template;
	}

	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;

		$type = $listing->get_ad_type();

		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags',
			];
		} else {
			$result = false;
		}

		return $result;
	}

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						absint( $field->getFieldId() ),
						$id,
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
                                                <div class="price-range">
                                                    <label>%s</label>
                                                    <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                                    %s
                                                </div>
                                             </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}

	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}

			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites ) ) {
				return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_html__( "Favourites", 'clproperty' )
				       . '" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="rtcl-icon rtcl-icon-heart"></span><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::remove_from_favourite() . '</span></a>';
			} else {
				return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_html__( "Favourites", 'clproperty' )
				       . '" class="rtcl-favourites" data-id="' . $post_id . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::add_to_favourite() . '</span></a>';
			}
		} else {
			return '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="' . esc_html__( "Favourites", 'clproperty' )
			       . '" class="rtcl-require-login"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">' . \Rtcl\Helpers\Text::add_to_favourite()
			       . '</span></a>';
		}
	}

	public static function check_fb_custom_group_field_set() {
        global $listing;
        $fields_available = false;
        if ($listing) {
	        $form = $listing->getForm();
	        if ( $form ) {
		        $fields = $form->getFieldAsGroup( FBField::CUSTOM );
		        if ( count( $fields ) ) {
			        foreach ( $fields as $fieldName => $field ) {
				        $field = new FBField( $field );
				        $value = $field->getFormattedCustomFieldValue( $listing->get_id() );
				        if ( ! empty( $value ) ) {
					        return true;
				        }
			        }
		        }
	        }
        }
        return $fields_available;
	}

	/**
	 * Add mortgage calculator toggle to single listing settings.
	 *
	 * Inserts the option after 'detail_page_sidebar_position' field.
	 *
	 * @param array $options Existing settings options.
	 * @return array Modified options.
	 */
	public static function single_listing_printer_button_option( $options ) {

		$newOptions = [
			'enable_printer_button' => [
				'title'   => esc_html__( 'Enable Printer Button', 'clproperty' ),
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show printer button on listing detail page.', 'clproperty' ),
			],
		];

		// Find the position after 'enable_mortgage_calculator'.
		$keys    = array_keys( $options );
		$position = array_search( 'enable_mortgage_calculator', $keys, true );

		if ( false !== $position ) {
			$position++; // Insert after.
			$before  = array_slice( $options, 0, $position, true );
			$after   = array_slice( $options, $position, null, true );
			$options = array_merge( $before, $newOptions, $after );
		} else {
			// Fallback: append at end.
			$options = array_merge( $options, $newOptions );
		}

		return $options;
	}

	public static function single_listing_mortgage_option( $options ) {

		$newOptions = [
			'enable_mortgage_calculator' => [
				'title'   => esc_html__( 'Enable Mortgage Calculator', 'clproperty' ),
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Show mortgage calculator on listing detail page.', 'clproperty' ),
			],
		];

		// Find the position after 'detail_page_sidebar_position'.
		$keys    = array_keys( $options );
		$position = array_search( 'detail_page_sidebar_position', $keys, true );

		if ( false !== $position ) {
			$position++; // Insert after.
			$before  = array_slice( $options, 0, $position, true );
			$after   = array_slice( $options, $position, null, true );
			$options = array_merge( $before, $newOptions, $after );
		} else {
			// Fallback: append at end.
			$options = array_merge( $options, $newOptions );
		}

		return $options;
	}

}

Listing_Functions::instance();