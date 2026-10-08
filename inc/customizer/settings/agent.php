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
use radiustheme\ClProperty\Customizer\Controls\Customizer_Heading_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Agent_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'show_listing_count',
			[
				'default'           => $this->defaults['show_listing_count'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_listing_count',
				[
					'label'   => esc_html__( 'Show Listing Count', 'clproperty' ),
					'section' => 'agent_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agent_ratings',
			[
				'default'           => $this->defaults['show_agent_ratings'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agent_ratings',
				[
					'label'   => esc_html__( 'Show Agent Ratings', 'clproperty' ),
					'section' => 'agent_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agent_phone',
			[
				'default'           => $this->defaults['show_agent_phone'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agent_phone',
				[
					'label'   => esc_html__( 'Show Agent Phone', 'clproperty' ),
					'section' => 'agent_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agent_mail',
			[
				'default'           => $this->defaults['show_agent_mail'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agent_mail',
				[
					'label'   => esc_html__( 'Show Agent Email', 'clproperty' ),
					'section' => 'agent_archive_section',
				]
			) 
		);
		//Agent Single Settings

		$wp_customize->add_setting(
			'agent_single_rating',
			[
				'default'           => $this->defaults['agent_single_rating'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'agent_single_rating',
				[
					'label'   => esc_html__( 'Agent Rating Visibility', 'clproperty' ),
					'section' => 'agent_single_section',
				]
		) );

		$wp_customize->add_setting(
			'agent_single_listing_count',
			[
				'default'           => $this->defaults['agent_single_listing_count'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'agent_single_listing_count',
				[
					'label'   => esc_html__( 'Agent Listing Count Visibility', 'clproperty' ),
					'section' => 'agent_single_section',
				]
		) );

		$wp_customize->add_setting(
			'agent_single_listing_specialty',
			[
				'default'           => $this->defaults['agent_single_listing_specialty'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'agent_single_listing_specialty',
				[
					'label'   => esc_html__( 'Agent Specialty Visibility', 'clproperty' ),
					'section' => 'agent_single_section',
				]
		) );

		$wp_customize->add_setting(
			'agent_services',
			[
				'default'           => $this->defaults['agent_services'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'agent_services',
				[
					'label'   => esc_html__( 'Agent Service Visibility', 'clproperty' ),
					'section' => 'agent_single_section',
				]
		) );

		$wp_customize->add_setting( 'listing_agent_listing_per',
            array(
                'default' => $this->defaults['listing_agent_listing_per'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_agent_listing_per',
            array(
                'label' => __( 'Agent/Store Listing Per Page', 'clproperty' ),
                'section' => 'agent_single_section',
                'type' => 'number',
            )
        );
 
	}


}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Agent_Settings();
}
