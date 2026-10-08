<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Customizer Default Data
if ( ! function_exists( 'rttheme_generate_defaults' ) ) {
	function rttheme_generate_defaults() {
		$customizer_defaults = [

			// General
			'logo'                         => '',
			'logo_light'                   => '',
			'mobile_logo1'                 => '',
			'mobile_logo'                  => '',
			'preloader'                    => '',
			'preloader_image'              => '',
			'magnific_popup'               => 0,
			'banner_image'                 => '',
			'back_to_top'                  => 0,
			'remove_admin_bar'             => 0,
			'sticky_sidebar'               => 0,

			// Header
			'top_bar'                      => 0,
			'tr_header'                    => 0,
			'sticky_header'                => 1,
			'header_btn'                   => 0,
			'header_btn_txt'               => 'Add Listing',
			'header_btn_url'               => '#',
			'breadcrumb'                   => 1,
			'header_login_icon'            => 1,
			'header_fav_icon'              => 1,
			'header_compare_icon'          => 2,
			'header_cart_icon'             => 0,
			'header_search_icon'           => 0,
			'header_mobile_topbar'         => 0,
			'header_style'                 => '1',
			'header_width'                 => 'box-width',
			'header_transparent_color'     => 'rgba(0, 0, 0, .56)',
			'main_logo_width_height'       => '',
			'location_text'				   =>'',
			'phone_text'				   =>'Hotline:',
			'social_text'				   =>'Follow Us On:',

			// Color
			'primary_color'                => '#0179e8',
			'primary_dark'                 => '#00a376',
			'secondary_color'              => '#01519c',
			'gradient_dark_color'		   => '#0a7ee9',
			'gradient_light_color'		   => '#01a0ea',
			'body_color'                   => '#666666',
			'page_color'                   => '#f8f8f8',
			'menu_color'                   => '',
			'topbar_bg'					   =>'',
			'topbar_text_color'			   =>'',
			'topbar_icon_color'			   =>'',
			'sub_menu_color'               => '',
			'menu_hover_color'             => '',
			'transparent_menu_color'       => '',
			'transparent_menu_color_hover' => '',
			'menu_icon_color'              => '',
			'menu_icon_tr_color'           => '',

			'breadcrumb_bg1'                     => '',
			'breadcrumb_color'                   => '#9f9f9f',
			'breadcrumb_title_color'             => '',
			'breadcrumb_active_color'            => '',

			//Footer
			'footer_style'                       => '1',
			'footer_bg'                          => '',
			'footer_text_color'                  => '',
			'footer_text_hover'                  => '',
			'copyright_bg'                       => '',
			'copyright_text_color'               => '',
			'footer_title_color'                 => '',
			'footer_border'                      => 1,
            'footer_link_color'				     =>'',
            'footer_link_hover_color'			 =>'',

			// Page Layout
			'page_layout'                        => 'right-sidebar',
			'page_sidebar'                       => 'sidebar',
			'page_padding_top'                   => '',
			'page_padding_bottom'                => '',
			'page_breadcrumb'                    => 'default',
			'page_footer_style'                  => 'default',
			'page_header_style'                  => 'default',
			'page_header_width'                  => 'default',
			'page_tr_header'                     => 'default',
			'page_top_bar'                       => 'default',

			// Error Layout
			'error_padding_top'                  => '',
			'error_padding_bottom'               => '',
			'error_breadcrumb'                   => 'default',
			'error_top_bar'                      => 'default',
			'error_header_style'                 => 'default',
			'error_header_width'                 => 'default',
			'error_tr_header'                    => 'default',
			'error_footer_style'                 => 'default',

			// Blog Layout
			'blog_layout'                        => 'right-sidebar',
			'blog_sidebar'                       => 'sidebar',
			'blog_padding_top'                   => '',
			'blog_padding_bottom'                => '90px',
			'blog_breadcrumb'                    => 'default',
			'blog_top_bar'                       => 'default',
			'blog_header_style'                  => 'default',
			'blog_header_width'                  => 'default',
			'blog_tr_header'                     => 'default',
			'blog_footer_style'                  => 'default',

			// Single Post Layout
			'single_post_layout'                 => 'right-sidebar',
			'single_post_sidebar'                => 'sidebar',
			'single_post_top_bar'                => 'default',
			'single_post_header_style'           => 'default',
			'single_post_header_width'           => 'default',
			'single_post_tr_header'              => 'default',
			'single_post_padding_top'            => '',
			'single_post_padding_bottom'         => '',
			'single_post_breadcrumb'             => 'default',
			'single_post_footer_style'           => 'default',
			

			// Agent Archive Layout
			'agent_archive_layout'               => 'left-sidebar',
			'agent_archive_sidebar'              => 'agent-sidebar',
			'agent_archive_breadcrumb'           => 'default',
			'agent_archive_top_bar'              => 'default',
			'agent_archive_header_style'         => 'default',
			'agent_archive_header_width'         => 'default',
			'agent_archive_footer_style'         => 'default',
			'agent_archive_tr_header'            => 'default',
			'agent_archive_padding_top'          => '',
			'agent_archive_padding_bottom'       => '',


			// Agent Single Layout
			'agent_single_layout'                => 'left-sidebar',
			'agent_single_sidebar'               => 'agent-sidebar',
			'agent_single_breadcrumb'            => 'default',
			'agent_single_footer_style'          => 'default',
			'agent_single_header_style'          => 'default',
			'agent_single_header_width'          => 'default',
			'agent_single_top_bar'               => 'default',
			'agent_single_tr_header'             => 'default',
			'agent_single_padding_top'           => '',
			'agent_single_padding_bottom'        => '',

			// Listing Archive Layout
			'listing_archive_layout'             => 'left-sidebar',
			'listing_archive_sidebar'            => 'listing-archive-sidebar',
			'listing_archive_padding_top'        => '',
			'listing_archive_padding_bottom'     => '90px',
			'listing_archive_breadcrumb'         => 'default',
			'listing_archive_top_bar'            => 'default',
			'listing_archive_header_style'       => 'default',
			'listing_archive_header_width'       => 'default',
			'listing_archive_tr_header'          => 'default',
			'listing_archive_footer_style'       => 'default',

			// Listing Single Layout
			'listing_single_layout'              => 'right-sidebar',
			'listing_single_padding_top'         => '90px',
			'listing_single_padding_bottom'      => '90px',
			'listing_single_breadcrumb'          => 'default',
			'listing_single_footer_style'        => 'default',
			'listing_single_header_style'        => 'default',
			'listing_single_header_width'        => 'default',
			'listing_single_tr_header'           => 'default',
			'listing_single_top_bar'             => 'default',

			// Store Archive Layout
			'store_archive_layout'               => 'right-sidebar',
			'store_archive_sidebar'              => 'store-sidebar',
			'store_archive_header_style'         => 'default',
			'store_archive_header_width'         => 'default',
			'store_archive_tr_header'            => 'default',
			'store_archive_top_bar'              => 'default',
			'store_archive_padding_top'          => '',
			'store_archive_padding_bottom'       => '',
			'store_archive_breadcrumb'           => 'default',
			'store_archive_footer_style'         => 'default',
			'store_single_padding_top'           => '',
			'store_single_padding_bottom'        => '',

			// Store Single Layout
			'store_single_layout'                => 'left-sidebar',
			'store_single_sidebar'               => 'store-sidebar',
			'store_single_breadcrumb'            => 'default',
			'store_single_footer_style'          => 'default',
			'store_single_header_style'          => 'default',
			'store_single_header_width'          => 'default',
			'store_single_top_bar'               => 'default',
			'store_single_tr_header'             => 'default',

			// Blog Archive
			'blog_style'                         => 'style1',
			'blog_date'                          => 1,
			'blog_cat_visibility'                => 1,
			'blog_archive_reading_time'          => 0,
			'blog_author_name'                   => 1,
			'blog_comment_num'                   => 1,
			'excerpt_length'                     => 41,
			'blog_related_posts'                 => 1,

			// Single Post
			'post_date'                          => 1,
			'post_author_name'                   => 1,
			'post_comment_num'                   => 1,
			'post_details_related_section'       => 0,
			'post_details_reading_time'          => 0,
			'post_author_about'                  => 0,
			'post_social_icon'                   => 0,
			'post_tag'                           => 1,
			'post_navigation'                    => 0,
			'post_cats'                          => 1,
			'social_facebook'                    => 1,
			'social_twitter'                     => 1,
			'social_linkedin'                    => 1,
			'social_pinterest'                   => 0,
			'social_tumblr'                      => 1,
			'social_reddit'                      => 0,
			'social_vk'                          => 0,

			// Listings Settings
			'single_listing_style'               => 1,
			'custom_group_individual'            => '55',//added custom group field id
			'listing_detail_sidebar'             => 1,
			'walkscore_control'                  => 1,
			'walkscore_title'                    => 'Walk Score',
			'walkscore_api_key'                  => '',
			'remove_listing_type_prefix'         => 0,
			'listing_type_prefix_text'           => '',
			'show_floating_menu'                 => 1,
			'show_listing_button_area'           => 1,
			'show_related_listing'               => 1,
			'show_listing_custom_fields'         => 1,
			'overview_show_hide'                 => 1,
			'listing_author_meta'          		 => 0,
			'listing_arexcerpt_limit'			 =>'15',
			'overview_text'                      => 'Overview',
			'details_show_hide'                  => 1,
			'details_text'                       => 'Details',
			'feature_aminities_show_hide'        => 1,
			'feature_text'                       => 'Features & Amenities',
			'show_user_info_on_details'          => 'show_owner_info',
			'show_owner_store_title'          	 => 0,
			'show_owner_store_whatsapp'          => 0,
			'show_owner_store_email'          	 => 0,
			'show_owner_store_rating'          	 => 0,
			'show_listing_slider_nav'          	 => 0,
			'listing_author_btn_text'          	 => 'Details',
			'listing_slider_autoplay'          	 => 'yes',
			'listing_slider_autoplay_delay'      => 2000,
			'listing_owner_widget_title'         => '',
			'listing_widget_min_price'           => '0',
			'listing_widget_max_price'           => '20000',
			'listing_map_per_page'               => '8',

			//agency

			'show_ad_count'						=>0,
			'show_agency_views'					=>0,
			'show_agency_ratings'				=>0,
			'show_agency_location'				=>1,
			'show_agency_excerpt'				=>1,
			'show_agency_phone'					=>1,
			'show_agency_mail'					=>1,
			'show_agency_webaddress'			=>0,
			'show_agency_social_share'			=>1,

			//agent

			'show_listing_count'				=>1,
			'show_agent_ratings'				=>1,
			'show_agent_phone'					=>1,
			'show_agent_mail'					=>0,
			'listing_agent_listing_per'			=>10,
			'agent_single_rating'			    =>1,
			'agent_single_listing_count'		=>0,
			'agent_single_listing_specialty'	=>0,
			'agent_services'					=>0,

			//agency

			'single_agency_listing_count'		=>0,
			'single_agency_slogan'				=>0,
			'store_owner_contact_form'			=>0,


			// Error
			'error_bodybanner'                   => '',
			'error_text'                         => 'ERROR PAGE',
			'error_subtitle'                     => 'Sorry! This Page is <br> Not Available!',
			'error_buttontext'                   => 'Go Back To Home Page',

			//Newsletter
			'newsletter_section'                 => '',
			'newsletter_title'					 =>'Get listed to our Daily',
			'newsletter_sub_title'				 =>'Latest News &amp; Offers',
			'newsletter_img'					 =>'',

			// Footer
			'copyright_area'                     => 1,
			'copyright_text'                     => gmdate( 'Y' ) . '© All right reserved by Radiustheme',
			'copyright_menu_color'               => '',

			// Contact Info
			'contact_address'                    => 'Middlest 2 East 42nd Streearketplace New York, NY 10017',
			'contact_phone'                      => '+123(55)-90067990',
			'contact_email'                      => 'info@example.com',
			'contact_website'                    => '',
			'facebook'                           => '#',
			'twitter'                            => '#',
			'instagram'                          => '#',
			'youtube'                            => '',
			'pinterest'                          => '',
			'linkedin'                           => '#',
			'skype'                              => '',

			// Body Typography
			'typo_body'                          => json_encode(
				[
					'font'          => 'Roboto',
					'regularweight' => 'normal',
				]
			),
			'typo_body_size'                     => '16px',
			'typo_body_height'                   => '30px',

			//Menu Typography
			'typo_menu'                          => json_encode(
				[
					'font'          => 'Poppins',
					'regularweight' => '500',
				]
			),
			'typo_menu_size'                     => '16px',
			'typo_menu_height'                   => '22px',

			//Sub Menu Typography
			'sub_menu_typo'                          => json_encode(
				[
					'font'          => 'Poppins',
					'regularweight' => '500',
				]
			),
			'typo_submenu_size'                  => '15px',
			'typo_submenu_height'                => '22px',

			// Heading Typography
			'typo_heading'                       => json_encode(
				[
					'font'          => 'Poppins',
					'regularweight' => '500',
				]
			),
			'typo_h1'                            => json_encode(
				[
					'font'          => '',
					'regularweight' => '600',
				]
			),
			'typo_h1_size'                       => '46px',
			'typo_h1_height'                     => '56px',

			'typo_h2'        => json_encode(
				[
					'font'          => '',
					'regularweight' => '600',

				]
			),
			'typo_h2_size'   => '36px',
			'typo_h2_height' => '46px',

			'typo_h3'        => json_encode(
				[
					'font'          => '',
					'regularweight' => '600',

				]
			),
			'typo_h3_size'   => '28px',
			'typo_h3_height' => '38px',

			'typo_h4'        => json_encode(
				[
					'font'          => '',
					'regularweight' => '600',

				]
			),
			'typo_h4_size'   => '22px',
			'typo_h4_height' => '32px',

			'typo_h5'        => json_encode(
				[
					'font'          => '',
					'regularweight' => '600',

				]
			),
			'typo_h5_size'   => '18px',
			'typo_h5_height' => '28px',

			'typo_h6'        => json_encode(
				[
					'font'          => '',
					'regularweight' => '600',

				]
			),
			'typo_h6_size'   => '14px',
			'typo_h6_height' => '26px',

		];

		return apply_filters( 'rttheme_customizer_defaults', $customizer_defaults );
	}
}