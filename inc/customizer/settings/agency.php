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
class RDTheme_Agency_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'show_ad_count',
			[
				'default'           => $this->defaults['show_ad_count'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_ad_count',
				[
					'label'   => esc_html__( 'Show Ad Count', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_views',
			[
				'default'           => $this->defaults['show_agency_views'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_views',
				[
					'label'   => esc_html__( 'Show Agency Views', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_ratings',
			[
				'default'           => $this->defaults['show_agency_ratings'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_ratings',
				[
					'label'   => esc_html__( 'Show Agency Ratings', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_location',
			[
				'default'           => $this->defaults['show_agency_location'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_location',
				[
					'label'   => esc_html__( 'Show Agency Location', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_excerpt',
			[
				'default'           => $this->defaults['show_agency_excerpt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_excerpt',
				[
					'label'   => esc_html__( 'Show Agency Excerpt', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_phone',
			[
				'default'           => $this->defaults['show_agency_phone'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_phone',
				[
					'label'   => esc_html__( 'Show Agency Phone', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_mail',
			[
				'default'           => $this->defaults['show_agency_mail'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_mail',
				[
					'label'   => esc_html__( 'Show Agency Email', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_webaddress',
			[
				'default'           => $this->defaults['show_agency_webaddress'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_webaddress',
				[
					'label'   => esc_html__( 'Show Agency Website Link', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_agency_social_share',
			[
				'default'           => $this->defaults['show_agency_social_share'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_agency_social_share',
				[
					'label'   => esc_html__( 'Show Agency Social Share', 'clproperty' ),
					'section' => 'agency_archive_section',
				]
			) 
		);
		//agency single settings
		$wp_customize->add_setting(
			'single_agency_listing_count',
			[
				'default'           => $this->defaults['single_agency_listing_count'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'single_agency_listing_count',
				[
					'label'   => esc_html__( 'Listing Count Visibility', 'clproperty' ),
					'section' => 'agency_single_section',
				]
			) 
		);
		$wp_customize->add_setting(
			'single_agency_slogan',
			[
				'default'           => $this->defaults['single_agency_slogan'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'single_agency_slogan',
				[
					'label'   => esc_html__( 'Agency Slogan Visibility', 'clproperty' ),
					'section' => 'agency_single_section',
				]
			) 
		);
		$wp_customize->add_setting(
			'single_agency_slogan',
			[
				'default'           => $this->defaults['single_agency_slogan'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'single_agency_slogan',
				[
					'label'   => esc_html__( 'Agency Slogan Visibility', 'clproperty' ),
					'section' => 'agency_single_section',
				]
			) 
		);
		$wp_customize->add_setting(
			'store_owner_contact_form',
			[
				'default'           => $this->defaults['store_owner_contact_form'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'store_owner_contact_form',
				[
					'label'   => esc_html__( 'Store Owner Contact Form Visibility', 'clproperty' ),
					'section' => 'agency_single_section',
				]
			) 
		);
	}


}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Agency_Settings();
}
