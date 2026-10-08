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

$callback404 = [ Helper::get_img( '404.png' ), 709, 287 ];
$options404 = RDTheme::$options['error_bodybanner'] ? wp_get_attachment_image_src(RDTheme::$options['error_bodybanner'], 'full') : null;

$rdtheme_error_img = !empty($options404) ? $options404 : $callback404;
?>
<?php get_header();?>
<div id="primary" class="content-area erorr-page">
	<div class="container motion-effects-wrap">
	<img width="412" height="412" src="<?php echo esc_url( Helper::get_img('404-layer.png') ) ?>" alt="<?php esc_attr_e('Animated Image', 'clproperty') ?>" data-position="<?php echo esc_attr('120') ?>" class="follow-with-mouse animate-image img1">
	<img width="412" height="412" src="<?php echo esc_url( Helper::get_img('404-layer.png') ) ?>" alt="<?php esc_attr_e('Animated Image', 'clproperty') ?>" data-position="<?php echo esc_attr('-150') ?>" class="follow-with-mouse animate-image img2">
	<img width="412" height="412" src="<?php echo esc_url( Helper::get_img('404-layer.png') ) ?>" alt="<?php esc_attr_e('Animated Image', 'clproperty') ?>" data-position="<?php echo esc_attr('-100') ?>" class="follow-with-mouse animate-image img3">

		<div class="erorr-box">
            <div class="error-img">
                <img 
				src="<?php echo esc_url( $rdtheme_error_img[0] );?>" 
				width="<?php echo esc_attr( $rdtheme_error_img[1] );?>"
				height="<?php echo esc_attr( $rdtheme_error_img[2] );?>"
				alt="<?php esc_attr_e( '404', 'clproperty' );?>" 
				data-position="50" 
				class="follow-with-mouse image-404">
            </div>
			<h2 class="item-title">
				<?php
					echo wp_kses( RDTheme::$options['error_text'], array(
						'a' => array(
							'href' => array(),
							'title' => array()
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
					) );
				?>
			 </h2>
			<div class="item-subtitle">
				<?php
					echo wp_kses( RDTheme::$options['error_subtitle'], array(
						'br' => array(),
					) );
				?>
			</div>
			<a class="item-btn rt-animation-btn" href="<?php echo esc_url( home_url( '/' ) );?>"><?php echo esc_html( RDTheme::$options['error_buttontext'] ); ?></a>
		</div>
	</div>
</div>
<?php get_footer();