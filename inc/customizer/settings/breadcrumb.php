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
use radiustheme\ClProperty\Customizer\Controls\Customizer_Separator_Control;
use WP_Customize_Media_Control;


/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Breadcrumb_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_general_controls' ] );
	}

	public function register_general_controls( $wp_customize ) {

		// Breadcrumb
		$wp_customize->add_setting( 'breadcrumb',
			[
				'default'           => $this->defaults['breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'breadcrumb',
			[
				'label'   => __( 'Breadcrumb Visibility', 'clproperty' ),
				'section' => 'breadcrumb_section',
			]
		) );
		
		// Breadcrumb Image
		$wp_customize->add_setting( 'banner_image',
			[
				'default'           => $this->defaults['banner_image'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'banner_image',
			[
				'label'         => esc_html__( 'Banner/Breadcrumb Background Image', 'clproperty' ),
				'description'   => esc_html__( 'Add image to change banner background image', 'clproperty' ),
				'section'       => 'breadcrumb_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => esc_html__( 'Select File', 'clproperty' ),
					'change'       => esc_html__( 'Change File', 'clproperty' ),
					'default'      => esc_html__( 'Default', 'clproperty' ),
					'remove'       => esc_html__( 'Remove', 'clproperty' ),
					'placeholder'  => esc_html__( 'No file selected', 'clproperty' ),
					'frame_title'  => esc_html__( 'Select File', 'clproperty' ),
					'frame_button' => esc_html__( 'Choose File', 'clproperty' ),
				],
			]
		) );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Breadcrumb_Settings();
}
