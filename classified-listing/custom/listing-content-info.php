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

use radiustheme\ClProperty\Helper;
use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use \radiustheme\ClProperty\RDTheme;

global $listing;
$phone                      = get_post_meta( $listing->get_id(), 'phone', true );
$whatsapp                   = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
$email                      = get_post_meta( $listing->get_id(), 'email', true );
$website                    = get_post_meta( $listing->get_id(), 'website', true );
$rating_count               = $listing->get_rating_count();
$average_rating             = $listing->get_average_rating();
$listing_owner_widget_title = RDTheme::$options['listing_owner_widget_title'];
$user_login_class=is_user_logged_in() ? 'has-chat':'no-chat';
?>

<div class="rtcl-listing-user-info widget <?php echo esc_attr($user_login_class); ?>">
	<?php if ( $phone || $email || $website || $whatsapp ) : ?>
        <div class="widget-contact-form">
            <?php if(RDTheme::$options['show_owner_store_title']){ ?>
                <h3 class="widget-heading">
                    <?php
                    if ( $listing_owner_widget_title) {
                        echo esc_html( $listing_owner_widget_title );
                    } else {
                        echo esc_html__( "Contact Listing Owner", 'clproperty' );
                    }
                    ?>
                </h3>
            <?php } ?>

            <!-- Tab Navigation -->
            <div class="rtcl-sidebar-tabs">
                <ul class="rtcl-sidebar-tab-nav">
                    <li class="rtcl-sidebar-tab-btn active" data-tab="information"><?php esc_html_e( 'Information', 'clproperty' ); ?></li>
                    <?php if ( $email ) : ?>
                        <li class="rtcl-sidebar-tab-btn" data-tab="contact"><?php esc_html_e( 'Contact', 'clproperty' ); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Information Tab Content -->
            <div class="rtcl-sidebar-tab-content active" data-tab-content="information">
                <div class="rtcl-member-info-wrapper">
                    <div class="member-img">
                        <a  href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#';?>" class="user-avatar">
                            <?php Helper::get_listing_author_iamge( $listing,105 ); ?>
                        </a>
                        <div class="title-wrapper">
                            <h4>
                                <a href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#';?>">
				                    <?php echo esc_html( $listing->get_author_name() ); ?>
                                </a>
                            </h4>
                            <?php
                            ob_start();
                            do_action('rtcl_after_author_meta', $listing->get_owner_id());
                            $author_meta = ob_get_clean();
                            if ( trim( $author_meta ) ) : ?>
                                <div class="rtin-user-item">
                                    <?php echo wp_kses_post( $author_meta ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="member-content">
                        <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { ?>
                            <p class="login-message"><?php /* translators: %s: login page URL. */
                            echo wp_kses(sprintf(__("Please <a href='%s'>login</a> to view the seller information.", "clproperty"), esc_url(Link::get_my_account_page_link())), ['a' => ['href' => []]]); ?></p>
                        <?php } else { ?>
                            <?php if ( $phone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ) :
                                    $mobileClass = wp_is_mobile() ? " rtcl-mobile" : null;
                                    $phone_options = [
                                        'safe_phone'   => mb_substr( $phone, 0, mb_strlen( $phone ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' ),
                                        'phone_hidden' => mb_substr( $phone, - 3 )
                                    ];
                            ?>
                            <div class="item-number phone reveal-phone<?php echo esc_attr($mobileClass); ?>" data-options="<?php echo esc_attr( wp_json_encode( $phone_options ) ); ?>">
                                <div class='numbers'><i class="icon-rt-icon-phone-line2"></i><?php echo esc_html($phone_options['safe_phone']); ?></div>
                                <small class='text-muted'><?php esc_html_e("(Show)","clproperty") ?></small>
                            </div>
                        <?php endif; ?>
                        <?php if ( $whatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' ) ) : ?>
                            <div class="item-number  whatsapp">
                                <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $whatsapp ); ?>&text=<?php echo esc_html( get_the_title() );?>">
                                    <i class="icon-rt-icon-whatsapp-solid"></i> <?php echo esc_html( $whatsapp ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( $email && Functions::check_visibility( $listing->get_author_id(), 'email' ) ) : ?>
                            <div class="agency-email listing-mail">
                                <a href="mailto:<?php echo esc_attr( $email ); ?>"><i class="icon-rt-icon-email"></i><?php echo esc_html( $email ); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if($website): ?>
                            <div class="agency-website listing-website">
                                <a href="<?php echo esc_url( $website ); ?>"><i class="icon-rt-icon-web"></i><?php echo esc_html__('Visit Website','clproperty'); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $rating_count && RDTheme::$options['show_owner_store_rating']) ): ?>
                            <div class="product-rating listing-rating">
                                <?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
                                <div class="item-text"><?php echo wp_kses_post( apply_filters( 'clproperty_rating_count_format',
                                        sprintf(
                                /* translators: %s: review count. */
                                __( '(<span>%s</span>) Reviews', 'clproperty' ),
                                esc_html( $rating_count )
                            ) ) ); ?></div>
                            </div>
                        <?php endif; ?>
                        <?php } ?>
                    </div>
                </div>

                <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { } else {?>
                    <?php if(is_user_logged_in()){ ?>
                    <div class="rtcl-chat-website-link">
                        <?php
                        if ( Fns::is_enable_chat() && is_user_logged_in() ):
                            $chat_btn_class = [ 'rtcl-chat-link' ];
                            $chat_url = Link::get_my_account_page_link('chat');
                            $is_chat = 'rtcl-contact-link';
                            $chat_label = 'Live Chat';
                            if ( is_user_logged_in() && $listing->get_author_id() !== get_current_user_id() ) {
                                $chat_url   = '#';
                                $chat_label = __('Quick Chat','clproperty');
                                $is_chat    = 'rtcl-contact-seller';
                                array_push( $chat_btn_class, 'rtcl-contact-seller' );
                            }
                            ?>
                            <div class='<?php echo esc_attr( $is_chat ); ?>'>
                                <a class="<?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>"
                                href="<?php echo esc_url( $chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
                                    <i class="fas fa-comment"></i><?php echo esc_html($chat_label ); ?>
                                    <span class="rtcl-chat-unread-count"></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <!-- Contact Tab Content -->
            <?php if ( $email ) : ?>
                <div class="rtcl-sidebar-tab-content" data-tab-content="contact">
                    <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { } else {?>
                        <div class='rtcl-do-email'>
                            <?php $listing->email_to_seller_form(); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php endif; ?>

        </div>
	<?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabBtns = document.querySelectorAll('.rtcl-sidebar-tab-btn');
    var tabContents = document.querySelectorAll('.rtcl-sidebar-tab-content');

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetTab = this.getAttribute('data-tab');

            tabBtns.forEach(function (b) { b.classList.remove('active'); });
            tabContents.forEach(function (c) { c.classList.remove('active'); });

            this.classList.add('active');
            var target = document.querySelector('[data-tab-content="' + targetTab + '"]');
            if (target) target.classList.add('active');
        });
    });
});
</script>
