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
use radiustheme\ClProperty\Customizer\Controls\Customizer_Switch_Control;
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Footer_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_footer_controls' ] );
	}

	public function register_footer_controls( $wp_customize ) {
		// Footer Style
		$wp_customize->add_setting( 'footer_style',
			[
				'default'           => $this->defaults['footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'footer_style',
			[
				'label'       => __( 'Footer Layout', 'clproperty' ),
				'description' => esc_html__( 'Select the header style', 'clproperty' ),
				'section'     => 'footer_section',
				'choices'     => [
					'1' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/footer-1.png',
						'name'  => __( 'Style 1', 'clproperty' ),
					],
				],
			]
		) );
		// Footer Border
		$wp_customize->add_setting( 'footer_border',
			[
				'default'           => $this->defaults['footer_border'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_border',
			[
				'label'   => __( 'Footer Border Top', 'clproperty' ),
				'section' => 'footer_section',
			]
		) );

		// Copyright Area Control
		$wp_customize->add_setting( 'copyright_area',
			[
				'default'           => $this->defaults['copyright_area'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'copyright_area',
			[
				'label'   => __( 'Display Copyright Area', 'clproperty' ),
				'section' => 'footer_section',
			]
		) );
		// Copyright Text
		$wp_customize->add_setting( 'copyright_text',
			[
				'default'           => $this->defaults['copyright_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_textarea_sanitization',
			]
		);
		$wp_customize->add_control( 'copyright_text',
			[
				'label'           => __( 'Copyright Text', 'clproperty' ),
				'section'         => 'footer_section',
				'type'            => 'textarea',
				'active_callback' => 'rttheme_is_copyright_area_enabled',
			]
		);

		
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Footer_Settings();
}
