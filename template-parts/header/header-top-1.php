<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_top_info = RDTheme::$options['contact_address'] || RDTheme::$options['contact_phone'] || RDTheme::$options['contact_email'] || RDTheme::$options['contact_website'] ? true
	: false;
$socials      = Helper::socials();

if ( ! $has_top_info || ! $socials ) {
	return;
}
$header_container = 'container';
if ( 'fullwidth' == RDTheme::$header_width ) {
	$header_container = 'container-fluid';
}
$full_container=RDTheme::$header_width==='fullwidth' ? 'has-full-container':'no-full-container';
$location_text=RDTheme::$options['location_text'] ? RDTheme::$options['location_text']:'';
$phone_text=RDTheme::$options['phone_text'] ? RDTheme::$options['phone_text']:'';
$social_text=RDTheme::$options['social_text'] ? RDTheme::$options['social_text']:'';

$mobile_topbar_header=RDTheme::$options['header_mobile_topbar'] ? 'has-mobile-topbar':'hide-mobile-topbar';
?>

<div id="tophead" class="header-topbar <?php echo esc_attr( $full_container ); ?> <?php echo esc_attr($mobile_topbar_header); ?>">
    <div class="<?php echo esc_attr( $header_container ); ?>">
        <div class="topbar-content-wrapper">
			<?php if ( $has_top_info ): ?>
                <ul class="topbar-left">
                    <?php if ( RDTheme::$options['contact_address'] ): ?>
                        <li class="item-location"><i class="icon-rt-icon-location-line"></i><?php if($location_text) echo wp_kses_post($location_text); ?><span><?php echo esc_html( RDTheme::$options['contact_address'] ); ?></span></li>
                    <?php endif; ?>
                    <?php if ( RDTheme::$options['contact_phone'] ): ?>
                        <li class="item-phone"><i class="icon-rt-icon-phone-line2"></i><?php if($phone_text) echo wp_kses_post($phone_text); ?><span><?php echo esc_html( RDTheme::$options['contact_phone'] ); ?></span></li>
                    <?php endif; ?>
                </ul>
			<?php endif; ?>
			<?php if ( $socials ): ?>
                <ul class="topbar-right">
                    <?php if ( $socials ): ?>
                        <li class="social-icon">
                            <label><i class="icon-rt-icon-share-line"></i><?php if($social_text) echo wp_kses_post($social_text); ?></label>
                            <?php foreach ( $socials as $social ): ?>
                                <a target="_blank" href="<?php echo esc_url( $social['url'] ); ?>"><i class="<?php echo esc_attr( $social['icon'] ); ?>"></i></a>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>
                </ul>
			<?php endif; ?>
        </div>
    </div>
</div>