<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use radiustheme\ClProperty\Listing_Functions;
use Rtcl\Helpers\Functions;

global $listing;

$floorList = get_post_meta( $listing->get_id(), "clproperty_floor_plan", true );
$newfloorList = get_post_meta( $listing->get_id(), "floor_plan", true );

if (!empty($newfloorList)) {
	$floorplans = $newfloorList;
} else {
	$floorplans = $floorList;
}

$single_listing = Functions::get_option( 'rtcl_single_listing_settings' );
$text            = ! empty( $single_listing['floor_plan_section_label'] ) ? $single_listing['floor_plan_section_label'] : '';

?>

<?php if ( (! empty( $floorplans ) && !empty($floorplans['0']['title']))  && Listing_Functions::is_enable_floor_plan()) { ?>
	<div class="clproperty-accordion-item">
		<?php if($text){ ?>
			<div class="accordion-header" id="clproperty_listing_floor_plan_heading">
				<h3 class="mb-0">
					<button class="btn" data-toggle="collapse" data-target="#clproperty_listing_floor_plan" aria-expanded="true" aria-controls="clproperty_listing_floor_plan">
					<?php echo esc_html($text); ?>
					</button>
				</h3>
			</div>
		<?php } ?>
		<div id="clproperty_listing_floor_plan" class="collapse show" data-parent="#clproperty_listing_floor_plan">
			<div class="accordion" id="accordionExample">
				<?php
				$count = 0;
				?>
				<?php foreach ( $floorplans as $floor ):
					$count ++;

					$title   = $floor['title'] ?? '';
					$desc    = $floor['description'] ?? '';
					$bed     = ltrim( $floor['bed'], '0' );
					$bath    = ltrim( $floor['bath'], '0' );
					$size    = $floor['size'] ?? '';
					$parking = $floor['parking'] ?? '';
					if (!empty($newfloorList)) {
						$imgID   = $floor['floor_img'][0] ?? '';
					} else {
						$imgID   = $floor['floor_img'] ?? '';
					}

					if ( $count === 1 ) {
						$show      = 'show';
						$expand    = 'true';
						$collapsed = '';
					} else {
						$show      = '';
						$expand    = 'false';
						$collapsed = ' collapsed';
					}
					?>
					<div class="card">
						<div class="card-header<?php echo esc_attr( $collapsed ); ?>" data-toggle="collapse"
							data-target="#collapse<?php echo esc_attr( $count ); ?>"
							aria-expanded="<?php echo esc_attr( $expand ); ?>" role="tabpanel">
							<?php if ( ! empty( $title ) ): ?>
								<div class="floor-name"><?php echo esc_html( $title ); ?></div>
							<?php endif; ?>
							<ul class="entry-meta">
								<?php if ( ! empty( $bed ) ):

									$bed_label = ( $bed == 1 ) ? __("Bed","clproperty") : __("Beds","clproperty");

									if ( $bed < 10 ) {
										$bed = "0" . $bed;
									}
									?>
									<li class="d-none d-md-flex">
										<i class="icon-rt-icon-bed-line"></i>
										<span class='label'>
											<?php echo esc_html( $bed_label, 'clproperty' ); ?>
										</span>
										<span class='value'><?php echo esc_html( $bed ); ?></span>
									</li>
								<?php endif; ?>

								<?php if ( ! empty( $bath ) ):

									$bath_label = ( $bath == 1 ) ? __("Bath","clproperty") : __("Baths","clproperty");

									if ( $bath < 10 ) {
										$bath = "0" . $bath;
									}
									?>
									<li>
										<i class="icon-rt-icon-bath-line"></i>
										<?php
										printf( "<span class='label'>%s</span><span class='value'>%s</span>",
											esc_html($bath_label, 'clproperty' ),
											esc_html( $bath )
										);
										?>
									</li>
								<?php endif; ?>

								<?php if ( ! empty( $size ) ): 
									$size_label = esc_html__( "Size","clproperty" );
									?>
									<li>
										<i class="icon-rt-icon-bath-area"></i>
                                        <span class='label'>
											<?php echo esc_html( $size_label ); ?>
										</span>
										<?php echo esc_html( $size ); ?>
									</li>
								<?php endif; ?>

							</ul>
						</div>

						<div id="collapse<?php echo esc_attr( $count ); ?>"
							class="collapse <?php echo esc_attr( $show ); ?> tab-content" data-parent="#accordionExample">
							<div class="card-body">
								<?php if ( ! empty( $desc ) ): ?>
									<p><?php echo esc_html( $desc ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $imgID ) ): ?>
									<div class="floor-design blocks-gallery-item">
										<a href="<?php echo esc_url( wp_get_attachment_image_url( $imgID, 'full' ) ); ?>">
											<?php echo wp_get_attachment_image( $imgID, 'full' ); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php } ?>