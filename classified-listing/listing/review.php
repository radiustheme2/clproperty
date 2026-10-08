<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/listing/review.php.
 *
 * @see
 * @author  RadiusTheme
 * @package classified-listing/Templates
 * @version 1.0.0
 */

use Rtcl\Helpers\Functions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$title = get_comment_meta( $comment->comment_ID, 'title', true );
$time  = get_comment_date(). ' @' . get_comment_time();
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

    <div id="comment-<?php comment_ID(); ?>" class="comment-container">
        <div class="media">
            <div class="media-info">
                <?php
                /**
                 * The rtcl_review_before hook
                 *
                 * @hooked rtcl_review_display_gravatar - 10
                 */
                do_action('rtcl_review_before', $comment);
                ?>
            </div>
            <div class="comment-body media-body">
                <?php do_action('rtcl_review_before_comment_text', $comment); ?>

                <h5 class="item-title"><?php echo esc_html( $title ); ?>
                    <?php Functions::get_template( 'listing/review-rating' );?>
                </h5>

                <div class="item-date">
                    <span class="c-author"><?php comment_author(); ?></span>
                    <span class="c-seperator">/</span>
                    <?php echo esc_html( $time ); ?>
                </div>

                <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your review is awaiting moderation.', 'clproperty' ); ?></p>
                <?php endif; ?>

                <?php comment_text(); ?>

                <?php do_action('rtcl_review_after_comment_text', $comment); ?>
            </div>
        </div>
    </div>
