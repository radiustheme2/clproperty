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
use WP_Customize_Media_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Error_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_error_controls' ] );
	}

	public function register_error_controls( $wp_customize ) {
		$wp_customize->add_setting( 'error_bodybanner',
			[
				'default'           => $this->defaults['error_bodybanner'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'error_bodybanner',
			[
				'label'         => __( 'Featured Image', 'clproperty' ),
				'section'       => 'error_section',
				'mime_type'     => 'image',
				'button_labels' => [
					'select'       => __( 'Select File', 'clproperty' ),
					'change'       => __( 'Change File', 'clproperty' ),
					'default'      => __( 'Default', 'clproperty' ),
					'remove'       => __( 'Remove', 'clproperty' ),
					'placeholder'  => __( 'No file selected', 'clproperty' ),
					'frame_title'  => __( 'Select File', 'clproperty' ),
					'frame_button' => __( 'Choose File', 'clproperty' ),
				],
			]
		) );
		// Error Text
		$wp_customize->add_setting( 'error_text',
			[
				'default'           => $this->defaults['error_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_kses_post',
			]
		);
		$wp_customize->add_control( 'error_text',
			[
				'label'   => __( 'Error Text', 'clproperty' ),
				'section' => 'error_section',
				'type'    => 'text',
			]
		);
		// Error Subtitle
		$wp_customize->add_setting( 'error_subtitle',
			[
				'default'           => $this->defaults['error_subtitle'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'wp_kses_post',
			]
		);
		$wp_customize->add_control( 'error_subtitle',
			[
				'label'   => __( 'Error Subtitle', 'clproperty' ),
				'section' => 'error_section',
				'type'    => 'text',
			]
		);
		// Button Text
		$wp_customize->add_setting( 'error_buttontext',
			[
				'default'           => $this->defaults['error_buttontext'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'error_buttontext',
			[
				'label'   => __( 'Button Text', 'clproperty' ),
				'section' => 'error_section',
				'type'    => 'text',
			]
		);
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Error_Settings();
}
