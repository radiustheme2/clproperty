<?php
/**
 * @var array[] $images
 * @var array[] $videos
 * @var string  $video_url
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\RDTheme;

global $listing;
$opt_layout  = ! empty( RDTheme::$options['single_listing_style'] ) ? RDTheme::$options['single_listing_style'] : '1';
$meta_layout = get_post_meta( $listing->get_id(), 'listing_layout', true );
if ( ! $meta_layout || 'default' == $meta_layout ) {
	$style = $opt_layout;
} else {
	$style = $meta_layout;
}


$thumbSize      = $style == '2' ? 'full' : 'rtcl-gallery';

$total_gallery_image  = count( $images );
$total_gallery_videos = count( $videos );
$total_gallery_item   = $total_gallery_image + $total_gallery_videos;

if ( $total_gallery_item ) :
	$owl_class = $total_gallery_item > 1 && Functions::is_gallery_slider_enabled() ? " owl-carousel" : '';
	?>
    <div id="rtcl-slider-wrapper" class="rtcl-slider-wrapper">
		<?php
		if ( '1' == $style ) {
			?>
            <div class="listing-gallery">
				<?php
				if ( $total_gallery_image ) {
					foreach ( $images as $index => $image ) :
						$image_attributes = wp_get_attachment_image_src( $image->ID, $thumbSize );
						$image_full = wp_get_attachment_image_src( $image->ID, 'large' );
						?>
                        <div class="rtcl-img-item blocks-gallery-item">
                            <a href="<?php echo esc_url( $image_full[0] ); ?>">
                                <img src="<?php echo esc_url( $image_attributes[0] ); ?>"
                                     alt="<?php esc_attr( $listing->the_title() ); ?>"
                                     data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                     class="rtcl-responsive-img"/>
                            </a>
                        </div>
					<?php endforeach;
				}
				?>
            </div>
			<?php
		}
		?>
        <!-- Slider -->
        <div class="rtcl-slider">
            <div class="swiper-wrapper">
				<?php
				if ( $total_gallery_videos ) {
					foreach ( $videos as $index => $video_url ) { ?>
                        <div class="swiper-slide rtcl-slider-item ">
                            <iframe class="rtcl-lightbox-iframe"
                                    src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ) ?>"
                                    style="width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000">
                            </iframe>
                        </div>
						<?php
					}
				}
				if ( $total_gallery_image ) {
					foreach ( $images as $index => $image ) :
						$image_attributes = wp_get_attachment_image_src( $image->ID, $thumbSize );
						$image_full = wp_get_attachment_image_src( $image->ID, 'full' );
						?>
                        <div class="swiper-slide rtcl-slider-item">
                            <img src="<?php echo esc_html( $image_attributes[0] ); ?>"
                                 data-src="<?php echo esc_attr( $image_full[0] ) ?>"
                                 data-large_image="<?php echo esc_attr( $image_full[0] ) ?>"
                                 data-large_image_width="<?php echo esc_attr( $image_full[1] ) ?>"
                                 data-large_image_height="<?php echo esc_attr( $image_full[2] ) ?>"
                                 alt="<?php echo esc_attr( get_the_title( $image->ID ) ); ?>"
                                 data-caption="<?php echo esc_attr( wp_get_attachment_caption( $image->ID ) ); ?>"
                                 class="rtcl-responsive-img"/>
                        </div>
					<?php endforeach;
				}

				?>
            </div>
            <?php if(RDTheme::$options['show_listing_slider_nav']){ ?>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            <?php } ?>
        </div>

        <!--Default Slider -->

		<?php if ( $total_gallery_item > 1 ): ?>
            <!-- Slider nav -->
            <div class="rtcl-slider-nav">
                <div class="swiper-wrapper">
					<?php
					if ( $total_gallery_videos ) {
						foreach ( $videos as $index => $video_url ) { ?>
                            <div class="swiper-slide rtcl-slider-thumb-item rtcl-slider-video-thumb">
                                <img src="<?php echo esc_url( Functions::get_embed_video_thumbnail_url( $video_url ) ) ?>"
                                     class="rtcl-gallery-thumbnail" alt=""/>
                            </div>
							<?php
						}
					}
					if ( $total_gallery_image ) {
						foreach ( $images as $index => $image ) : ?>
                            <div class="swiper-slide rtcl-slider-thumb-item">
								<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery-thumbnail' ); ?>
                            </div>
						<?php endforeach;
					} ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
		<?php endif; ?>


    </div>
<?php endif;