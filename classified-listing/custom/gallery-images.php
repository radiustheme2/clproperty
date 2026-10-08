<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.5
 */

use Rtcl\Helpers\Functions;

global $listing;
$images = $listing->get_images();
$videos = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
$videos = ! empty( $videos ) && is_array( $videos ) ? $videos : [];

$total_gallery_videos = count( $videos );

$total_gallery_image  = '';
if ( ! empty( $images ) ) {
	$total_gallery_image = count( $images );
}


?>
<div class="listing-single-gallery">
	<div class="listing-gallery">
		<?php

		$show_video = isset( $show_video ) ? $show_video : 'yes';
		if ( $total_gallery_videos && 'yes' === $show_video ) {
			foreach ( $videos as $index => $video_url ) { ?>
                <div class="rtcl-img-item blocks-gallery-item">
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
				$image_attributes = wp_get_attachment_image_src( $image->ID, 'full' );
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
</div>
