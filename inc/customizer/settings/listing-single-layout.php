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

use radiustheme\ClProperty\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\ClProperty\Customizer\RDTheme_Customizer;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Single_Layout_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listing_single_layout_controls' ] );
	}

	public function register_listing_single_layout_controls( $wp_customize ) {

		// Top bar
		$wp_customize->add_setting( 'listing_single_top_bar',
			[
				'default'           => $this->defaults['listing_single_top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_top_bar', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Top Bar', 'clproperty' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clproperty' ),
				'on'      => esc_html__( 'Enable', 'clproperty' ),
				'off'     => esc_html__( 'Disable', 'clproperty' ),
			],
		] );
		// Header Layout
		$wp_customize->add_setting( 'listing_single_header_style',
			[
				'default'           => $this->defaults['listing_single_header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_header_style', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Header Layout', 'clproperty' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clproperty' ),
				'1'       => esc_html__( 'Layout 1', 'clproperty' ),
				'2'       => esc_html__( 'Layout 2', 'clproperty' ),
				'3'       => esc_html__( 'Layout 2', 'clproperty' ),
				'4'       => esc_html__( 'Layout 2', 'clproperty' ),
			],
		] );



		//Header width
		$wp_customize->add_setting( 'listing_single_header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['listing_single_header_width'],
		] );

		$wp_customize->add_control( 'listing_single_header_width', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'clproperty' ),
			'choices' => [
				'default'   => __( 'Default', 'clproperty' ),
				'box-width' => __( 'Box width', 'clproperty' ),
				'fullwidth' => __( 'Fullwidth', 'clproperty' ),
			],
		] );

		// Transparent Header
		$wp_customize->add_setting( 'listing_single_tr_header',
			[
				'default'           => $this->defaults['listing_single_tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_tr_header', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Transparent Header', 'clproperty' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clproperty' ),
				'on'      => esc_html__( 'Enable', 'clproperty' ),
				'off'     => esc_html__( 'Disable', 'clproperty' ),
			],
		] );
		// Breadcrumb
		$wp_customize->add_setting( 'listing_single_breadcrumb',
			[
				'default'           => $this->defaults['listing_single_breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_breadcrumb', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Breadcrumb', 'clproperty' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clproperty' ),
				'on'      => esc_html__( 'Enable', 'clproperty' ),
				'off'     => esc_html__( 'Disable', 'clproperty' ),
			],
		] );
		// Footer Layout
		$wp_customize->add_setting( 'listing_single_footer_style',
			[
				'default'           => $this->defaults['listing_single_footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_footer_style', [
			'type'    => 'select',
			'section' => 'listing_single_layout_section',
			'label'   => esc_html__( 'Footer Layout', 'clproperty' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'clproperty' ),
				'1'       => esc_html__( 'Layout 1', 'clproperty' ),
				'2'       => esc_html__( 'Layout 2', 'clproperty' ),
			],
		] );

		// Padding Top
		$wp_customize->add_setting( 'listing_single_padding_top',
			[
				'default'           => $this->defaults['listing_single_padding_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_padding_top',
			[
				'label'       => esc_html__( 'Content Padding Top', 'clproperty' ),
				'description' => esc_html__( 'Listing Single Content Padding Top(Use px unit after digit)', 'clproperty' ),
				'section'     => 'listing_single_layout_section',
				'type'        => 'text',
			]
		);
		// Padding Bottom
		$wp_customize->add_setting( 'listing_single_padding_bottom',
			[
				'default'           => $this->defaults['listing_single_padding_bottom'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_single_padding_bottom',
			[
				'label'       => esc_html__( 'Content Padding Bottom', 'clproperty' ),
				'description' => esc_html__( 'Listing Single Content Padding Bottom(Use px unit after digit)', 'clproperty' ),
				'section'     => 'listing_single_layout_section',
				'type'        => 'text',
			]
		);
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Single_Layout_Settings();
}
