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

use Rtcl\Controllers\Hooks\AppliedBothEndHooks;
use Rtcl\Helpers\Breadcrumb;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;

class General_Setup {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );
		add_filter( 'max_srcset_image_width', [ $this, 'disable_wp_responsive_images' ] );
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
		add_action( 'clproperty_breadcrumb', [ $this, 'breadcrumb' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_action( 'wp_head', [ $this, 'noscript_hide_preloader' ], 1 );
		add_action( 'wp_head', [ $this, 'pingback' ] );
		add_action( 'wp_body_open', [ $this, 'preloader' ] );
		add_action( 'wp_footer', [ $this, 'scroll_to_top_html' ], 1 );
		add_filter( 'get_search_form', [ $this, 'search_form' ] );
		add_filter( 'post_class', [ $this, 'hentry_config' ] );
		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
		add_filter( 'wp_list_categories', [ $this, 'add_span_cat_count' ] );
		add_filter( 'get_archives_link', [ $this, 'add_span_archive_count' ] );
		add_filter( 'widget_text', 'do_shortcode' );

		//Add user 
		add_action( 'personal_options_update', [ $this, 'rt_update_user_profile_fields' ] );
		add_action( 'edit_user_profile_update', [ $this, 'rt_update_user_profile_fields' ] );

		//Disable Gutenberg widget block
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );// Disables the block editor from managing widgets in the Gutenberg plugin.
		add_filter( 'use_widgets_block_editor', '__return_false' ); // Disables the block editor from managing widgets.

		//add discussion option true on agent plugin
		add_filter( 'rtcl_agent_register_post_type_args', [ $this, 'rtcl_agent_register_post_type_args' ] );

		//Remove admin bar
		add_action( 'after_setup_theme', [ $this, 'remove_admin_bar' ], 99 );

		//agent comments on
		add_filter('rtcl_agent_comment_status', function(){
			$text = esc_html__('open','clproperty');
			return $text;
		});

		add_action('widgets_init', [$this, 'clproperty_theme_unregister_widgets'], 11);

		// Ajax
		add_action( 'wp_ajax_load_more_agent_listing', array($this, 'clproperty_load_more_agent_listing_func' ));
    	add_action( 'wp_ajax_nopriv_load_more_agent_listing', array($this, 'clproperty_load_more_agent_listing_func' ));

		add_action( 'wp_ajax_load_more_agent_store_listing', array($this, 'clproperty_load_more_agent_store_listing_func' ));
    	add_action( 'wp_ajax_nopriv_load_more_agent_store_listing', array($this, 'clproperty_load_more_agent_store_listing_func' ));

		add_action( 'wp_ajax_rtcl_cf_by_category', [ $this, 'rtcl_cf_by_category_func' ] );
		add_action( 'wp_ajax_nopriv_rtcl_cf_by_category', [ $this, 'rtcl_cf_by_category_func' ] );
	}

	function rtcl_cf_by_category_func() {
		if ( 'all' == $_POST['cat_id']) {
			$args      = [ 'is_searchable' => true ];
			$fields_id = Functions::get_cf_ids( $args );
		} else {
			$fields_id = self::get_cf_ids( $_POST['cat_id'] );
		}

        echo '<div class="listing-custom-search-box">';
		$html = '';
		foreach ( $fields_id as $field ) {
			$html .= Listing_Functions::get_advanced_search_field_html( $field );
		}

		echo wp_kses_post( $html );
		?>
		<?php if ( isset($_POST['price_search']) && $_POST['price_search'] == 'yes' ): ?>
            <div class="search-item">
                <div class="price-range">
                    <label><?php esc_html_e( 'Price', 'clproperty' ); ?></label>
					<?php
					$data_form = '';
					$data_to   = '';
					if ( isset( $_GET['filters']['price']['min'] ) ) {
						$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
					}
					if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
						$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
					}

					$_min_price = ( isset( $min_price ) && ! empty( $min_price ) ) ? $min_price : 0;
					$_max_price = ( isset( $max_price ) && ! empty( $max_price ) ) ? $max_price : 5000;

					$_min_price = apply_filters( 'rtcl_raw_price', $_min_price );
					$_max_price = apply_filters( 'rtcl_raw_price', $_max_price );

					global $rtclmcData;
					if ( ! is_array( $rtclmcData ) && class_exists( 'RtclMc_Frontend_Price_Filters' ) ) {
						$RtclMc_Frontend_Price_Filters = \RtclMc_Frontend_Price_Filters::instance();
						$rtclmcData                    = $RtclMc_Frontend_Price_Filters->getCurrencyData();
					}

					$currency_symbol = Functions::get_currency_symbol();
					if ( isset( $rtclmcData['currency'] ) && ! empty( $rtclmcData['currency'] ) ) {
						$currency_symbol = Functions::get_currency_symbol( $rtclmcData['currency'] );
					}

					$currency_pos           = Functions::get_option_item( 'rtcl_general_currency_settings', 'currency_position', 'left' );
					$data_currency_position = sprintf( "data-prefix=%s", $currency_symbol );
					if ( in_array( $currency_pos, [ 'right', 'right_space' ] ) ) {
						$data_currency_position = sprintf( "data-postfix=%s", $currency_symbol );
					}

					?>

                    <input type="number" class="ion-rangeslider"
						<?php echo esc_attr( $data_form ); ?>
						<?php echo esc_attr( $data_to ); ?>
						<?php echo esc_attr( $data_currency_position ); ?>
                           data-min="<?php echo esc_attr( $_min_price ) ?>"
                           data-max="<?php echo esc_attr( $_max_price ) ?>"
                    />
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
		<?php endif;

        echo '</div>';
         wp_die();
	}

	public static function get_cf_ids( $category_id ) {
		$group_id = AppliedBothEndHooks::get_custom_field_group_ids( null, $category_id );
		$args     = [
			'post_type'        => rtcl()->post_type_cf,
			'post_status'      => 'publish',
			'posts_per_page'   => - 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_parent__in'  => $group_id,
			'meta_query'       => [
				[
					'key'   => '_searchable',
					'value' => 1
				],
			]
		];

		return get_posts( $args );
	}
    
	//add discussion option true on agent plugin
	function rtcl_agent_register_post_type_args( $arg ) {
		$arg['supports'][] = esc_html__('comments','clproperty') ;
		return $arg;

	}

	//Plugin Widget Unregister
	public function clproperty_theme_unregister_widgets() {
			unregister_sidebar( 'rtcl-archive-sidebar' );
			unregister_sidebar( 'rtcl-single-sidebar' );
	}

	//Remove admin bar
	 function remove_admin_bar() {
	 	$remove_admin_bar = RDTheme::$options['remove_admin_bar'];
	 	if ( $remove_admin_bar && ! current_user_can( 'administrator' ) ) {
	 		show_admin_bar( false );
	 	}
	 }

	//disable wp responsive images
	function disable_wp_responsive_images() {
		return 1;
	}

	// Update user profile info
	function rt_update_user_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( ! empty( $_POST['user_agency'] ) && intval( $_POST['user_agency'] ) >= 1900 ) {
			update_user_meta( $user_id, 'user_agency', intval( $_POST['user_agency'] ) );
		}
	}


	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function theme_setup() {
		// Theme supports
		add_theme_support( 'title-tag' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );

		// Image sizes
		$sizes = [
			'rdtheme-size1'  => [ 1200, 650, true ], // When Full width
			'rdtheme-size2'  => [ 370, 260, true ], // Listing Thumbnail Size and blog grid
			'rdtheme-size3'  => [ 570, 497, true ], // listing slider addon thumbnail
		];

		$sizes = apply_filters( 'clproperty_image_size', $sizes );

		foreach ( $sizes as $size => $value ) {
			add_image_size( $size, $value[0], $value[1], $value[2] );
		}

		// Register menus
		register_nav_menus(
			[
				'primary'   => esc_html__( 'Primary', 'clproperty' ),
			]
		);
	}

	public function register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'clproperty' ),
				'id'            => 'sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		$footer_widget_titles = [
			'1' => esc_html__( 'Footer 1', 'clproperty' ),
			'2' => esc_html__( 'Footer 2', 'clproperty' ),
			'3' => esc_html__( 'Footer 3', 'clproperty' ),
			'4' => esc_html__( 'Footer 4', 'clproperty' ),
		];

		foreach ( $footer_widget_titles as $id => $name ) {
			register_sidebar(
				[
					'name'          => $name,
					'id'            => 'footer-' . $id,
					'before_widget' => '<div id="%1$s" class="footer-box %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="footer-title">',
					'after_title'   => '</h3>',
				]
			);
		}
		register_sidebar(
			[
				'name'          => esc_html__( 'Single Property', 'clproperty' ),
				'id'            => 'single-property-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Agency/Store Sidebar', 'clproperty' ),
				'id'            => 'store-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Agent Sidebar', 'clproperty' ),
				'id'            => 'agent-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Listing Archive Sidebar', 'clproperty' ),
				'id'            => 'listing-archive-sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-heading">',
				'after_title'   => '</h3>',
			]
		);

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Archive Sidebar', 'clproperty' ),
					'id'            => 'woocommerce-archive-sidebar',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-heading">',
					'after_title'   => '</h3>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'WooCommerce Single Sidebar', 'clproperty' ),
					'id'            => 'woocommerce-single-sidebar',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-heading">',
					'after_title'   => '</h3>',
				]
			);
		}
	}

	public function body_classes( $classes ) {
		//Theme Version

		$clproperty_theme = wp_get_theme();
		$classes[] = $clproperty_theme->Name.'-version-'.$clproperty_theme->Version;
		$classes[] = 'theme-clproperty';
		$classes[] = 'header-style-' . RDTheme::$header_style;
		$classes[] = 'header-width-' . RDTheme::$header_width;
		$sticky    = RDTheme::$options['sticky_header'] ? 1 : 0;

		if ( $sticky && RDTheme::$options['single_listing_style'] !== '2' ) {
			$classes[] = 'sticky-header';
		}

		if ( RDTheme::$has_tr_header ) {
			$classes[] = 'trheader';
		} else {
			$classes[] = 'no-trheader';
		}

		if ( is_front_page() && ! is_home() ) {
			$classes[] = 'front-page';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			$classes[] = 'single-listing-style-' . Helper::listing_single_style();
		}

		if ( class_exists( 'ClProperty_Core' ) ) {
			$classes[] = 'clproperty-core-installed';
		}

		if ( ! class_exists( 'ClProperty_Core' ) ) {
			$classes[] = 'need-clproperty-core';
		}

		if ( Helper::has_full_width() ) {
			$classes[] = 'is-full-width';
		}

		// WooCommerce
		if ( isset( $_COOKIE["shopview"] ) && $_COOKIE["shopview"] == 'list' ) {
			$classes[] = 'product-list-view';
		} else {
			$classes[] = 'product-grid-view';
		}

		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}

		if ( isset( $_GET ) && ! empty( $_GET ) ) {
			$classes[] = 'clproperty-have-request';
		}

		return $classes;
	}

	public function is_blog() {
		return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && 'post' == get_post_type();
	}

	public function noscript_hide_preloader() {
		// Hide preloader if js is disabled
		echo '<noscript><style>#preloader{display:none;}</style></noscript>';
	}

	public function pingback() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	public function preloader() {
		// Preloader
		if ( RDTheme::$options['preloader'] ) {
			if ( ! empty( wp_get_attachment_image_url( RDTheme::$options['preloader_image'], 'full' ) ) ) {
				$preloader_img = wp_get_attachment_image_url( RDTheme::$options['preloader_image'], 'full' );
			} else {
				$preloader_img = Helper::get_img( 'preloader.gif' );
			}
			echo '<div id="preloader" style="background-image:url(' . esc_url( $preloader_img ) . ');"></div>';
		}
	}

	public function wp_body_open() {
		do_action( 'wp_body_open' );
	}

	public function scroll_to_top_html() {
		// Back-to-top link
		if ( RDTheme::$options['back_to_top'] ) {
			echo '<a href="#" class="scrollToTop" style=""><i class="fa fa-angle-double-up"></i></a>';
		}
	}

	public function search_form() {
		$output = '
		<form method="get" class="custom-search-form" action="' . esc_url( home_url( '/' ) ) . '">
            <div class="search-box">
                <div class="row gutters-10">
                    <div class="col-12 form-group mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="' . esc_attr__( 'What are you looking for?', 'clproperty' ) . '" value="' . get_search_query() . '" name="s" />
                            <span class="input-group-append">
                                <button class="item-btn" type="submit">
                                    <i class="icon-rt-icon-search-line"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
		</form>
		';

		return $output;
	}

	public function hentry_config( $classes ) {
		if ( is_search() || is_page() ) {
			$classes = array_diff( $classes, [ 'hentry' ] );
		}

		return $classes;
	}

	public function excerpt_more() {
		if ( is_search() ) {
			$readmore = '<a href="' . get_the_permalink() . '"> [' . esc_html__( 'read more ...', 'clproperty' ) . ']</a>';

			return $readmore;
		}

		return ' ...';
	}

	public function add_span_cat_count( $links ) {
		$links = str_replace( '</a> (', '<span>(', $links );
		$links = str_replace( ')', ')</span></a>', $links );

		return $links;
	}

	public function add_span_archive_count( $links ) {
		$links = str_replace( '</a>&nbsp;(', '<span>(', $links );
		$links = str_replace( ')', ')</span></a>', $links );

		return $links;
	}

	public function breadcrumb() {
		if ( is_404() ) {
			$clproperty_title = esc_html__( 'Error Page', 'clproperty' );
		}
		else if ( is_search() ) {
			$clproperty_title = esc_html__( 'Search Results for : ', 'clproperty' ) . get_search_query();
		} 
		else if ( is_home() ) {
			if ( get_option( 'page_for_posts' ) ) {
				$clproperty_title = get_the_title( get_option( 'page_for_posts' ) );
			}
			else {
				$clproperty_title = apply_filters( 'theme_blog_title', esc_html__( 'All Posts', 'clproperty' ) );
			}
		}
		else if ( is_archive() ) {
			$clproperty_title = get_the_archive_title();
		} 
		else if ( is_page() ) {
			$clproperty_title = get_the_title();
		} 
		else if ( is_single() ) {
			$clproperty_title ='';
		}

		$args = [
			'delimiter'   => '&nbsp;<i class="fa-solid fa-arrow-right-long"></i>&nbsp;',
			
			'wrap_before' => '<nav class="rtcl-breadcrumb">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'clproperty' ),
		];

		$breadcrumbs = new Breadcrumb();

		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], home_url() );
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		if ( ! empty( $args['breadcrumb'] ) ) {

			?>
            <section class="breadcrumbs-banner">
			
                <div class="container">
					<?php if($clproperty_title && !is_author()){ ?>
						<h2><?php echo wp_kses_post($clproperty_title); ?></h2>
					<?php } ?>
					<?php
					echo wp_kses_post( $args['wrap_before'] );
					
					foreach ( $args['breadcrumb'] as $key => $crumb ) {
						echo wp_kses_post( $args['before'] );
						if ( ! empty( $crumb[1] ) && sizeof( $args['breadcrumb'] ) !== $key + 1 ) {
							echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
						} else {
							echo '<span>' . esc_html( $crumb[0] ) . '</span>';
						}
						echo wp_kses_post( $args['after'] );
						if ( sizeof( $args['breadcrumb'] ) !== $key + 1 ) {
							echo wp_kses_post( $args['delimiter'] );
						}
					}
					echo wp_kses_post( $args['wrap_after'] );
					?>
                </div>
            </section>
			<?php
		}
	}

	/* - Ajax LoadMore Agent Listing Function
	--------------------------------------------------------*/

	public function clproperty_load_more_agent_listing_func() {
		global $wp;
		$agent_store_per_page	=RDTheme::$options['listing_agent_listing_per'] ? RDTheme::$options['listing_agent_listing_per']:10;
		$page 					= (isset($_REQUEST['pageNumber'])) ? $_REQUEST['pageNumber'] : 0;
		$user_id				=esc_attr( $_REQUEST['authorId'] );
		$args = array(
			'post_type'      => rtcl()->post_type,
			'posts_per_page' => $agent_store_per_page,
			'paged'          => $page,
			'author'         =>$user_id,
		);
		$agent_ads_query = new \WP_Query( apply_filters( 'rtcl_agent_listing_args', $args ) );
	
		if($agent_ads_query->have_posts()) : while($agent_ads_query->have_posts()) : $agent_ads_query->the_post();
			$listing = rtcl()->factory->get_listing( get_the_ID() );
			$listing_type       = Listing_Functions::get_listing_type( $listing );
		?>
	
		<div class="item-block item-block--list-layout2">
			<div class="item-block__figure">
				<?php echo wp_kses_post($listing->get_the_thumbnail( 'rtcl-thumbnail' )); ?>
				<div class="item-block__tags">
			
				<a class="item-block__tag" href="#"><?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] )  ?></a>
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
				
			</div>
			<div class="item-block__content">
				<h3 class="item-block__heading"><?php $listing->the_title(); ?></h3>
				<div class="item-block__location">
				<i class="icon-rt-icon-location-solid"></i>
				<span class="item-block__location__name"><?php  $listing->the_locations(  ); ?></span>
				</div>
				
				<p class="item-block__text"> 
					<?php 
							$length=RDTheme::$options['listing_arexcerpt_limit'];
							$excerpt=Helper::clproperty_excerpt($length);
							echo wp_kses_post( $excerpt );
					?>
				</p>
				
				<div class="item-block__price">
				<?php echo wp_kses_post( $listing->get_price_html() ); ?>
				</div>
				<div class="item-block__features">
				<?php 
					Helper::clproperty_listing_listable_fields($listing);
				?>
				</div>
			</div>
		</div>
		<?php endwhile;
		endif;
		  wp_reset_query();
		  die();
	}

	// End of ajax callback function

	/* - Ajax LoadMore Agent Store Listing Function
	--------------------------------------------------------*/
	public function clproperty_load_more_agent_store_listing_func() {
		global $wp;
		
		$page 					= (isset($_REQUEST['pageNumber'])) ? $_REQUEST['pageNumber'] : 0;
		$user_id				=esc_attr( $_REQUEST['authorId'] );
		$agent_store_per_page=RDTheme::$options['listing_agent_listing_per'] ? RDTheme::$options['listing_agent_listing_per']:10;
		$args2 = array(
			'post_type'      => rtcl()->post_type,
			'posts_per_page' =>$agent_store_per_page,
			'paged'          	=> $page,
			'meta_query'    =>[
				[
					'key'   => '_rtcl_manager_id',
					'value' => $user_id
				]
			]
		);
		$agent_ads_query2 = new \WP_Query( apply_filters( 'rtcl_agent_listing_args', $args2 ) );
	
		if($agent_ads_query2->have_posts()) : while($agent_ads_query2->have_posts()) : $agent_ads_query2->the_post();
			$listing = rtcl()->factory->get_listing( get_the_ID() );
			$listing_type       = Listing_Functions::get_listing_type( $listing );
		?>
	
		<div class="item-block item-block--list-layout2">
			<div class="item-block__figure">
				<?php echo wp_kses_post($listing->get_the_thumbnail( 'rtcl-thumbnail' )); ?>
				<div class="item-block__tags">
			
				<a class="item-block__tag" href="#"><?php echo esc_html( apply_filters( 'rtcl_type_prefix', __( 'For', 'clproperty' ) ) ) . ' ' . esc_html( $listing_type['label'] )  ?></a>
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
				
			</div>
			<div class="item-block__content">
				<h3 class="item-block__heading"><?php $listing->the_title(); ?></h3>
				<div class="item-block__location">
				<i class="icon-rt-icon-location-solid"></i>
				<span class="item-block__location__name"><?php  $listing->the_locations(  ); ?></span>
				</div>
				<p class="item-block__text"> 
					<?php 
							$length=RDTheme::$options['listing_arexcerpt_limit'];
							$excerpt=Helper::clproperty_excerpt($length);
							echo wp_kses_post( $excerpt );
					?>
				</p>
				<div class="item-block__price">
				<?php echo wp_kses_post( $listing->get_price_html() ); ?>
				</div>
				<div class="item-block__features">
				<?php 
					Helper::clproperty_listing_listable_fields($listing);
				?>
				</div>
			</div>
		</div>
		<?php endwhile;
		endif;
		  wp_reset_query();
		  die();
	}
	// End of ajax callback function

}

General_Setup::instance();