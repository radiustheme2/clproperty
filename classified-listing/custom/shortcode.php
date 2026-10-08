<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;

/**
 * ThemeJetpack Class
 */
class Shortcode {

	protected static $instance = null;

	public static $shortcode_list = [
		'header'    => [
			'tag'      => 'clproperty_listing_header',
			'callback' => 'render_listing_header',
		],
		'gallery_image'    => [
			'tag'      => 'clproperty_listing_gallery_image',
			'hint'     => 'clproperty_listing_gallery_image show_video="no"',
			'callback' => 'clproperty_listing_gallery_image',
		],
        'gallery_slider'    => [
			'tag'      => 'clproperty_listing_gallery_slider',
			'hint'     => 'clproperty_listing_gallery_slider show_video="no"',
			'callback' => 'clproperty_listing_gallery_slider',
		],
		'sidebar'    => [
			'tag'      => 'clproperty_listing_sidebar',
			'callback' => 'render_listing_sidebar',
		],
	];

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter(
			'rtcl/fb/single_layout/fields',
			function ( $fields ) {
				$shortcode_hints = '';
				foreach ( self::$shortcode_list as $label => $shortcode ) {
					$display = isset( $shortcode['hint'] ) ? $shortcode['hint'] : $shortcode['tag'];
				    $shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$display}]";
				}

				$fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

				return $fields;
			}
		);

		foreach ( self::$shortcode_list as $shortcode ) {
			add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * @return false|string
	 */
	public function render_listing_header() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <!--Listing Heading-->
            <div class="listing-single-layout-1">
			    <?php Helper::get_custom_listing_template( 'listing-heading' ); ?>
            </div>
            <!--End Listing Heading-->

			<?php
			return ob_get_clean();
		}

		return '';
	}
    
	/**
	 * @return false|string
	 */
	public function clproperty_listing_gallery_image( $atts ) {
		$atts = shortcode_atts( [ 'show_video' => 'yes' ], $atts, 'clproperty_listing_gallery_image' );

		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			Helper::get_custom_listing_template( 'gallery-images', true, [ 'show_video' => $atts['show_video'] ] );

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @param $atts
	 *
	 * @return false|string
	 */
    public function clproperty_listing_gallery_slider( $atts ) {
		$atts = shortcode_atts( [ 'show_video' => 'yes' ], $atts, 'clproperty_listing_gallery_slider' );

		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			Helper::get_custom_listing_template( 'gallery-slider', true, [ 'show_video' => $atts['show_video'] ] );

			return ob_get_clean();
		}

		return '';
	}

    /**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_sidebar() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {

			$enable_mortgage_calculator = Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_mortgage_calculator', false, 'checkbox' );

			ob_start();
			?>

            <div class="listing-sidebar">
				<?php
				if ( $listing->can_show_user() ) {
					if ( RDTheme::$options['show_user_info_on_details'] === 'show_store_info' ) {
						$listing->the_user_info();
					} else {
						Helper::get_custom_listing_template( 'listing-content-info' );
					}
				}
				if ( $enable_mortgage_calculator ) {
					clproperty_render_mortgage_calculator();
				}
				?>
                <!-- Social Profile  -->
				<?php do_action( 'rtcl_single_listing_social_profiles'); ?>
                <!-- Business Hours  -->
				<?php do_action( 'rtcl_single_listing_business_hours' ); ?>

				<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>

				<?php if ( is_active_sidebar( 'single-property-sidebar' ) ): ?>
                    <aside class="sidebar-widget">
						<?php dynamic_sidebar( 'single-property-sidebar' ); ?>
                    </aside>
				<?php endif; ?>
            </div>

            <?php
			return ob_get_clean();
		}

		return '';
	}

}

Shortcode::instance();
