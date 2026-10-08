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


// if ( ! defined( 'RT_DEBUG' ) || ! constant( 'RT_DEBUG' ) ) {
// 	Helper::requires( 'lib/updater/theme-updater.php' );
// 	Helper::requires( 'lib/updater/lc-utility.php' );
// 	Helper::requires( 'lib/updater/lc-helper.php' );
// }
Helper::requires( 'lib/class-tgm-plugin-activation.php' );

Helper::requires( 'class-clproperty-walker-category.php' );
Helper::requires( 'tgm-config.php' );
Helper::requires( 'general.php' );
Helper::requires( 'scripts.php' );
Helper::requires( 'layout-settings.php' );

// WooCommerce
if ( class_exists( 'WooCommerce' ) ) {
	Helper::requires( 'woo-functions.php' );
	Helper::requires( 'woo-hooks.php' );
}

if ( class_exists( 'Rtcl' ) ) {
	Helper::requires( 'custom/functions.php', 'classified-listing' );
	Helper::requires( 'custom/shortcode.php', 'classified-listing' );
	Helper::requires( 'clproperty-mortgage-calculator.php' );
}

// Add Customizer
Helper::requires( 'customizer/customizer-default-data.php' );
Helper::requires( 'customizer/init.php' );
Helper::requires( 'rdtheme.php' );
