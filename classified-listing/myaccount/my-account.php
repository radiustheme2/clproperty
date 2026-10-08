<?php
/**
 *
 * @author 		RadiusTheme
 * @package 	classified-listing/templates
 * @version     1.0.0
 */

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\RDTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$main_logo         = ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo'], 'full' ) : '';
$light_logo        = ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo_light'], 'full' )
	:'';
?>
<div class="rtcl-MyAccount-mobile-navbar">
    <div class="rtcl-myaccount-logo">
        <?php 
            if(!empty($light_logo)){
                ?>
                    <a class="custom-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <img
                            class="img-fluid" src="<?php echo esc_url( $light_logo[0] ); ?>"
                            width="<?php echo esc_attr( $light_logo[1] ); ?>"
                            height="<?php echo esc_attr( $light_logo[2] ); ?>"
                            alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                        >
                    </a>
            <?php 
            } else if( !empty($main_logo) ){
                ?>
                <a class="custom-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img
                        class="img-fluid" src="<?php echo esc_url( $main_logo[0] ); ?>"
                        width="<?php echo esc_attr( $main_logo[1] ); ?>"
                        height="<?php echo esc_attr( $main_logo[2] ); ?>"
                        alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                    >
                </a>
            <?php 
            } else{
                ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'clproperty' ); ?>" rel="home">
                        <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                    </a>
                </h1>
                <?php 
            }
        ?>
    </div>
	<div class="rtcl-MyAccount-open-menu"><span></span></div>
</div>
<div class="rtcl-MyAccount-wrap">
	<?php do_action('rtcl_account_navigation'); ?>

    <div class="rtcl-MyAccount-content">
		<?php Functions::print_notices(); ?>
		<?php do_action('rtcl_account_content'); ?>
    </div>
</div>
