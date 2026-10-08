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

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Blog_Archive_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_blog_archive_controls' ] );
	}

	/**
	 * Blog Archive Controls
	 */
	public function register_blog_archive_controls( $wp_customize ) {
		// Blog Style
		$wp_customize->add_setting( 'blog_style',
			[
				'default'           => $this->defaults['blog_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'blog_style',
			[
				'label'       => __( 'Blog Layout', 'clproperty' ),
				'description' => esc_html__( 'Select the blog style', 'clproperty' ),
				'section'     => 'blog_archive_section',
				'choices'     => [
					'style1' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/blog1.jpg',
						'name'  => __( 'List', 'clproperty' ),
					],
					'style2' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/blog2.jpg',
						'name'  => __( 'Grid', 'clproperty' ),
					],
				],
			]
		) );

		$wp_customize->add_setting( 'blog_date',
			[
				'default'           => $this->defaults['blog_date'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_date',
			[
				'label'   => __( 'Display Date', 'clproperty' ),
				'section' => 'blog_archive_section',
			]
		) );

		$wp_customize->add_setting( 'blog_author_name',
			[
				'default'           => $this->defaults['blog_author_name'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_author_name',
			[
				'label'   => __( 'Display Author Name', 'clproperty' ),
				'section' => 'blog_archive_section',
			]
		) );

		// Blog Cat Visibility
		$wp_customize->add_setting( 'blog_cat_visibility',
			[
				'default'           => $this->defaults['blog_cat_visibility'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_cat_visibility',
			[
				'label'   => __( 'Display Category', 'clproperty' ),
				'section' => 'blog_archive_section',
			]
		) );

		// Blog Reading Time
		$wp_customize->add_setting( 'blog_archive_reading_time',
			[
				'default'           => $this->defaults['blog_archive_reading_time'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_archive_reading_time',
			[
				'label'   => __( 'Display Reading Time', 'clproperty' ),
				'section' => 'blog_archive_section',
			]
		) );

		// Blog Comment Visibility
		$wp_customize->add_setting( 'blog_comment_num',
			[
				'default'           => $this->defaults['blog_comment_num'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_comment_num',
			[
				'label'   => __( 'Display Comment Count', 'clproperty' ),
				'section' => 'blog_archive_section',
			]
		) );

		$wp_customize->add_setting( 'excerpt_length',
			[
				'default'           => $this->defaults['excerpt_length'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_sanitize_integer',
			]
		);
		$wp_customize->add_control( 'excerpt_length',
			[
				'label'   => __( 'Excerpt Length', 'clproperty' ),
				'section' => 'blog_archive_section',
				'type'    => 'number',
			]
		);

		// Blog Comment Visibility
		$wp_customize->add_setting( 'blog_related_posts',
			[
				'default'           => $this->defaults['blog_related_posts'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'blog_related_posts',
			[
				'label'   => __( 'Pagination', 'clproperty' ),
				'section' => 'blog_archive_section',
			]
		) );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Blog_Archive_Settings();
}
