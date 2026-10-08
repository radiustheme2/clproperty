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

use radiustheme\ClProperty\Customizer\Controls\Customizer_Separator_Control;
use radiustheme\ClProperty\Customizer\RDTheme_Customizer;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Color_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_color_controls' ] );
	}

	public function register_color_controls( $wp_customize ) {
		//Site Color Settings
		//====================================================================================

		// Primary Color
		$wp_customize->add_setting( 'primary_color',
			[
				'default'           => $this->defaults['primary_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color',
			[
				'label'   => esc_html__( 'Primary Color', 'clproperty' ),
				'section' => 'site_color_section',
			]
		) );

		// Secondary Color
		$wp_customize->add_setting( 'secondary_color',
			[
				'default'           => $this->defaults['secondary_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color',
			[
				'label'   => esc_html__( 'Secondary Color', 'clproperty' ),
				'section' => 'site_color_section',
			]
		) );

		// Body Color
		$wp_customize->add_setting( 'body_color',
			[
				'default'           => $this->defaults['body_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_color',
			[
				'label'   => esc_html__( 'Body Color', 'clproperty' ),
				'section' => 'site_color_section',
			]
		) );

		// Body Color
		$wp_customize->add_setting( 'page_color',
			[
				'default'           => $this->defaults['page_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_color',
			[
				'label'   => esc_html__( 'Page Color', 'clproperty' ),
				'section' => 'site_color_section',
			]
		) );

		//Color Separator ------------------
		$wp_customize->add_setting( 'primary_color_separator',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_html',
			]
		);
		$wp_customize->add_control( new Customizer_Separator_Control( $wp_customize, 'primary_color_separator', [
			'settings' => 'primary_color_separator',
			'section'  => 'site_color_section',
		] ) );


		// Others Color Settings
		//========================

		$wp_customize->add_setting('gradient_dark_color', 
			array(
				'default' => $this->defaults['gradient_dark_color'],
				'type' => 'theme_mod', 
				'capability' => 'edit_theme_options', 
				'transport' => 'refresh', 
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gradient_dark_color',
			array(
				'label' => esc_html__('Gradient Dark Color', 'clproperty'),
				'section' => 'site_color_section', 
			)
		));

		$wp_customize->add_setting('gradient_light_color', 
			array(
				'default' => $this->defaults['gradient_light_color'],
				'type' => 'theme_mod', 
				'capability' => 'edit_theme_options', 
				'transport' => 'refresh', 
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gradient_light_color',
			array(
				'label' => esc_html__('Gradient Light Color', 'clproperty'),
				'section' => 'site_color_section', 
			)
		));


		// Header Color Settings
		//====================================================================================

		$wp_customize->add_setting( 'topbar_bg',
			[
				'default'           => $this->defaults['topbar_bg'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_bg',
			[
				'label'   => esc_html__( 'Topbar Background', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		$wp_customize->add_setting( 'topbar_text_color',
			[
				'default'           => $this->defaults['topbar_text_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_text_color',
			[
				'label'   => esc_html__( 'Topbar Text Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		$wp_customize->add_setting( 'topbar_icon_color',
			[
				'default'           => $this->defaults['topbar_icon_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_icon_color',
			[
				'label'   => esc_html__( 'Topbar Icon Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		//Color Separator ------------------
		$wp_customize->add_setting( 'topbar_color_separator',
			[
				'default'           => '',
				'sanitize_callback' => 'esc_html',
			]
		);
		$wp_customize->add_control( new Customizer_Separator_Control( $wp_customize, 'topbar_color_separator', [
			'settings' => 'topbar_color_separator',
			'section'  => 'header_color_section',
		] ) );

		$wp_customize->add_setting( 'menu_color',
			[
				'default'           => $this->defaults['menu_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_color',
			[
				'label'   => esc_html__( 'Menu Font Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		// Menu Color
		$wp_customize->add_setting( 'sub_menu_color',
			[
				'default'           => $this->defaults['sub_menu_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_color',
			[
				'label'   => esc_html__( 'Sub Menu Font Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		// Menu Hover Color
		$wp_customize->add_setting( 'menu_hover_color',
			[
				'default'           => $this->defaults['menu_hover_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hover_color',
			[
				'label'   => esc_html__( 'Menu Font Hover Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		// Transparent Menu Color
		$wp_customize->add_setting( 'transparent_menu_color',
			[
				'default'           => $this->defaults['transparent_menu_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'transparent_menu_color',
			[
				'label'   => esc_html__( 'Transparent Menu Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		// Transparent Menu Color
		$wp_customize->add_setting( 'transparent_menu_color_hover',
			[
				'default'           => $this->defaults['transparent_menu_color_hover'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'transparent_menu_color_hover',
			[
				'label'   => esc_html__( 'Transparent Menu Hover Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );
		
		$wp_customize->add_setting( 'menu_icon_color',
			[
				'default'           => $this->defaults['menu_icon_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_icon_color',
			[
				'label'   => esc_html__( 'Menu Icon Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );
		$wp_customize->add_setting( 'menu_icon_tr_color',
			[
				'default'           => $this->defaults['menu_icon_tr_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_icon_tr_color',
			[
				'label'   => esc_html__( 'Transparent Menu Icon  Color', 'clproperty' ),
				'section' => 'header_color_section',
			]
		) );

		// Breadcrumb Color Settings
		//====================================================================================

		$wp_customize->add_setting( 'breadcrumb_bg1',
			[
				'default'           => $this->defaults['breadcrumb_bg1'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumb_bg1',
			[
				'label'   => esc_html__( 'Breadcrumb Background', 'clproperty' ),
				'section' => 'breadcrumb_color_section',
			]
		) );

		// Breadcrumb Title Color
		$wp_customize->add_setting( 'breadcrumb_title_color',
			[
				'default'           => $this->defaults['breadcrumb_title_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumb_title_color',
			[
				'label'   => esc_html__( 'Breadcrumb Title Color', 'clproperty' ),
				'section' => 'breadcrumb_color_section',
			]
		) );

		// Breadcrumb Text Color
		$wp_customize->add_setting( 'breadcrumb_color',
			[
				'default'           => $this->defaults['breadcrumb_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumb_color',
			[
				'label'   => esc_html__( 'Breadcrumb Text Color', 'clproperty' ),
				'section' => 'breadcrumb_color_section',
			]
		) );
		// Breadcrumb Active Color
		$wp_customize->add_setting( 'breadcrumb_active_color',
			[
				'default'           => $this->defaults['breadcrumb_active_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'breadcrumb_active_color',
			[
				'label'   => esc_html__( 'Breadcrumb Active Color', 'clproperty' ),
				'section' => 'breadcrumb_color_section',
			]
		) );

		//Footer Color
		//====================================================================================

		// Footer Background
		$wp_customize->add_setting( 'footer_bg',
			[
				'default'           => $this->defaults['footer_bg'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bg',
			[
				'label'       => esc_html__( 'Footer Background', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );

		// Footer Text Color
		$wp_customize->add_setting( 'footer_text_color',
			[
				'default'           => $this->defaults['footer_text_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color',
			[
				'label'       => esc_html__( 'Footer Text color', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );

		// Footer Widget Title Color
		$wp_customize->add_setting( 'footer_title_color',
			[
				'default'           => $this->defaults['footer_title_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_title_color',
			[
				'label'       => esc_html__( 'Footer Widget Title Color', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );

		// Footer Widget Link Color
		$wp_customize->add_setting( 'footer_link_color',
			[
				'default'           => $this->defaults['footer_link_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_link_color',
			[
				'label'       => esc_html__( 'Footer Widget Link Color', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );

		// Footer Widget Link Hover Color
		$wp_customize->add_setting( 'footer_link_hover_color',
			[
				'default'           => $this->defaults['footer_link_hover_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_link_hover_color',
			[
				'label'       => esc_html__( 'Footer Widget Link Hover Color', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );

		// Copyright Background
		$wp_customize->add_setting( 'copyright_bg',
			[
				'default'           => $this->defaults['copyright_bg'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'copyright_bg',
			[
				'label'       => esc_html__( 'Copyright Background', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );



		// Copyright Text Color
		$wp_customize->add_setting( 'copyright_text_color',
			[
				'default'           => $this->defaults['copyright_text_color'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			]
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'copyright_text_color',
			[
				'label'       => esc_html__( 'Copyright Text Color', 'clproperty' ),
				'section'     => 'footer_color_section',
			]
		) );


	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Color_Settings();
}