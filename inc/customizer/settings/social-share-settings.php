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
use radiustheme\ClProperty\Customizer\Controls\Customizer_Multiple_Checkbox_Control;
use WP_Customize_Control;
/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Social_Share_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_error_controls' ] );
	}

	public function register_error_controls( $wp_customize ) {
		// Social Share Facebook
		$wp_customize->add_setting( 'social_facebook', [
			'default'           => $this->defaults['social_facebook'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_facebook',
			[
				'label'   => __( 'Hide Facebook?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );
		// Social Share Twitter
		$wp_customize->add_setting( 'social_twitter', [
			'default'           => $this->defaults['social_twitter'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_twitter',
			[
				'label'   => __( 'Hide Twitter?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );
		// Social Share Linkedin
		$wp_customize->add_setting( 'social_linkedin', [
			'default'           => $this->defaults['social_linkedin'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_linkedin',
			[
				'label'   => __( 'Hide Linkedin?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );
		// Social Share Pinterest
		$wp_customize->add_setting( 'social_pinterest', [
			'default'           => $this->defaults['social_pinterest'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_pinterest',
			[
				'label'   => __( 'Hide Pinterest?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );
		// Social Share Tumblr
		$wp_customize->add_setting( 'social_tumblr', [
			'default'           => $this->defaults['social_tumblr'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_tumblr',
			[
				'label'   => __( 'Hide Tumblr?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );
		// Social Share Reddit
		$wp_customize->add_setting( 'social_reddit', [
			'default'           => $this->defaults['social_reddit'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_reddit',
			[
				'label'   => __( 'Hide Reddit?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );
		// Social Share VK
		$wp_customize->add_setting( 'social_vk', [
			'default'           => $this->defaults['social_vk'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'social_vk',
			[
				'label'   => __( 'Hide VK?', 'clproperty' ),
				'section' => 'social_share_section',
				'type'    => 'checkbox',
			]
		) );

	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Social_Share_Settings();
}
