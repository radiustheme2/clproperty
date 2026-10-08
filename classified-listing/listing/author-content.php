<?php
/**
 * Author Listing
 *
 * @author     RadiusTheme
 * @package    ClassifiedListing/Templates
 * @version    2.2.1.1
 */

use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$author   = get_user_by( 'slug', get_query_var( 'author_name' ) );
$user_id  = $author->ID;
$store_id = get_user_meta( $user_id, '_rtcl_store_id', true );
$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
$whatsApp = get_user_meta( $user_id, '_rtcl_whatsapp_number', true );
$website  = get_user_meta( $user_id, '_rtcl_website', true );
$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store    = new Store( $store_id );

?>

<div class="rtcl-agent-single-wrapper rtcl product-grid">
	<div class="rtcl-agent-info-wrap">
		<div class="rtcl-agent-img-wrap">
			<div class="rtcl-agent-img">
				<?php echo wp_kses_post($pp_id ? wp_get_attachment_image( $pp_id, [
					220,
					220
				] ) : get_avatar( $user_id,'220' )); ?>
			</div>
			<?php
				$social_list = Functions::get_user_social_profile( $user_id );
				if ( ! empty( $social_list ) ) {
					?>
					<div class="rtcl-agent-social">
						<?php
						foreach ( $social_list as $item => $value ) {
							?>
							<a class="<?php echo esc_attr( $item ) ?>" target="_blank" href="<?php echo esc_url( $value ) ?>">
								<i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
							</a>
							<?php
						}
						?>
					</div>
			<?php } ?>
		</div>
		<div class="rtcl-agent-info">
			<h3 class="agent-name"><?php echo esc_html($author->display_name); ?></h3>
			<?php if($store_id){ ?>
				<h5 class="agency-name">
					<a href="<?php echo esc_url( get_the_permalink( $store_id ) ); ?>"><?php echo esc_html( get_the_title( $store_id ) ); ?></a>
				</h5>
			<?php } ?>
			<?php if($author->description){ ?>
				<div class="agent-bio">
				<?php echo esc_html($author->description); ?>
				</div>
			<?php } ?>
			<div class="rtcl-agent-meta">
				<?php if ( $phone ): ?>
                    <div class="agent-meta item-phone">
						<i class="icon-rt-icon-phone-line2"></i>
						<span><?php esc_html_e( 'Phone', 'clproperty' ); ?>:</span>
						<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
					</div>
				<?php endif; ?>
				<?php if ( $whatsApp ): ?>
                    <div class="agent-meta item-whatsapp">
						<i class="icon-rt-icon-whatsapp-solid"></i>
						<span><?php esc_html_e( 'What\'s App', 'clproperty' ); ?>:</span>
						<a target="_blank"
						   href="https://wa.me/<?php echo esc_attr( $whatsApp ); ?>"><?php echo esc_html( $whatsApp ); ?></a>
					</div>
				<?php endif; ?>
                <div class="agent-meta item-contact">
					<i class="icon-rt-icon-email"></i>
					<span><?php esc_html_e( 'Email', 'clproperty' ); ?>:</span>
					<a href="mailto:<?php echo esc_attr( $author->user_email ); ?>"><?php echo esc_html( $author->user_email ); ?></a>
				</div>
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
		<?php do_action( 'rtcl_author_details_after_meta', $user_id ); ?>
	</div>
	<?php Functions::get_template( 'listing/author-listing'); ?>
</div>