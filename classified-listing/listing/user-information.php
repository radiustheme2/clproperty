<?php
/**
 * @var string  $phone
 * @var string  $whatsapp_number
 * @var string  $email
 * @var string  $website
 * @var array   $phone_options
 * @var bool    $has_contact_form
 * @var string  $email_to_seller_form
 * @var Listing $listing
 * @var array   $locations
 * @var int     $listing_id Listing id
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use \radiustheme\ClProperty\RDTheme;
?>
<div class="rtcl-listing-user-info widget">
	<?php if ( $phone || $email || $website ) : ?>
        <div class="widget-contact-form dd">
			<?php if(RDTheme::$options['show_owner_store_title']){ ?>
            	<h3 class="widget-heading"><?php esc_html_e( "Contact Agent", 'clproperty' ); ?></h3>
			<?php } ?>
			<?php do_action( 'rtcl_add_user_information', $listing_id ); ?>
            <div class="rtcl-chat-website-link">
				<?php
				if ( Fns::is_enable_chat() && is_user_logged_in() ):
					$chat_btn_class = [ 'rtcl-chat-link' ];
					$chat_url = Link::get_my_account_page_link();
					if ( is_user_logged_in() ) {
						$chat_url = '#';
						array_push( $chat_btn_class, 'rtcl-contact-seller' );
					} else {
						array_push( $chat_btn_class, 'rtcl-no-contact-seller' );
					}
					?>
                    <div class='rtcl-contact-seller'>
                        <a class="<?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>"
                           href="<?php echo esc_url($chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
                            <i class="fas fa-comment"></i>
							<?php esc_html_e("Quick Chat", "clproperty" ) ?>
                        </a>
                    </div>
				<?php endif; ?>
            </div>

			<?php if ( $has_contact_form && $email ) : ?>
                <div class='rtcl-do-email'>
					<?php
					Functions::print_html( $email_to_seller_form, true );
					?>
                </div>
			<?php endif; ?>
        </div>
	<?php endif; ?>
</div>
