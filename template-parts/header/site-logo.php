<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\RDTheme;

$custom_logo_id    = get_theme_mod( 'custom_logo' );
$main_logo         = ( isset( RDTheme::$options['logo'] ) && 0 != RDTheme::$options['logo'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo'], 'full' ) : '';
$light_logo        = ( isset( RDTheme::$options['logo_light'] ) && 0 != RDTheme::$options['logo_light'] ) ? wp_get_attachment_image_src( RDTheme::$options['logo_light'], 'full' )
	:'';


if ( RDTheme::$has_tr_header ) {
	$logo = $light_logo;
} else {
	$logo = $main_logo;
}
?>

<div class="site-branding-wrap">
    <div class="site-branding">
		<?php if ( ! empty( $logo ) ): ?>
            <a class="custom-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img
                        class="img-fluid" src="<?php echo esc_url( $logo[0] ); ?>"
                        width="<?php echo esc_attr( $logo[1] ); ?>"
                        height="<?php echo esc_attr( $logo[2] ); ?>"
                        alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                >
            </a>
		<?php else: ?>
            <h1 class="site-title">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'clproperty' ); ?>" rel="home">
					<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                </a>
            </h1>
		<?php endif; ?>
    </div>
</div>
