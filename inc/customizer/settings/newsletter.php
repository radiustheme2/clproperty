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
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Newsletter_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_error_controls' ] );
	}

	public function register_error_controls( $wp_customize ) {
		$wp_customize->add_setting( 'newsletter_section',
			[
				'default'           => $this->defaults['newsletter_section'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'newsletter_section',
			[
				'label'   => __( 'Show Newsletter ?', 'clproperty' ),
				'section' => 'newsletter_section',
			]
		) );
		$wp_customize->add_setting( 'newsletter_title',
			[
				'default'           => $this->defaults['newsletter_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_clproperty_sanitization',
			]
		);
		$wp_customize->add_control( 'newsletter_title',
			[
				'label'   => __( 'Newsletter Title', 'clproperty' ),
				'section' => 'newsletter_section',
				'type'    => 'clproperty',
			]
		);
		$wp_customize->add_setting( 'newsletter_sub_title',
			[
				'default'           => $this->defaults['newsletter_sub_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_clproperty_sanitization',
			]
		);
		$wp_customize->add_control( 'newsletter_sub_title',
			[
				'label'   => __( 'Newsletter Title', 'clproperty' ),
				'section' => 'newsletter_section',
				'type'    => 'clproperty',
			]
		);
		$wp_customize->add_setting( 'newsletter_img',
        array(
            'default' => $this->defaults['newsletter_img'],
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'newsletter_img',
            array(
                'label' => __( 'Newsletter Building Image', 'clproperty' ),
                'description' => esc_html__( 'This is the description for the Media Control', 'clproperty' ),
                'section' => 'newsletter_section',
                'mime_type' => 'image',
                'button_labels' => array(
                    'select' => __( 'Select File', 'clproperty' ),
                    'change' => __( 'Change File', 'clproperty' ),
                    'default' => __( 'Default', 'clproperty' ),
                    'remove' => __( 'Remove', 'clproperty' ),
                    'placeholder' => __( 'No file selected', 'clproperty' ),
                    'frame_title' => __( 'Select File', 'clproperty' ),
                    'frame_button' => __( 'Choose File', 'clproperty' ),
                ),
            )
        ) );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Newsletter_Settings();
}
