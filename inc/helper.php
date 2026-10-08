<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use Rtrs\Modules\Review\Helpers\ReviewFns;

class Helper {

	public static function has_sidebar() {
		return ( self::has_full_width() ) ? false : true;
	}
	
	public static function has_full_width() {
		$theme_option_full_width = ( RDTheme::$layout == 'full-width' ) ? true : false;
		$not_active_sidebar      = ! is_active_sidebar( 'sidebar' );
		$bool                    = $theme_option_full_width || $not_active_sidebar;

		return $bool;
	}

	public static function listing_single_style() {
		$opt_layout  = ! empty( RDTheme::$options['single_listing_style'] ) ? RDTheme::$options['single_listing_style'] : '1';
		$meta_layout = get_post_meta( get_the_id(), 'listing_layout', true );
		if ( ! $meta_layout || 'default' == $meta_layout ) {
			return $opt_layout;
		} else {
			return $meta_layout;
		}
	}

	public static function the_layout_class() {
	
		$fullwidth_col = ( RDTheme::$options['blog_style'] == 'style1' && is_home() ) ? 'col-sm-10 offset-sm-1 col-12' : 'col-sm-12 col-12';

		$layout_class = self::has_sidebar() ? 'col-lg-9 col-sm-12 col-12' : $fullwidth_col;
		if ( RDTheme::$layout == 'left-sidebar' ) {
			$layout_class .= ' order-lg-2';
		}
		echo esc_attr( apply_filters( 'clproperty_layout_class', $layout_class ) );
	}

	public static function the_sidebar_class() {
		$sidebar_class = self::has_sidebar() ? 'col-lg-3 col-sm-12 sidebar-break-lg' : 'col-sm-12 col-12';
		echo esc_attr( apply_filters( 'clproperty_sidebar_class', $sidebar_class ) );
	}

	public static function comments_callback( $comment, $args, $depth ) {
		$args2 = get_defined_vars();
		Helper::get_template_part( 'template-parts/comments-callback', $args2 );
	}

	public static function requires( $filename, $dir = false ) {
		if ( $dir ) {
			$child_file = get_stylesheet_directory() . '/' . $dir . '/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			} else {
				$file = get_template_directory() . '/' . $dir . '/' . $filename;
			}
		} else {
			$child_file = get_stylesheet_directory() . '/inc/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			} else {
				$file = Constants::$theme_inc_dir . $filename;
			}
		}
		if ( file_exists( $file ) ) {
			require_once $file;
		} else {
			return false;
		}
	}

	public static function get_file( $path ) {
		$file = get_stylesheet_directory_uri() . $path;
		if ( ! file_exists( $file ) ) {
			$file = get_template_directory_uri() . $path;
		}

		return $file;
	}

	public static function get_img( $filename ) {
		$path = '/assets/img/' . $filename;

		return self::get_file( $path );
	}

	public static function get_css( $filename ) {
		$path = '/assets/css/' . $filename . '.css';

		return self::get_file( $path );
	}
	public static function custom_font_css($filename){
		$path = '/assets/custom-icons/css/' . $filename . '.css';

		return self::get_file( $path );
	}
	public static function get_maybe_rtl_css( $filename ) {
		if ( is_rtl() ) {
			$path = '/assets/css-rtl/' . $filename . '.css';

			return self::get_file( $path );
		} else {
			return self::get_css( $filename );
		}
	}

	public static function get_rtl_css( $filename ) {
		$path = '/assets/css-rtl/' . $filename . '.css';

		return self::get_file( $path );
	}

	public static function get_js( $filename ) {
		$path = '/assets/js/' . $filename . '.js';

		return self::get_file( $path );
	}

	public static function get_template_part( $template, $args = [] ) {
		extract( $args );
		$template = '/' . $template . '.php';
		if ( file_exists( get_stylesheet_directory() . $template ) ) {
			$file = get_stylesheet_directory() . $template;
		} else {
			$file = get_template_directory() . $template;
		}
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			return false;
		}
	}

	/**
	 * Get all sidebar list
	 *
	 * @return array
	 */
	public static function custom_sidebar_fields(): array {
		$base                                      = 'clproperty';
		$sidebar_fields                            = [];
		$sidebar_fields['sidebar']                 = esc_html__( 'Sidebar', 'clproperty' );
		$sidebar_fields['listing-archive-sidebar'] = esc_html__( 'Listing Archive Sidebar', 'clproperty' );
		$sidebar_fields['store-sidebar']           = esc_html__( 'Agency/Store Sidebar', 'clproperty' );
		$sidebar_fields['agent-sidebar']           = esc_html__( 'Agent Sidebar', 'clproperty' );
		if ( class_exists( 'WooCommerce' ) ) {
			$sidebar_fields['woocommerce-archive-sidebar'] = esc_html__( 'WooCommerce Archive Sidebar', 'clproperty' );
			$sidebar_fields['woocommerce-single-sidebar']  = esc_html__( 'WooCommerce Single Sidebar', 'clproperty' );
		}
		$sidebars = get_option( "{$base}_custom_sidebars", [] );
		
		if ( $sidebars ) {
			foreach ( $sidebars as $sidebar ) {
				$sidebar_fields[ $sidebar['id'] ] = $sidebar['name'];
			}
		}
 
		return $sidebar_fields;
	}

	/**
	 * Get site header list
	 *
	 * @param  string  $return_type
	 *
	 * @return array
	 */
	public static function get_clproperty_header_list( $return_type = '' ): array {
		if ( 'header' === $return_type ) {
			return [
				'1' => [
					'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/header-1.png',
					'name'  => __( 'Style 1', 'clproperty' ),
				],
			];
		} else {
			return [
				'default' => esc_html__( 'Default', 'clproperty' ),
				'1'       => esc_html__( 'Layout 1', 'clproperty' ),
			];
		}
	}

	public static function get_custom_listing_template( $template, $echo = true, $args = [] ) {
		$template = 'classified-listing/custom/' . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';
			return $template;
		}
	}

	public static function get_custom_store_template( $template, $echo = true, $args = [] ) {
		$template = 'classified-listing/store/custom/' . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	public static function is_chat_enabled() {
		if ( RDTheme::$options['header_chat_icon'] && class_exists( 'Rtcl' ) ) {
			if ( Fns::is_enable_chat() ) {
				return true;
			}
		}

		return false;
	}

	public static function get_primary_color() {
		return apply_filters( 'rdtheme_primary_color', RDTheme::$options['primary_color'] );
	}

	public static function get_secondary_color() {
		return apply_filters( 'rdtheme_secondary_color', RDTheme::$options['secondary_color'] );
	}

	public static function get_body_color() {
		return apply_filters( 'rdtheme_body_color', RDTheme::$options['body_color'] );
	}

	public static function wp_set_temp_query( $query ) {
		global $wp_query;
		$temp     = $wp_query;
		$wp_query = $query;

		return $temp;
	}

	public static function wp_reset_temp_query( $temp ) {
		global $wp_query;
		$wp_query = $temp;
		wp_reset_postdata();
	}
	public static function clproperty_excerpt( $limit ) {
	    $excerpt = explode(' ', get_the_excerpt(), $limit);
	    if (count($excerpt)>=$limit) {
	        array_pop($excerpt);
	        $excerpt = implode(" ",$excerpt).'';
	    } else {
	        $excerpt = implode(" ",$excerpt);
	    }
	    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	    return $excerpt;
	}
	public static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = "$r, $g, $b";

		return $rgb;
	}

	public static function socials() {
		$rdtheme_socials = [
			'facebook'  => [
				'icon' => 'fa-brands fa-facebook-f',
				'url'  => RDTheme::$options['facebook'],
			],
			'twitter'   => [
				'icon' => 'fa-brands fa-x-twitter',
				'url'  => RDTheme::$options['twitter'],
			],
			'linkedin'  => [
				'icon' => 'fa-brands fa-linkedin-in',
				'url'  => RDTheme::$options['linkedin'],
			],
			'youtube'   => [
				'icon' => 'fa-brands fa-youtube',
				'url'  => RDTheme::$options['youtube'],
			],
			'pinterest' => [
				'icon' => 'fab fa-pinterest',
				'url'  => RDTheme::$options['pinterest'],
			],
			'instagram' => [
				'icon' => 'fab fa-instagram',
				'url'  => RDTheme::$options['instagram'],
			],
			'skype'     => [
				'icon' => 'fab fa-skype',
				'url'  => RDTheme::$options['skype'],
			],
		];

		return array_filter( $rdtheme_socials, [ __CLASS__, 'filter_social' ] );
	}

	public static function post_share_on_social() {
		$sharer = [];
		if ( RDTheme::$options['social_facebook'] ) {
			$sharer[] = 'facebook';
		}
		if ( RDTheme::$options['social_twitter'] ) {
			$sharer[] = 'twitter';
		}
		if ( RDTheme::$options['social_linkedin'] ) {
			$sharer[] = 'linkedin';
		}
		if ( RDTheme::$options['social_pinterest'] ) {
			$sharer[] = 'pinterest';
		}
		if ( RDTheme::$options['social_tumblr'] ) {
			$sharer[] = 'tumblr';
		}
		if ( RDTheme::$options['social_reddit'] ) {
			$sharer[] = 'reddit';
		}
		if ( RDTheme::$options['social_vk'] ) {
			$sharer[] = 'vk';
		}
		return $sharer;
	}

	public static function filter_social( $args ) {
		return ( $args['url'] != '' );
	}

	// Get user social info

	public static function get_user_social_info( $social_links ) {
		if ( count( $social_links ) < 1 && ! is_array( $social_links ) ) {
			return;
		}
		ob_start();
		?>
        <ul class="agent-social">
            <li class="social-item">
                <a href="#" class="social-hover-icon social-link">
                    <i class="fas fa-share-alt"></i>
                </a>
                <ul class="team-social-dropdown">
					<?php foreach ( $social_links as $icon ) : ?>

                        <li class="social-item">
                            <a
                                    href="<?php echo esc_html( $icon['social_link'] ) ?>"
                                    class="social-link" target="_blank"
                                    title="<?php echo esc_html( $icon['social_title'] ) ?>">
								<?php \Elementor\Icons_Manager::render_icon( $icon['social_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </a>
                        </li>

					<?php endforeach; ?>
                </ul>
            </li>
        </ul>
		<?php
		echo wp_kses_post( ob_get_clean() );
	}

	// Time Elapsed
	public static function time_elapsed_string() {
		$ptime = get_the_time( 'U' );
		$etime = time() - $ptime;

		if ( $etime < 1 ) {
			return '0 seconds';
		}

		$a        = [
			365 * 24 * 60 * 60 => 'year',
			30 * 24 * 60 * 60  => 'month',
			24 * 60 * 60       => 'day',
			60 * 60            => 'hour',
			60                 => 'minute',
			1                  => 'second',
		];
		$a_plural = [
			'year'   => 'years',
			'month'  => 'months',
			'day'    => 'days',
			'hour'   => 'hours',
			'minute' => 'minutes',
			'second' => 'seconds',
		];

		foreach ( $a as $secs => $str ) {
			$d = $etime / $secs;
			if ( $d >= 1 ) {
				$r = round( $d );

				return $r . ' ' . ( $r > 1 ? $a_plural[ $str ] : $str ) . ' ago';
			}
		}
	}

	//Post reading time calculate
	public static function reading_time_count( $content = '', $is_zero = false ) {
		global $post;
		$post_content = $content ? $content : $post->post_content; // wordpress users only
		$word         = str_word_count( wp_strip_all_tags( strip_shortcodes( $post_content ) ) );
		$m            = floor( $word / 200 );
		$s            = floor( $word % 200 / ( 200 / 60 ) );
		if ( $is_zero && $m < 10 ) {
			$m = '0' . $m;
		}
		if ( $is_zero && $s < 10 ) {
			$s = '0' . $s;
		}
		if ( $m < 1 ) {
			return $s . ' second' . ( $s == 1 ? '' : 's' );
		}

		return $m . ' min' . ( $m == 1 ? '' : 's' );
	}

	// Modify Color
	public static function rt_modify_color( $hex, $steps ) {
		$steps = max( - 255, min( 255, $steps ) );
		// Format the hex color string
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}
		// Get decimal values
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		// Adjust number of steps and keep it inside 0 to 255
		$r     = max( 0, min( 255, $r + $steps ) );
		$g     = max( 0, min( 255, $g + $steps ) );
		$b     = max( 0, min( 255, $b + $steps ) );
		$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}

	// Number Shorten
	public static function rt_number_shorten( $number, $precision = 3, $divisors = null ) {
		if ( $number < 1000 ) {
			return $number;
		}
		// Setup default $divisors if not provided
		if ( ! isset( $divisors ) ) {
			$divisors = [
				pow( 1000, 0 ) => '', // 1000^0 == 1
				pow( 1000, 1 ) => esc_html__( 'K', 'clproperty' ), // Thousand
				pow( 1000, 2 ) => esc_html__( 'M', 'clproperty' ), // Million
				pow( 1000, 3 ) => esc_html__( 'B', 'clproperty' ), // Billion
				pow( 1000, 4 ) => esc_html__( 'T', 'clproperty' ), // Trillion
				pow( 1000, 5 ) => esc_html__( 'Qa', 'clproperty' ), // Quadrillion
				pow( 1000, 6 ) => esc_html__( 'Qi', 'clproperty' ) // Quintillion
			];
		}

		// Loop through each $divisor and find the
		// lowest amount that matches
		foreach ( $divisors as $divisor => $shorthand ) {
			if ( abs( $number ) < ( $divisor * 1000 ) ) {
				// We found a match!
				break;
			}
		}

		// We found our match, or there were no matches.
		// Either way, use the last defined value for $divisor.
		return number_format( $number / $divisor, $precision ) . $shorthand;
	}

	//Custom pagination for page template
	static function clproperty_list_posts_pagination( $query = '' ) {
		if ( ! $query ) {
			global $query;
		}
		if ( $query->max_num_pages > 1 ) :
			$big   = 999999999; // need an unlikely integer
			$items = paginate_links( [
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'prev_next' => true,
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $query->max_num_pages,
				'type'      => 'array',
				'prev_text' => '<i class="fas fa-angle-double-left"></i>',
				'next_text' => '<i class="fas fa-angle-double-right"></i>',
			] );

			$pagination = '<div class="pagination-number"><ul class="pagination clearfix"><li>';
			$pagination .= join( "</li><li>", (array) $items );
			$pagination .= "</li></ul></div>";

			return $pagination;
		endif;

		return;
	}

	/**
	 * Get Listing author image
	 *
	 * @param       $listing
	 * @param  int  $size
	 */
	static public function get_listing_author_iamge( $listing, $size = 40, $default = 'Author', $args = [] ) {
		$manager_id = get_post_meta( $listing->get_id(), '_rtcl_manager_id', true );
		$owner_id   = $manager_id ? $manager_id : $listing->get_owner_id();
		$pp_id      = absint( get_user_meta( $owner_id, '_rtcl_pp_id', true ) );
		echo wp_kses_post( $pp_id ? wp_get_attachment_image( $pp_id, [ $size, $size ] ) : get_avatar( $listing->get_author_id(), $size ) );
	}

	/**
     * Get Cllisting thumb carousel markup
	 * @param          $listing_id
	 * @param  string  $size
	 */

	public static function clproperty_thumb_carousel( $listing_id, $size = 'rtcl-thumbnail') { ?>
	    <?php 
		
			$clproperty_slider_autoplay=RDTheme::$options['listing_slider_autoplay'];
			$clproperty_slider_delay=RDTheme::$options['listing_slider_autoplay_delay'];
			$clproperty_slider_autoplay=$clproperty_slider_autoplay=='yes' ? "true":"false";
			
		?>
        <div class="listing-archive-carousel" data-autoplay= "<?php echo esc_attr( $clproperty_slider_autoplay );?>" data-delay="<?php echo esc_attr( $clproperty_slider_delay );?>">
            <div class="swiper-wrapper">
				<?php $images = Functions::get_listing_images( $listing_id ); ?>
				<?php foreach ( $images as $index => $image ): ?>
					<?php $thumb_img = wp_get_attachment_image( $image->ID, $size );
					?>
                    <div class="swiper-slide">
						<?php echo wp_kses_post($thumb_img); ?>
                    </div>
				<?php endforeach; ?>
            </div>
			
            <div class="listing-archive-pagination"></div>
			
        </div>
		<?php
	}

	public static function clproperty_el_thumb_carousel( $listing_id, $size = 'rtcl-thumbnail',$pagination=false,$navigation=false) { ?>
        <div class="listing-el-carousel">
            <div class="swiper-wrapper">
				<?php $images = Functions::get_listing_images( $listing_id ); ?>
				<?php foreach ( $images as $index => $image ): ?>
					<?php $thumb_img = wp_get_attachment_image( $image->ID, $size );
					?>
                    <div class="swiper-slide">
						<?php echo wp_kses_post($thumb_img); ?>
                    </div>
				<?php endforeach; ?>
            </div>
			<?php if($navigation==true) {?>
            <div class="listing-el-navigation">
				<div class="thumb-swiper-button-prev listing-navigation">
					<svg width="10" height="20" viewBox="0 0 10 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8.75005 17.25C8.55823 17.25 8.36623 17.1767 8.2198 17.0302L0.719797 9.53024C0.426734 9.23718 0.426734 8.76261 0.719797 8.46974L8.2198 0.969736C8.51286 0.676673 8.98742 0.676673 9.2803 0.969736C9.57317 1.2628 9.57336 1.73736 9.2803 2.03024L2.31055 8.99999L9.2803 15.9697C9.57336 16.2628 9.57336 16.7374 9.2803 17.0302C9.13386 17.1767 8.94186 17.25 8.75005 17.25Z" fill="currentColor"></path>
                    </svg>
                </div>
                <div class="thumb-swiper-button-next listing-navigation">
					<svg width="10" height="20" viewBox="0 0 10 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1.24995 17.25C1.44177 17.25 1.63377 17.1767 1.7802 17.0302L9.2802 9.53024C9.57327 9.23718 9.57327 8.76261 9.2802 8.46974L1.7802 0.969736C1.48714 0.676673 1.01258 0.676673 0.719703 0.969736C0.426828 1.2628 0.426641 1.73736 0.719703 2.03024L7.68945 8.99999L0.719703 15.9697C0.426641 16.2628 0.426641 16.7374 0.719703 17.0302C0.86614 17.1767 1.05814 17.25 1.24995 17.25Z" fill="currentColor"></path>
                    </svg>
                </div>

			</div>
			<?php } ?>
        </div>
		<?php
	}

	//get total ratings by ReviewFns Plugin

	public static function get_average_ratings_by_review_schema(){
		return ReviewFns::getAvgRatings( get_the_ID() );
	}

	//get total review star  by ReviewFns Plugin
	public static function get_total_review_star(){
		$avg_rating=self::get_average_ratings_by_review_schema();
		return ReviewFns::review_stars( $avg_rating );
	}

	//get total ratings by ReviewFns Plugin

	public static function get_total_ratings_by_review_schema(){
		return ReviewFns::getTotalRatings(get_the_ID());
	}

	public static function get_feature_listing_text($listing){
		if(!$listing->is_featured()){
			return;
		}
		$label = Functions::get_option_item( 'rtcl_general_listing_label_settings', 'listing_featured_label' );
		$label = $label ?: esc_html__( "Featured", "clproperty" );
		echo '<span class="badge rtcl-badge-featured">' . esc_html( $label ) . '</span>';
	}

	//listing listable fields

	public static function clproperty_listing_listable_fields($listing){
		global $listing;

		$category_id = Functions::get_term_child_id_for_a_post($listing->get_categories());
								// Get custom fields
		$custom_field_ids = Functions::get_custom_field_ids($category_id);
	
		$fields = array();
		if (!empty($custom_field_ids)) {
			$args = array(
				'post_type'        => rtcl()->post_type_cf,
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
				'post__in'         => $custom_field_ids,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'suppress_filters' => false,
				'meta_query'       => array(
					array(
						'key'     => '_listable',
						'compare' => '=',
						'value'   => 1,
					)
				)
			);
			$args = apply_filters( 'rtcl_loop_item_listable_fields', $args, $listing );
			$fields = get_posts($args);
		}
		Functions::get_template("listing/listable-fields", array(
			'fields'     => $fields,
			'listing_id' => $listing->get_id()
		), '', rtclPro()->get_plugin_template_path());
	}

	public static function clproperty_dropdown_categories( $args = '' ) {
		$defaults = [
			'show_option_all'   => '',
			'show_option_none'  => '',
			'orderby'           => 'id',
			'order'             => 'ASC',
			'show_count'        => 0,
			'hide_empty'        => 1,
			'child_of'          => 0,
			'exclude'           => '',
			'echo'              => 1,
			'selected'          => 0,
			'hierarchical'      => 0,
			'name'              => 'cat',
			'id'                => '',
			'class'             => 'postform',
			'depth'             => 0,
			'tab_index'         => 0,
			'taxonomy'          => 'category',
			'hide_if_empty'     => false,
			'option_none_value' => - 1,
			'value_field'       => 'term_id',
			'required'          => false,
			'aria_describedby'  => '',
		];

		$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;

		// Back compat.
		if ( isset( $args['type'] ) && 'link' === $args['type'] ) {
			_deprecated_argument(
				__FUNCTION__,
				'3.0.0',
				wp_kses_post( sprintf(
				/* translators: 1: "type => link", 2: "taxonomy => link_category" */
					__( '%1$s is deprecated. Use %2$s instead.', 'clproperty' ),
					'<code>type => link</code>',
					'<code>taxonomy => link_category</code>'
				) )
			);
			$args['taxonomy'] = 'link_category';
		}

		// Parse incoming $args into an array and merge it with $defaults.
		$parsed_args = wp_parse_args( $args, $defaults );

		$option_none_value = $parsed_args['option_none_value'];

		if ( ! isset( $parsed_args['pad_counts'] ) && $parsed_args['show_count'] && $parsed_args['hierarchical'] ) {
			$parsed_args['pad_counts'] = true;
		}

		$tab_index = $parsed_args['tab_index'];

		$tab_index_attribute = '';
		if ( (int) $tab_index > 0 ) {
			$tab_index_attribute = " tabindex=\"$tab_index\"";
		}

		// Avoid clashes with the 'name' param of get_terms().
		$get_terms_args = $parsed_args;
		unset( $get_terms_args['name'] );
		$categories = get_terms( $get_terms_args );


		$name     = esc_attr( $parsed_args['name'] );
		$class    = esc_attr( $parsed_args['class'] );
		$id       = $parsed_args['id'] ? esc_attr( $parsed_args['id'] ) : $name;
		$required = $parsed_args['required'] ? 'required' : '';

		$aria_describedby_attribute = $parsed_args['aria_describedby'] ? ' aria-describedby="' . esc_attr( $parsed_args['aria_describedby'] ) . '"' : '';

		if ( ! $parsed_args['hide_if_empty'] || ! empty( $categories ) ) {
			$output = "<select $required name='$name' id='$id' class='$class'$tab_index_attribute$aria_describedby_attribute>\n";
		} else {
			$output = '';
		}
		if ( empty( $categories ) && ! $parsed_args['hide_if_empty'] && ! empty( $parsed_args['show_option_none'] ) ) {

			/**
			 * Filters a taxonomy drop-down display element.
			 *
			 * A variety of taxonomy drop-down display elements can be modified
			 * just prior to display via this filter. Filterable arguments include
			 * 'show_option_none', 'show_option_all', and various forms of the
			 * term name.
			 *
			 * @param string $element Category name.
			 * @param WP_Term|null $category The category object, or null if there's no corresponding category.
			 *
			 * @since 1.2.0
			 *
			 * @see wp_dropdown_categories()
			 *
			 */
			$show_option_none = apply_filters( 'list_cats', $parsed_args['show_option_none'], null );
			$output           .= "\t<option value='" . esc_attr( $option_none_value ) . "' selected='selected'>$show_option_none</option>\n";
		}

		if ( ! empty( $categories ) ) {

			if ( $parsed_args['show_option_all'] ) {

				/** This filter is documented in wp-includes/category-template.php */
				$show_option_all = apply_filters( 'list_cats', $parsed_args['show_option_all'], null );
				$selected        = ( '0' === (string) $parsed_args['selected'] ) ? " selected='selected'" : '';
				$output          .= "\t<option data-term-id='all' value='0'$selected>$show_option_all</option>\n";
			}

			if ( $parsed_args['show_option_none'] ) {

				/** This filter is documented in wp-includes/category-template.php */
				$show_option_none = apply_filters( 'list_cats', $parsed_args['show_option_none'], null );
				$selected         = selected( $option_none_value, $parsed_args['selected'], false );
				$output           .= "\t<option value='" . esc_attr( $option_none_value ) . "'$selected>$show_option_none</option>\n";
			}

			if ( $parsed_args['hierarchical'] ) {
				$depth = $parsed_args['depth'];  // Walk the full depth.
			} else {
				$depth = - 1; // Flat.
			}

			$output .= self::walk_category_dropdown_tree( $categories, $depth, $parsed_args );
		}

		if ( ! $parsed_args['hide_if_empty'] || ! empty( $categories ) ) {
			$output .= "</select>\n";
		}


		/**
		 * Filters the taxonomy drop-down output.
		 *
		 * @param string $output HTML output.
		 * @param array $parsed_args Arguments used to build the drop-down.
		 *
		 * @since 2.1.0
		 *
		 */
		$output = apply_filters( 'wp_dropdown_cats', $output, $parsed_args );

		if ( $parsed_args['echo'] ) {
			echo wp_kses_post( $output );
		}

		return $output;
	}

	public static function walk_category_dropdown_tree( ...$args ) {
		// The user's options are the third parameter.
		if ( empty( $args[2]['walker'] ) || ! ( $args[2]['walker'] instanceof \Walker ) ) {
			$walker = new ClProperty_Walker_Category_Dropdown;
		} else {
			$walker = $args[2]['walker'];
		}

		return $walker->walk( ...$args );
	}

}