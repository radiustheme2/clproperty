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
use radiustheme\ClProperty\Customizer\Controls\Customizer_Multiple_Checkbox_Control;
use WP_Customize_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Single_Post_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_single_post_controls' ] );
	}

	public function register_single_post_controls( $wp_customize ) {
		$wp_customize->add_setting( 'post_date',
			[
				'default'           => $this->defaults['post_date'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_date',
			[
				'label'   => __( 'Display Date', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_author_name',
			[
				'default'           => $this->defaults['post_author_name'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_author_name',
			[
				'label'   => __( 'Display Author Name', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_comment_num',
			[
				'default'           => $this->defaults['post_comment_num'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_comment_num',
			[
				'label'   => __( 'Display Comment Count', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_cats',
			[
				'default'           => $this->defaults['post_cats'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_cats',
			[
				'label'   => __( 'Display Category', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_details_related_section',
			[
				'default'           => $this->defaults['post_details_related_section'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_details_related_section',
			[
				'label'   => __( 'Display Related Posts', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_details_reading_time',
			[
				'default'           => $this->defaults['post_details_reading_time'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_details_reading_time',
			[
				'label'   => __( 'Display Post Reading Time', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_tag',
			[
				'default'           => $this->defaults['post_tag'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_tag',
			[
				'label'   => __( 'Display Tag', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_social_icon',
			[
				'default'           => $this->defaults['post_social_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_social_icon',
			[
				'label'   => __( 'Display Social Share', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		//Single post navigation
		$wp_customize->add_setting( 'post_navigation',
			[
				'default'           => $this->defaults['post_navigation'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_navigation',
			[
				'label'   => __( 'Display Navigation', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );

		$wp_customize->add_setting( 'post_author_about',
			[
				'default'           => $this->defaults['post_author_about'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'post_author_about',
			[
				'label'   => __( 'Display Author About', 'clproperty' ),
				'section' => 'single_post_section',
			]
		) );
		
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Single_Post_Settings();
}
