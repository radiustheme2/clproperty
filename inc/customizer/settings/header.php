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

use radiustheme\ClProperty\Customizer\RDTheme_Customizer;
use radiustheme\ClProperty\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\ClProperty\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\ClProperty\Helper;
use WP_Customize_Color_Control;
use radiustheme\ClProperty\Customizer\Controls\Customizer_Alfa_Color;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Header_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_header_controls' ] );
	}

	public function register_header_controls( $wp_customize ) {
		// Header Style
		$wp_customize->add_setting( 'header_style',
			[
				'default'           => $this->defaults['header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'header_style',
			[
				'label'       => __( 'Header Layout', 'clproperty' ),
				'description' => esc_html__( 'Select the header style', 'clproperty' ),
				'section'     => 'header_main_section',
				'choices'     => Helper::get_clproperty_header_list('header'),
			]
		) );


		//Header width
		$wp_customize->add_setting( 'header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['header_width'],
		] );

		$wp_customize->add_control( 'header_width', [
			'type'    => 'select',
			'section' => 'header_main_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'clproperty' ),
			'choices' => [
				'box-width' => __( 'Box width', 'clproperty' ),
				'fullwidth' => __( 'Fullwidth', 'clproperty' ),
			],
		] );

		// Top bar
		$wp_customize->add_setting( 'top_bar',
			[
				'default'           => $this->defaults['top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'top_bar',
			[
				'label'   => __( 'Top Bar', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );

		// Sticky Header Control
		$wp_customize->add_setting( 'sticky_header',
			[
				'default'           => $this->defaults['sticky_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'sticky_header',
			[
				'label'       => __( 'Sticky Header', 'clproperty' ),
				'description' => __( 'Show header at the top when scrolling down', 'clproperty' ),
				'section'     => 'header_main_section',
			]
		) );

		// Transparent Header
		$wp_customize->add_setting( 'tr_header',
			[
				'default'           => $this->defaults['tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'tr_header',
			[
				'label'       => __( 'Transparent Header', 'clproperty' ),
				'description' => __( 'You have to enable Banner or Slider in page to make it work properly', 'clproperty' ),
				'section'     => 'header_main_section',
			]
		) );

		//Transparent Header BG Color
		$wp_customize->add_setting( 'header_transparent_color',
			[
				'default'           => $this->defaults['header_transparent_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Alfa_Color( $wp_customize, 'header_transparent_color',
			[
				'label'           => __( 'Transparent Background Color', 'clproperty' ),
				'section'         => 'header_main_section',
				'active_callback' => 'rttheme_is_trheader_enable',
			]
		) );


		// Button Control
		$wp_customize->add_setting( 'header_btn',
			[
				'default'           => $this->defaults['header_btn'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_btn',
			[
				'label'   => __( 'Header Right Button', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );

		// Button Text
		$wp_customize->add_setting( 'header_btn_txt',
			[
				'default'           => $this->defaults['header_btn_txt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_txt',
			[
				'label'           => __( 'Button Text', 'clproperty' ),
				'section'         => 'header_main_section',
				'type'            => 'text',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);
		// Button URL
		$wp_customize->add_setting( 'header_btn_url',
			[
				'default'           => $this->defaults['header_btn_url'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_url_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_url',
			[
				'label'           => __( 'Button Link', 'clproperty' ),
				'section'         => 'header_main_section',
				'type'            => 'url',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);


		// Header Login Icon Visibility
		$wp_customize->add_setting( 'header_login_icon',
			[
				'default'           => $this->defaults['header_login_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_login_icon',
			[
				'label'   => __( 'Header Login Icon Visibility', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );



		// Header Fav Icon
		$wp_customize->add_setting( 'header_fav_icon',
			[
				'default'           => $this->defaults['header_fav_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_fav_icon',
			[
				'label'   => __( 'Header Favourite Icon Visibility', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );


		// Header Compare Icon
		$wp_customize->add_setting( 'header_compare_icon',
			[
				'default'           => $this->defaults['header_compare_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_compare_icon',
			[
				'label'   => __( 'Header Compare Icon Visibility', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );

		// Header Cart Icon
		$wp_customize->add_setting( 'header_search_icon',
			[
				'default'           => $this->defaults['header_search_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_search_icon',
			[
				'label'   => __( 'Header Search Icon Visibility', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );

		// Mobile Header Topbar
		$wp_customize->add_setting( 'header_mobile_topbar',
			[
				'default'           => $this->defaults['header_mobile_topbar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_topbar',
			[
				'label'   => __( 'Mobile Header Topbar', 'clproperty' ),
				'section' => 'header_main_section',
			]
		) );

	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Header_Settings();
}
