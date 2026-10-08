<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @var RtclEmail $email
 * @var Listing   $listing
 * @var array     $data
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <?php /* translators: %s: listing owner name. */ ?>
    <p><?php printf( esc_html__( 'Hi %s,', 'clproperty' ), esc_html( $listing->get_owner_name() ) ); ?></p>
    <?php /* translators: %1$s: listing URL, %2$s: listing title. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( 'You have received a reply from your listing at <a href="%1$s">%2$s</a>', 'clproperty' ),
			esc_url( $listing->get_the_permalink() ),
			esc_html( $listing->get_the_title() ) ) ) ?></p>
    <?php /* translators: %s: sender name. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Name:</strong> %s', 'clproperty' ), esc_html( $data['name'] ) ) ); ?></p>
    <?php /* translators: %s: sender email. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Email:</strong> %s', 'clproperty' ), esc_html( $data['email'] ) ) ); ?></p>
    <?php /* translators: %s: sender phone. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Phone:</strong> %s', 'clproperty' ), esc_html( $data['phone'] ) ) ); ?></p>
    <?php /* translators: %s: message content. */ ?>
    <p><?php echo wp_kses_post( sprintf( __( '<strong>Message:</strong> %s', 'clproperty' ), esc_html( $data['message'] ) ) ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
