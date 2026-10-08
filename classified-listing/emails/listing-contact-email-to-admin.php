<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @var RtclEmail $email
 * @var array     $data
 * @var Listing   $listing
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p><?php esc_html_e( 'Hi Administrator,', 'clproperty' ); ?></p>
    <?php /* translators: %s: website name. */ ?>
    <p><?php echo esc_html( sprintf( __( 'A listing on your website %s received a message.', 'clproperty' ), Functions::get_blogname() ) ) ?></p>
    <?php /* translators: %1$s: listing URL, %2$s: listing title. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Listing :</strong> <a href="%1$s">%2$s</a>', 'clproperty' ), esc_url( $listing->get_the_permalink() ), esc_html( $listing->get_the_title() ) ) ); ?></p>
    <?php /* translators: %s: sender name. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender name:</strong> %s', 'clproperty' ), esc_html( $data['name'] ) ) ); ?></p>
    <?php /* translators: %s: sender email. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender email:</strong> %s', 'clproperty' ), esc_html( $data['email'] ) ) ); ?></p>
    <?php /* translators: %s: sender phone. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender phone:</strong> %s', 'clproperty' ), esc_html( $data['phone'] ) ) ); ?></p>
    <?php /* translators: %s: message content. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Sender message:</strong> %s', 'clproperty' ), esc_html( $data['message'] ) ) ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
