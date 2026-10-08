<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty\Customizer\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use radiustheme\ClProperty\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\ClProperty\Customizer\RDTheme_Customizer;
use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\Customizer\Controls\Customizer_Heading_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listings_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		$group_list = $this->custom_field_group_list();


		// Single Listing Layout
		$wp_customize->add_setting(
			'single_listing_style',
			[
				'default'           => $this->defaults['single_listing_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'single_listing_style',
			[
				'label'       => esc_html__( 'Property Details Style', 'clproperty' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select property details page style', 'clproperty' ),
				'type'        => 'select',
				'choices'     => [
					'1' => esc_html__( 'Style 1', 'clproperty' ),
					'2' => esc_html__( 'Style 2', 'clproperty' ),
				],
			]
		);
		// Custom Field Group List
		$wp_customize->add_setting(
			'custom_group_individual',
			[
				'default'           => $this->defaults['custom_group_individual'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'custom_group_individual',
			[
				'label'       => esc_html__( 'Individual Custom Field Group', 'clproperty' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select a group to show in listing details page as different section', 'clproperty' ),
				'type'        => 'select',
				'choices'     => $group_list,
			]
		);
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'listing_detail_sidebar',
			[
				'default'           => $this->defaults['listing_detail_sidebar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'listing_detail_sidebar',
				[
					'label'   => esc_html__( 'Listing Sidebar Visibility', 'clproperty' ),
					'section' => 'listing_single_section',
				]
			) 
		);

		// Enable WalkScore
		$wp_customize->add_setting(
			'walkscore_control',
			[
				'default'           => $this->defaults['walkscore_control'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'walkscore_control',
				[
					'label'   => esc_html__( 'WalkScore Visibility', 'clproperty' ),
					'section' => 'listing_single_section',
				]
		) );
		// WalkScore Title
		$wp_customize->add_setting(
			'walkscore_title',
			[
				'default'           => $this->defaults['walkscore_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'walkscore_title',
			[
				'label'       => esc_html__( 'WalkScore Title', 'clproperty' ),
				'description' => esc_html__( 'Add title for walkscore section', 'clproperty' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);

		// WalkScore API
		$wp_customize->add_setting(
			'walkscore_api_key',
			[
				'default'           => $this->defaults['walkscore_api_key'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'walkscore_api_key',
			[
				'label'       => esc_html__( 'WalkScore API Key', 'clproperty' ),
				'description' => esc_html__( 'Add API Key provided from walkscore', 'clproperty' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);
		// Overview Visibility
		$wp_customize->add_setting(
			'overview_show_hide',
			[
				'default'           => $this->defaults['overview_show_hide'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'overview_show_hide',
				[
					'label'   => esc_html__( 'Overview Visibility', 'clproperty' ),
					'section' => 'listing_single_section',
				]
			) );

		//Overview Title
		$wp_customize->add_setting(
			'overview_text',
			[
				'default'           => $this->defaults['overview_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'overview_text',
			[
				'label'       => esc_html__( 'Overview text', 'clproperty' ),
				'description' => esc_html__( 'You may change Overview title from here', 'clproperty' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);

		// Features & Amenities Visibility
		$wp_customize->add_setting(
			'feature_aminities_show_hide',
			[
				'default'           => $this->defaults['feature_aminities_show_hide'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'feature_aminities_show_hide',
				[
					'label'   => esc_html__( 'Features & Amenities Visibility', 'clproperty' ),
					'section' => 'listing_single_section',
				]
			) );

		//Features & Amenities Title
		$wp_customize->add_setting(
			'feature_text',
			[
				'default'           => $this->defaults['feature_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'feature_text',
			[
				'label'       => esc_html__( 'Feature & Aminities Text', 'clproperty' ),
				'description' => esc_html__( 'You may change Features & Amenities title from here', 'clproperty' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);

		$wp_customize->add_setting(
			'show_related_listing',
			[
				'default'           => $this->defaults['show_related_listing'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_related_listing',
				[
					'label'       => esc_html__( 'Show Related Listing', 'clproperty' ),
					'description' => esc_html__( 'Show or hide related listing from listing details page', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );

		$wp_customize->add_setting(
				'show_listing_button_area',
				[
					'default'           => $this->defaults['show_listing_button_area'],
					'transport'         => 'refresh',
					'sanitize_callback' => 'rttheme_switch_sanitization',
				]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_listing_button_area',
				[
					'label'       => esc_html__( 'Show Button Area', 'clproperty' ),
					'description' => esc_html__( 'Show or hide button area from single listing page header', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );

		// Show Store Info on details page
		$wp_customize->add_setting(
			'show_user_info_on_details',
			[
				'default'           => $this->defaults['show_user_info_on_details'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'show_user_info_on_details',
			[
				'label'       => esc_html__( 'Select Owner/Store info', 'clproperty' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Show Store or Owner info on Details page', 'clproperty' ),
				'type'        => 'select',
				'choices'     => [
					'show_owner_info' => esc_html__( 'Show Owner Info', 'clproperty' ),
					'show_store_info' => esc_html__( 'Show Store Info', 'clproperty' ),
				],
			]
		);
		$wp_customize->add_setting(
			'show_owner_store_title',
			[
				'default'           => $this->defaults['show_owner_store_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_title',
				[
					'label'       => esc_html__( 'Store/Owner Widget Title', 'clproperty' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Title', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_whatsapp',
			[
				'default'           => $this->defaults['show_owner_store_whatsapp'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_whatsapp',
				[
					'label'       => esc_html__( 'Store/Owner Widget What\'s App (Deprecated)', 'clproperty' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget What\'s App Number', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_email',
			[
				'default'           => $this->defaults['show_owner_store_email'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_email',
				[
					'label'       => esc_html__( 'Store/Owner Widget Email (Deprecated)', 'clproperty' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Email', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_rating',
			[
				'default'           => $this->defaults['show_owner_store_rating'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_rating',
				[
					'label'       => esc_html__( 'Store/Owner Widget Rating', 'clproperty' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Rating', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_listing_slider_nav',
			[
				'default'           => $this->defaults['show_listing_slider_nav'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_listing_slider_nav',
				[
					'label'       => esc_html__( 'Listing Slider Navigation', 'clproperty' ),
					'description' => esc_html__( 'Show or hide button area from single listing single page', 'clproperty' ),
					'section'     => 'listing_single_section',
				]
		) );
		//Listing owner title text
		$wp_customize->add_setting(
			'listing_owner_widget_title',
			[
				'default'           => $this->defaults['listing_owner_widget_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_owner_widget_title',
			[
				'label'       => esc_html__( 'Listing owner widget title', 'clproperty' ),
				'description' => esc_html__( 'You may change Listing widget title', 'clproperty' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);
		// Remove Listing Type Prefix
		$wp_customize->add_setting(
			'remove_listing_type_prefix',
			[
				'default'           => $this->defaults['remove_listing_type_prefix'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'remove_listing_type_prefix',
				[
					'label'       => esc_html__( 'Remove Listing Type Prefix (Eg. For)', 'clproperty' ),
					'description' => esc_html__( 'Check for hiding listing type prefix (For) from everywhere.', 'clproperty' ),
					'section'     => 'listing_common_section',
				]
			) );

		//Features & Amenities Title
		$wp_customize->add_setting(
			'listing_type_prefix_text',
			[
				'default'           => $this->defaults['listing_type_prefix_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_type_prefix_text',
			[
				'label'       => esc_html__( 'Listing type prefix text', 'clproperty' ),
				'description' => esc_html__( 'You may change Listing type prefix text from here', 'clproperty' ),
				'section'     => 'listing_common_section',
				'type'        => 'text',
			]
		);

		

		// listing archive page settings
		
		$wp_customize->add_setting( 'show_listing_custom_fields',
			[
				'default'           => $this->defaults['show_listing_custom_fields'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_listing_custom_fields',
			[
				'label'       => esc_html__( 'Show Listing Custom Field', 'clproperty' ),
				'description' => esc_html__( 'Show or hide listing custom fields from listing archive page', 'clproperty' ),
				'section' => 'listing_archive_section',
			]
		) );

	
		$wp_customize->add_setting( 'listing_author_meta',
			[
				'default'           => $this->defaults['listing_author_meta'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'listing_author_meta',
			[
				'label'   => __( 'Listing Author Meta', 'clproperty' ),
				'section' => 'listing_archive_section',
			]
		) );
			
		$wp_customize->add_setting( 'listing_arexcerpt_limit',
            array(
                'default' => $this->defaults['listing_arexcerpt_limit'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_arexcerpt_limit',
            array(
                'label' => __( 'Listing Archive Excerpt Limit', 'clproperty' ),
                'section' => 'listing_archive_section',
                'type' => 'number',
            )
        );

		$wp_customize->add_setting( 'listing_author_btn_text',
            array(
                'default' => $this->defaults['listing_author_btn_text'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_author_btn_text',
            array(
                'label' => __( 'Listing Author Button Text', 'clproperty' ),
                'section' => 'listing_archive_section',
                'type' => 'text',
            )
        );

		$wp_customize->add_setting( 'listing_slider_autoplay', [
			'transport' => 'refresh',
			'default'           => $this->defaults['listing_slider_autoplay'],
			'sanitize_callback' => 'rttheme_radio_sanitization',
		] );

		$wp_customize->add_control( 'listing_slider_autoplay', [
			'type'    => 'select',
			'section' => 'listing_archive_section', // Add a default or your own section
			'label'   => __( 'Listing Slider Autoplay', 'clproperty' ),
			'choices' => [
				'yes' => __( 'Enable', 'clproperty' ),
				'no' => __( 'Disable', 'clproperty' ),
			],
		] );

		$wp_customize->add_setting( 'listing_slider_autoplay_delay', [
			'transport' => 'refresh',
			'default'           => $this->defaults['listing_slider_autoplay_delay'],
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );

		$wp_customize->add_control( 'listing_slider_autoplay_delay', [
			'type'    => 'number',
			'section' => 'listing_archive_section', // Add a default or your own section
			'label'   => __( 'Listing Slider Autoplay Delay', 'clproperty' ),
		] );

		$wp_customize->add_setting('map_listing_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'map_listing_heading', array(
            'label' => __( 'Map Listing Archive Page', 'clproperty' ),
            'section' => 'listing_archive_section',
        )));

		$wp_customize->add_setting( 'listing_map_per_page', [
			'transport' => 'refresh',
			'default'           => $this->defaults['listing_map_per_page'],
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );

		$wp_customize->add_control( 'listing_map_per_page', [
			'type'    => 'number',
			'section' => 'listing_archive_section', // Add a default or your own section
			'label'   => __( 'Listing Per Page', 'clproperty' ), 
		] );


		//Listing Min Price
		$wp_customize->add_setting(
			'listing_widget_min_price',
			[
				'default'           => $this->defaults['listing_widget_min_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_min_price',
			[
				'label'       => esc_html__( 'Listing min price ', 'clproperty' ),
				'description' => esc_html__( 'This settings for listing map search & banner search widget', 'clproperty' ),
				'section'     => 'listing_common_section',
				'type'        => 'text',
			]
		);
		//Listing Max Price
		$wp_customize->add_setting(
			'listing_widget_max_price',
			[
				'default'           => $this->defaults['listing_widget_max_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_max_price',
			[
				'label'       => esc_html__( 'Listing max price ', 'clproperty' ),
				'description' => esc_html__( 'This settings for listing map search & banner search widget', 'clproperty' ),
				'section'     => 'listing_common_section',
				'type'        => 'text',
			]
		);
	}

	public function custom_field_group_list() {
		$group_ids = Functions::get_cfg_ids();

		$list = [];
		foreach ( $group_ids as $id ) {
			$list[ $id ] = get_the_title( $id );
		}

		return $list;
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listings_Settings();
}
