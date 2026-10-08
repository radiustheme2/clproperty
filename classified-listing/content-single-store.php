<?php
/**
 * Store single content
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.3.21
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;
use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\RDTheme;
use radiustheme\ClProperty\Listing_Functions;

global $store;

if ( StoreFunctions::is_store_expired() ) {
	do_action( 'rtcl_single_store_expired_content' );

	return;
}

$store_manager_user_ids=$store->get_manager_ids();
$store_manager_user_ids=$store_manager_user_ids ? $store_manager_user_ids:array();
$store_single_layout = RDTheme::$layout;

$content_column = "col-lg-9 col-sm-12 col-12";
if ( 'full-width' == $store_single_layout ) {
	$content_column = "col-12";
}
 
do_action( 'rtcl_before_single_store' );
?>
<div class="single-store-top-area">
	<?php do_action( 'rtcl_before_single_store_content' ); ?>
	<div class="single-store-banner-content">
		<?php if ( $store->get_logo_url() ): ?>
			<div class="single-store-logo">
				<?php $store->the_logo(); ?>
				<?php if(RDTheme::$options['single_agency_listing_count']){ ?>
					<?php $store->the_metas(); ?>
				<?php } ?>
			</div>
		<?php endif; ?>
		<div class="single-store-content">
			<!-- Store Title -->
			<div class="single-store-heading">
				<div class="header-left">
					<h2 class="single-title"><?php $store->the_title(); ?></h2>
					<?php if ( $store_address = $store->get_address() ): ?>
						<div class="store-location"><i class="icon-rt-icon-location-solid" aria-hidden="true"></i><?php echo esc_html( $store_address ); ?></div>
					<?php endif; ?>
				</div>
				<div class="header-right">
					<button class="store-share" data-toggle="modal" data-target="#storeSocialShare">
						<i class="icon-rt-icon-share-line"></i><?php echo esc_html('Share','clproperty'); ?>
					</button>
					<div class="modal fade" id="storeSocialShare" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?php esc_html_e( 'Social Share', 'clproperty' ); ?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="share-icon">
									<?php \ClProperty_Core::social_share( Helper::post_share_on_social() ); ?>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ( $store->get_category() ) : ?>
				<h4 class="single-category"><?php echo esc_html($store->get_category()); ?></h4>
			<?php endif; ?>
			<?php if ( $store->get_the_slogan() && RDTheme::$options['single_agency_slogan']): ?>
				<div class="single-slogan"><?php $store->the_slogan(); ?></div>
			<?php endif; ?>
			<?php if($store->get_the_description()) ?>
				<div class="content"><?php $store->the_description(); ?></div>
			<?php ?>
			<!-- Store Schedule -->
			<?php Helper::get_custom_store_template( 'store-info', true, get_defined_vars() ); ?>
		</div>
	</div>
</div>
<div class="single-store-bottom-area <?php echo esc_attr($store_single_layout); ?>">
	<div class="row store-main-row">
		<div class="<?php echo esc_attr($content_column); ?>">
			<?php Functions::get_template( 'store/ad-listing' ); ?>
		</div>
		<?php if('full-width' !==$store_single_layout){ ?>
			<div id="sticky_sidebar" class="<?php Helper::the_sidebar_class(); ?>">
				<aside class="sidebar-widget main-sidebar-wrapper">
					<?php 
					/**Store Agent  */
					?>
					<?php 
						if(is_array($store_manager_user_ids) && $store_manager_user_ids !='' && !empty($store_manager_user_ids)){ ?>
							<div class="single-store-agent-wrapper widget">
									<h3 class="widget-heading"><?php esc_html_e( 'Our Reliable Agents', 'clproperty' ); ?></h3>
									<div class="single-store-agent">
									<?php foreach($store_manager_user_ids as $store_manager_user_id){
										$user=get_user_by( 'id', $store_manager_user_id );
										if($user){
											$agent_id 	  	  		  = get_user_meta( $store_manager_user_id, '_rtcl_agent_id', true );
											$agent_first_name 	  	  = get_user_meta( $store_manager_user_id, 'first_name', true );
											$agent_last_name 	  	  = get_user_meta( $store_manager_user_id, 'last_name', true );
											$agent_name     = trim((string) implode( ' ', [ $agent_first_name, $agent_last_name  ] ));
											$pp_id    	      = absint( get_user_meta( $store_manager_user_id, '_rtcl_pp_id', true ) );
											$phone   	      =  get_user_meta( $store_manager_user_id, '_rtcl_phone', true ) ;
											$whatsapp_number   	      =  get_user_meta( $store_manager_user_id, '_rtcl_whatsapp_number', true ) ;
											$services      = get_post_meta( $agent_id, 'rtcl_agent_services', true );
											$agent_permalink= get_post_permalink($agent_id);
											?>
												<div class="reliable-block">
													<div class="reliable-block__figure">
													<?php echo wp_kses_post($pp_id ? wp_get_attachment_image( $pp_id,
														[
															100,
															100,
														] ) : get_avatar( $agent_id, 100 )); 
													?>
													</div>
													<div class="reliable-block__content">
														<h4 class="reliable-block__heading"><a href="<?php echo esc_url($agent_permalink); ?>"><?php echo esc_html($agent_name); ?></a></h4>
														<span class="reliable-block__designation"><?php echo esc_html($services); ?></span>
														<p class="reliable-block__contact"><i class=" icon-rt-icon-phone-line2"></i><a href="tel:<?php echo esc_attr($phone ? $phone: $whatsapp_number); ?>"><?php echo esc_html($phone ? $phone: $whatsapp_number);  ?></a>
														</p>
													</div>
												</div>
											
										<?php } ?>
										
									<?php  } ?>
									</div>
							</div>
					<?php	}
					?>
					<?php if ( $store_email = $store->get_email() && RDTheme::$options['store_owner_contact_form']) : ?>
						<div class="store-form-wrapper widget">
							<h3 class="widget-heading"><?php esc_html_e( 'Message Store Owner', 'clproperty' ); ?></h3>
							<?php Functions::get_template( 'store/contact-form' ); ?>
						</div>
					<?php endif; ?>
					<?php
					if ( RDTheme::$sidebar && is_active_sidebar( RDTheme::$sidebar ) ) {
						dynamic_sidebar( RDTheme::$sidebar );
					} elseif ( is_active_sidebar( 'sidebar' ) ) {
						dynamic_sidebar( 'sidebar' );
					}
					?>
				</aside>
			</div>
		<?php } ?>
	</div>
</div>
<?php

do_action( 'rtcl_after_single_store' );