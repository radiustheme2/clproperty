<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Customizer {

	// Get our default values
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
			//self::populated_default_data();
		}

		return self::$instance;
	}

	public function populated_default_data() {
		$this->defaults = rttheme_generate_defaults();
	}

	/**
	 * Customizer Panels
	 */
	public function add_customizer_panels( $wp_customize ) {
		// Layout Panel
		$wp_customize->add_panel( 'rttheme_layouts_defaults',
			[
				'title'       => esc_html__( 'Layout Settings', 'clproperty' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'clproperty' ),
				'priority'    => 17,
			]
		);
		// Color Panel
		$wp_customize->add_panel( 'rttheme_color_panel',
			[
				'title'       => esc_html__( 'Color', 'clproperty' ),
				'description' => esc_html__( 'Change site color', 'clproperty' ),
				'priority'    => 15,
			]
		);
		// Add Listing Settings Section
		$wp_customize->add_panel( 'listings_panel',
			[
				'title'    => esc_html__( 'Listing Settings', 'clproperty' ),
				'priority' => 15,
			]
		);
	}

	/**
	 * Customizer sections
	 */
	public function add_customizer_sections( $wp_customize ) {
		// Rename the default Colors section
		$wp_customize->get_section( 'colors' )->title = 'Background';
		// Move the default Colors section to our new Colors Panel
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';
		// Change the Priority of the default Colors section so it's at the top of our Panel
		$wp_customize->get_section( 'colors' )->priority = 10;
		// Add General Section
		$wp_customize->add_section( 'general_section',
			[
				'title'    => esc_html__( 'General', 'clproperty' ),
				'priority' => 10,
			]
		);
		// Add Header Main Section
		$wp_customize->add_section( 'header_main_section',
			[
				'title'    => esc_html__( 'Header', 'clproperty' ),
				'priority' => 11,
			]
		);
		// Add Header Main Section
		$wp_customize->add_section( 'breadcrumb_section',
			[
				'title'    => esc_html__( 'Breadcrumb', 'clproperty' ),
				'priority' => 13,
			]
		);
		// Add Footer Section
		$wp_customize->add_section( 'footer_section',
			[
				'title'    => esc_html__( 'Footer', 'clproperty' ),
				'priority' => 12,
			]
		);
		// Add Color Section
		$wp_customize->add_section( 'site_color_section',
			[
				'title'    => esc_html__( 'Site Color', 'clproperty' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 10,
			]
		);
		$wp_customize->add_section( 'header_color_section',
			[
				'title'    => esc_html__( 'Header Color', 'clproperty' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 12,
			]
		);
		$wp_customize->add_section( 'breadcrumb_color_section',
			[
				'title'    => esc_html__( 'Breadcrumb Color', 'clproperty' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 13,
			]
		);
		$wp_customize->add_section( 'footer_color_section',
			[
				'title'    => esc_html__( 'Footer Color', 'clproperty' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 14,
			]
		);
		// Add Blog Layout Section
		$wp_customize->add_section( 'blog_layout_section',
			[
				'title'    => esc_html__( 'Blog Layout', 'clproperty' ),
				'priority' => 10,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Single Post Layout Section
		$wp_customize->add_section( 'single_post_layout_section',
			[
				'title'    => esc_html__( 'Single Post Layout', 'clproperty' ),
				'priority' => 10,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Pages Layout Section
		$wp_customize->add_section( 'page_layout_section',
			[
				'title'    => esc_html__( 'Pages Layout', 'clproperty' ),
				'priority' => 15,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Error Layout Section
		$wp_customize->add_section( 'error_layout_section',
			[
				'title'    => esc_html__( 'Error Layout', 'clproperty' ),
				'priority' => 15,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Layout Section
		$wp_customize->add_section( 'listing_archive_layout_section',
			[
				'title'    => esc_html__( 'Listing Archive Layout', 'clproperty' ),
				'priority' => 20,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'listing_single_layout_section',
			[
				'title'    => esc_html__( 'Listing Single Layout', 'clproperty' ),
				'priority' => 21,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Layout Section
		$wp_customize->add_section( 'agent_archive_layout_section',
			[
				'title'    => esc_html__( 'Agent Archive Layout', 'clproperty' ),
				'priority' => 22,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'agent_single_layout_section',
			[
				'title'    => esc_html__( 'Agent Single Layout', 'clproperty' ),
				'priority' => 23,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'store_single_layout_section',
			[
				'title'    => esc_html__( 'Agency Single Layout', 'clproperty' ),
				'priority' => 31,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Store Layout Section
		$wp_customize->add_section( 'store_archive_layout_section',
			[
				'title'    => esc_html__( 'Agency Archive Layout', 'clproperty' ),
				'priority' => 30,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		
		// Add Blog Archive Section
		$wp_customize->add_section( 'blog_archive_section',
			[
				'title'    => esc_html__( 'Blog Settings', 'clproperty' ),
				'priority' => 15,
			]
		);
		// Add Single Post Section
		$wp_customize->add_section( 'single_post_section',
			[
				'title'    => esc_html__( 'Post Details Settings', 'clproperty' ),
				'priority' => 16,
			]
		);
		
		// Contact Info
		$wp_customize->add_section( 'contact_info_section',
			[
				'title'    => esc_html__( 'Contact & Social', 'clproperty' ),
				'priority' => 17,
			]
		);
		// Contact Info
		$wp_customize->add_section( 'newsletter_section',
			[
				'title'    => esc_html__( 'Newsletter Section', 'clproperty' ),
				'priority' => 18,
			]
		);
		// Contact Info
		$wp_customize->add_section( 'social_share_section',
			[
				'title'    => esc_html__( 'Social Share Settings', 'clproperty' ),
				'priority' => 19,
			]
		);
		//listing settings section

		
		
		$wp_customize->add_section( 'listing_archive_section',
			[
				'title'    => esc_html__( 'Listing Archive Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 1,
			]
		);
		$wp_customize->add_section( 'agency_archive_section',
			[
				'title'    => esc_html__( 'Agency Archive Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 2,
			]
		);
		$wp_customize->add_section( 'agent_archive_section',
			[
				'title'    => esc_html__( 'Agent Archive Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 3,
			]
		);
		$wp_customize->add_section( 'listing_single_section',
			[
				'title'    => esc_html__( 'Listing Single Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 4,
			]
		);
		$wp_customize->add_section( 'agent_single_section',
			[
				'title'    => esc_html__( 'Agent Single Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 5,
			]
		);
		$wp_customize->add_section( 'agency_single_section',
			[
				'title'    => esc_html__( 'Agency Single Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 6,
			]
		);
		$wp_customize->add_section( 'listing_common_section',
			[
				'title'    => esc_html__( 'Listing Common Settings', 'clproperty' ),
				'panel'    => 'listings_panel',
				'priority' => 7,
			]
		);

		// Contact Info
		$wp_customize->add_section( 'woocommerce_common_section',
			[
				'title'    => esc_html__( 'WooCommerce Common', 'clproperty' ),
				'priority' => 1,
				'panel'    => 'woocommerce',
			]
		);

		// Add Error Page Section
		$wp_customize->add_section( 'error_section',
			[
				'title'    => esc_html__( 'Error Page', 'clproperty' ),
				'priority' => 19,
			]
		);
	}

}
