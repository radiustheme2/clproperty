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

$site_name          = get_bloginfo( 'name' );
$custom_logo_id     = get_theme_mod( 'custom_logo' );



$mobile_logo        = ( isset( RDTheme::$options['mobile_logo'] ) && 0 != RDTheme::$options['mobile_logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['mobile_logo'],
	'full' )
	: '';

?>

<div class="rt-header-menu mean-container mobile-offscreen-menu" id="meanmenu">
    <div id="mobile-sticky-placeholder"></div>
    <div class="mobile-mene-bar" id="mobile-men-bar">
        <div class="mean-bar">
            <div class="mobile-logo <?php echo esc_attr( ! empty( $mobile_logo ) ? 'has-mobile-logo' : '' ) ?>">
                <?php if ( ! empty( $mobile_logo ) ) : ?>
                    <a class="custom-logo site-mobile-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img class="img-fluid" src="<?php echo esc_url( $mobile_logo[0] ); ?>" width="<?php echo esc_attr( $mobile_logo[1] ); ?>"
                            height="<?php echo esc_attr( $mobile_logo[2] ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
                    </a>
                <?php else: ?>
                    <h1 class="mb-0"><?php echo esc_html( $site_name ); ?></h1>
                <?php endif;?>
            </div>

            <?php get_template_part( 'template-parts/header/listing', 'area' ); ?>

        </div>
        <div class="rt-slide-nav">
            <div class="offscreen-navigation">
                <?php wp_nav_menu(
                    [
                        'theme_location' => 'primary',
                        'container'      => 'nav',
                    ]
                ); ?>
            </div>
        </div>
    </div>
</div>
