<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;
use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\RDTheme;

$user_id = get_post_meta( get_the_ID(), '_rtcl_user_id', true );
if ( ! $user = get_user_by( 'id', $user_id ) ) {
	return;
}

$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
$whatsApp = get_user_meta( $user_id, '_rtcl_whatsapp_number', true );
$website  = get_user_meta( $user_id, '_rtcl_website', true );
$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store    = new Store( $store_id );

$services    = get_post_meta( get_the_ID(), 'rtcl_agent_services', true );
$specialties = get_post_meta( get_the_ID(), 'rtcl_agent_specialties', true );
?>
<div class="rtcl-agent-single-wrapper rtcl product-grid">
    <div class="rtcl-agent-info-wrap">
        <div class="rtcl-agent-img-wrap">
            <div class="rtcl-agent-img">
                <?php echo wp_kses_post($pp_id ? wp_get_attachment_image( $pp_id, [
                    400,
                    240
                ] ) : get_avatar( $user_id, 400 )); ?>
            </div>
            <?php if(RDTheme::$options['agent_single_listing_count']){ ?>
                <div class="agent-listing-count">
                    <span class="agent-block__listing">
                    <?php /* translators: %s: listing count. */
                    echo wp_kses_post( sprintf( _n( '<i class="icon-rt-icon-listing-line"></i> Listing: %s', '<i class="icon-rt-icon-listing-line"></i> Listings: %s ', count( $store->get_manager_listing_ids( $user_id ) ), 'clproperty' ), absint( count( $store->get_manager_listing_ids( $user_id ) ) ) ) ); ?>
                    </span>
                </div>
            <?php } ?>
            <?php
            $social_list = Functions::get_user_social_profile( $user_id );
            if ( ! empty( $social_list ) ) {
                ?>
                <div class="rtcl-agent-social">
                    <?php
                    foreach ( $social_list as $item => $value ) {
                        ?>
                        <a class="<?php echo esc_attr( $item ) ?>" target="_blank" href="<?php echo esc_url( $value ) ?>">
	                        <?php if ( 'twitter' === $item ) { ?>
                                <i class="rtcl-icon fa-brands fa-x-twitter"></i>
	                        <?php } elseif ( 'tiktok' === $item ) { ?>
                                <i class="rtcl-icon fa-brands fa-tiktok"></i>
	                        <?php } else { ?>
                                <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
	                        <?php } ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
        <div class="rtcl-agent-info">
            <?php if( class_exists( Rtrs\Modules\Review\Helpers\ReviewFns::class )  && Helper::get_total_ratings_by_review_schema() >=1 && RDTheme::$options['agent_single_rating']){ ?>
                <div class="rtcl-agent-rating">
                    <?php echo  wp_kses_post(Helper::get_total_review_star());  ?>
                    <span><?php echo '('.esc_html( Helper::get_total_ratings_by_review_schema()).esc_html__(' Reviews','clproperty').')'; ?></span>
                </div>
            <?php  } ?>
            <h3 class="agent-name"><?php echo esc_html( get_the_title() ); ?></h3>
            <div class="agency-name">
                <a href="<?php echo esc_url( get_the_permalink( $store_id ) ); ?>"><?php echo esc_html( get_the_title( $store_id ) ); ?></a>
            </div>
			<div class="agent-bio">
				<?php the_content(); ?>
            </div>
            <div class="rtcl-agent-meta">
	            <?php if ( $services && RDTheme::$options['agent_services']): ?>
                    <div class="agent-meta item-services">
                        <span><?php esc_html_e( 'Service Areas', 'clproperty' ); ?>:</span>
                        <?php echo esc_html( $services ); ?>
                    </div>
	            <?php endif; ?>
	            <?php if ( $specialties && RDTheme::$options['agent_single_listing_specialty'] ): ?>
                    <div class="agent-meta item-services">
                        <span><?php esc_html_e( 'Specialties', 'clproperty' ); ?>:</span>
			            <?php echo esc_html( $specialties ); ?>
                    </div>
	            <?php endif; ?>
				<?php if ( $phone && Functions::check_visibility( $user_id, 'phone' ) ): ?>
                    <div class="agent-meta item-phone">
                        <i class="icon-rt-icon-phone-line2"></i>
                        <span><?php esc_html_e( 'Phone', 'clproperty' ); ?>:</span>
                        <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                    </div>
				<?php endif; ?>
	            <?php if ( $whatsApp && Functions::check_visibility( $user_id, 'whatsapp' ) ): ?>
                <div class="agent-meta item-contact">
                    <i class="icon-rt-icon-email"></i>
                    <span><?php esc_html_e( 'Email', 'clproperty' ); ?>:</span>
                    <a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a>
                </div>
                <?php endif; ?>
				<?php if ( $whatsApp && Functions::check_visibility( $user_id, 'email' ) ): ?>
                    <div class="agent-meta item-whatsapp">
                        <i class="icon-rt-icon-whatsapp-solid"></i>
                        <span><?php esc_html_e( 'What\'s App', 'clproperty' ); ?>:</span>
                        <a target="_blank" href="https://wa.me/<?php echo esc_attr( $whatsApp ); ?>"><?php echo esc_html( $whatsApp ); ?></a>
                    </div>
				<?php endif; ?>
				<?php if ( $website ): ?>
                    <div class="agent-meta item-whatsapp">
                        <i class="icon-rt-icon-web"></i>
                        <span><?php esc_html_e( 'Website', 'clproperty' ); ?>:</span>
                        <a target="_blank"
                           href="<?php echo esc_url( $website ); ?>"><?php echo esc_url( $website ); ?></a>
                    </div>
				<?php endif; ?>
            </div>

        </div>
    </div>

	<?php Functions::get_template( 'agent/ad-listing', compact( 'user_id', 'store_id' ), '', rtclAgent()->get_plugin_template_path() ); ?>
    
    <?php 
        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
     ?>

</div>
