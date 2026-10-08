<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.4.1
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

Helper::requires( 'dynamic-styles/common.php' );

$primary_color   = Helper::get_primary_color(); // #0179e8
$secondary_color = Helper::get_secondary_color(); // #01519c
$body_color      = Helper::get_body_color(); // #666666

$primary_rgb   = Helper::hex2rgb( $primary_color );
$secondary_rgb = Helper::hex2rgb( $secondary_color );



$menu_color                   = RDTheme::$options['menu_color'];
$transparent_menu_color       = RDTheme::$options['transparent_menu_color'];
$transparent_menu_color_hover = RDTheme::$options['transparent_menu_color_hover'];
$sub_menu_color               = RDTheme::$options['sub_menu_color'];
$menu_hover_color             = RDTheme::$options['menu_hover_color'];
$menu_icon_color              = RDTheme::$options['menu_icon_color'];
$menu_icon_tr_color           = RDTheme::$options['menu_icon_tr_color'];
$header_transparent_color     = RDTheme::$options['header_transparent_color'];
$breadcrumb_bg1               = RDTheme::$options['breadcrumb_bg1'];
$breadcrumb_color             = RDTheme::$options['breadcrumb_color'];
$breadcrumb_title_color       = RDTheme::$options['breadcrumb_title_color'];
$breadcrumb_active_color      = RDTheme::$options['breadcrumb_active_color'];
$footer_bg                    = RDTheme::$options['footer_bg'];
$footer_text                  = RDTheme::$options['footer_text_color'];
$copyright_bg                 = RDTheme::$options['copyright_bg'];
$copyright_text               = RDTheme::$options['copyright_text_color'];
$footer_title                 = RDTheme::$options['footer_title_color'];
$footer_link_color            = RDTheme::$options['footer_link_color'];
$footer_link_hover_color      = RDTheme::$options['footer_link_hover_color'];
$main_logo_width_height       = RDTheme::$options['main_logo_width_height'];
$gradient_dark_color          = RDTheme::$options['gradient_dark_color'];
$gradient_light_color         = RDTheme::$options['gradient_light_color'];
$topbar_bg_color              = RDTheme::$options['topbar_bg'];
$topbar_text_color            = RDTheme::$options['topbar_text_color'];
$topbar_icon_color            = RDTheme::$options['topbar_icon_color'];

$logo_max_width = $logo_max_height = '';
if ( $main_logo_width_height ) {
	[ $logo_max_width, $logo_max_height ] = explode( ',', $main_logo_width_height );
}

$menu_color              = $menu_color ? $menu_color : '';
$menu_hover_color        = $menu_hover_color ? $menu_hover_color : '';
$menu_icon_color         = $menu_icon_color ? $menu_icon_color : '';
$menu_icon_tr_color      = $menu_icon_tr_color ? $menu_icon_tr_color : '';
$topbar_bg_color         =$topbar_bg_color ? $topbar_bg_color:'';
$topbar_text_color       =$topbar_text_color ? $topbar_text_color:'';
$topbar_icon_color       =$topbar_icon_color ? $topbar_icon_color:'';
$breadcrumb_active_color = $breadcrumb_active_color ? $breadcrumb_active_color : $primary_color;

?>

<?php
/*-------------------------------------
#. Defaults
---------------------------------------*/
?>

:root {
    --rt-primary-color: <?php echo esc_html( $primary_color ? $primary_color : '#0179e8' ); ?>;
    --rt-secondary-color: <?php echo esc_html( $secondary_color ? $secondary_color : '#01519c' ); ?>;
    --rt-primary-rgb: <?php echo esc_html( $primary_rgb ? $primary_rgb : '1, 121, 232' ); ?>;
    --rt-secondary-rgb: <?php echo esc_html( $secondary_rgb ? $secondary_rgb : '1, 81, 156' ); ?>;
}

.elementor-kit-2673 {
    --e-global-color-primary: <?php echo esc_html( $primary_color ? $primary_color : '#0179e8' ); ?>;
    --e-global-color-secondary: <?php echo esc_html( $secondary_color ? $secondary_color : '#01519c' ); ?>;
    --e-global-color-accent: <?php echo esc_html( $primary_color ? $primary_color : '#0179e8' ); ?>;
}

body {
    color: <?php echo esc_html( $body_color ); ?>;
}

a:active, .rtcl a:hover, a:hover, a:focus {
    color: <?php echo esc_html( $secondary_color ); ?>;
}

<?php if ( $logo_max_width || $logo_max_height ) : ?>
    .header-menu .header-content .logo-area img {
        <?php echo esc_html($logo_max_width ? esc_attr( "max-width:" . trim( $logo_max_width ) ) . ";" : ""); ?>
	    <?php echo esc_html($logo_max_height ? esc_attr( "max-height:" . trim( $logo_max_height ) ) . ";" : ""); ?>
    }
<?php endif; ?>

<?php
/*-------------------------------------
#. Header
---------------------------------------*/
?>

<?php if($topbar_bg_color){ ?>
    .header-area .header-topbar {
        background:<?php echo esc_html( $topbar_bg_color ); ?>;
    }
<?php } ?>

<?php if($topbar_text_color){ ?>
    .topbar-content-wrapper ul li {
        color:<?php echo esc_html( $topbar_text_color ); ?>;
    }
<?php } ?>

<?php if($topbar_icon_color){ ?>
    .topbar-content-wrapper .topbar-left li i,
    .topbar-content-wrapper .topbar-right li label i,
    .topbar-content-wrapper .topbar-right li a i {
        color:<?php echo esc_html( $topbar_icon_color ); ?>;
    }
<?php } ?>

<?php if($menu_color){ ?>
    .site-header .main-navigation nav > ul > li > a {
        color: <?php echo esc_html( $menu_color ); ?>;
    }
<?php } ?>
<?php if ( $transparent_menu_color ) : ?>
    .trheader .site-header .main-navigation nav > ul > li > a {
    color: <?php echo esc_html( $transparent_menu_color ); ?>;
    }
   
<?php endif; ?>

<?php if ( $transparent_menu_color_hover ) : ?>
    .trheader .site-header .main-navigation nav > ul > li > a:hover {
        color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    }
    .trheader .site-header .main-navigation nav > ul > li.current_page_item > a,
    .trheader .site-header .main-navigation nav > ul > li.current-menu-ancestor > a,
    .trheader .site-header .main-navigation nav > ul > li.current-menu-item > a,
    .trheader .site-header .main-navigation nav > ul > li.current-menu-ancestor > a:hover {
        color: <?php echo esc_html( $transparent_menu_color_hover ); ?>;
    }
<?php endif; ?>
<?php if($sub_menu_color){ ?>
    .site-header .main-navigation ul li ul li a {
        color: <?php echo esc_html( $sub_menu_color ); ?>;
    }
<?php } ?>
<?php if($menu_hover_color){ ?>
    .site-header .main-navigation ul.menu li ul.sub-menu li a:hover,
    .site-header .main-navigation nav > ul > li.current-menu-item > a,
    .site-header .main-navigation nav > ul > li > a:hover {
        color: <?php echo esc_html( $menu_hover_color ); ?>;
    }
    .site-header .main-navigation ul.menu li ul.sub-menu li a:after{
        background:<?php echo esc_html( $menu_hover_color ); ?>;
    }
<?php } ?>
<?php if($menu_icon_color){ ?>
    .listing-area ul li .listing-btn {
        color: <?php echo esc_html( $menu_icon_color ); ?>;
        border-color:<?php echo esc_html( $menu_icon_color ); ?>;
    }
<?php } ?>
<?php if($menu_icon_tr_color){ ?>
    .trheader .listing-area ul li .listing-btn {
        color: <?php echo esc_html( $menu_icon_tr_color ); ?>;
        border-color:<?php echo esc_html( $menu_icon_tr_color ); ?>;
    }
<?php } ?>



<?php
/*-------------------------------------
#. Breadcrumb
---------------------------------------*/
?>
<?php if ( $breadcrumb_bg1 ) : ?>
    .breadcrumbs-banner {
        background-color: <?php echo esc_html( $breadcrumb_bg1 ); ?>;
    }
<?php endif; ?>
.breadcrumbs-banner .rtcl-breadcrumb {
    color: <?php echo esc_html( $breadcrumb_color ); ?>;
}
.breadcrumbs-banner .rtcl-breadcrumb a:hover,
.breadcrumbs-banner .rtcl-breadcrumb span {
    color: <?php echo esc_html( $breadcrumb_active_color ); ?>;
}
<?php if ( $breadcrumb_title_color ) : ?>
    .breadcrumbs-banner h2{
        color:<?php echo esc_html( $breadcrumb_title_color ); ?>;
    }
<?php endif; ?>    
<?php
/*-------------------------------------
#. Footer
---------------------------------------*/
?>
<?php if ( $footer_bg ) : ?>
    .footer-wrap {
       background-color: <?php echo esc_html( $footer_bg ); ?>;
    }
<?php endif; ?>

<?php if ( $footer_text ) : ?>
    .footer-box p{
        color: <?php echo esc_html( $footer_text ); ?>;
    }
<?php endif; ?>

<?php if ( $footer_title ) : ?>
    .footer-box .footer-title{
        color:<?php echo esc_html($footer_title ); ?>;
    }
<?php endif; ?>

<?php if ( $copyright_bg ) : ?>
    .footer-style-1 .footer-bottom{
        background:<?php echo esc_html($copyright_bg); ?>;
    }
<?php endif; ?>
<?php if ( $copyright_text ) : ?>
    .footer-bottom .footer-copyright{
        color:<?php echo esc_html($copyright_text ); ?>;
    }
<?php endif; ?>

<?php if ( $footer_link_color ) : ?>
    .footer-box.widget_recent_comments ul li, 
    .footer-box.widget_meta ul li a, 
    .footer-box.widget_pages ul li a, 
    .footer-box.widget_categories ul li a, 
    .footer-box.widget_archive ul li a,
    .site-footer .footer-box .rt-contact-wrapper ul li a,
    .site-footer .footer-box .rt-contact-wrapper ul li:nth-child(2) a,  
    .footer-box.widget_nav_menu ul li a{
        color:<?php echo esc_html($footer_link_color); ?>;
    }
<?php endif; ?>

<?php if ( $footer_link_hover_color ) : ?>
    .footer-box.widget_recent_comments ul li:hover, 
    .footer-box.widget_meta ul li a:hover, 
    .footer-box.widget_pages ul li a:hover,
    .footer-box.widget_categories ul li a:hover, 
    .footer-box.widget_archive ul li a:hover,
    .site-footer .footer-box .rt-contact-wrapper ul li a:hover,
    .site-footer .footer-box .rt-contact-wrapper ul li:nth-child(2) a:hover, 
    .footer-box.widget_nav_menu ul li a:hover{
        color:<?php echo esc_html( $footer_link_hover_color); ?>;
    }
<?php endif; ?>

<?php if ( $footer_link_hover_color ) : ?>
    .footer-box.widget_recent_comments ul li:hover::before, 
    .footer-box.widget_meta ul li a:hover::before, 
    .footer-box.widget_pages ul li a:hover::before, 
    .footer-box.widget_categories ul li a:hover::before,
    .footer-box.widget_archive ul li a:hover::before, 
    .footer-box.widget_nav_menu ul li a:hover::before {
        background-color:<?php echo esc_html( $footer_link_hover_color); ?>;
    }
<?php endif; ?>
.footer-box .footer-title:after,
.rtcl-agents-single-main .agent-listing-title:after{
    background: linear-gradient(90deg, <?php echo esc_html($primary_color); ?> 11.54%, rgba(1, 160, 234, 0) 100%);
}

<?php 
//others
?>
.contact-agent-block {
    background: linear-gradient(265.1deg, <?php echo esc_html( $gradient_light_color ); ?> 6.67%, <?php echo esc_html( $gradient_dark_color ); ?> 92.25%);
}
