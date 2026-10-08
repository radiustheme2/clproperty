<?php
/**
 * Newsletter section
 */
namespace radiustheme\ClProperty;

if (!defined('ABSPATH')) {
    exit;
}
$newsletter_bg_path = CLPROPERTY_ASSETS_URL . 'img/newsletter-bg.png';
$newsletter_bg_img='url(' . $newsletter_bg_path . ')';

$newsletter_building_id=RDTheme::$options['newsletter_img'];

?>

<div class="rt-newsletter-wrapper" data-bg-image="<?php echo esc_url( CLPROPERTY_ASSETS_URL . 'img/newsletter-bg.png' ); ?>" style="background-image:<?php echo esc_html( $newsletter_bg_img ); ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 col-12">
                <div class="newsletter-thumb">
                    <?php if($newsletter_building_id){
                        echo wp_get_attachment_image( $newsletter_building_id, 'full' );
                    } else {?>
                        <img src="<?php echo esc_url( CLPROPERTY_ASSETS_URL . 'img/newsletter-building.png' ); ?>" alt="<?php echo esc_attr('newsletter-image', 'clproperty'); ?>">
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <div class="clproperty-newsletter">
                    <h2 class="newsletter-block__heading"><?php echo wp_kses_post(RDTheme::$options['newsletter_title']); ?></h2>
                    <h4 class="newsletter-block__title"><?php echo wp_kses_post(RDTheme::$options['newsletter_sub_title']); ?></h4>
                </div>
                <?php echo do_shortcode("[mc4wp_form]") ?>
            </div>
        </div>
    </div>
</div>