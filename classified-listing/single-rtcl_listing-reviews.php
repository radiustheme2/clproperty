<?php
/**
 * Display single listing reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/classified-listing/single-rtcl_listing-reviews.php.
 *
 * @see
 * @author     RadiusTheme
 * @package    classified-listing/Templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use Rtcl\Helpers\Link;

if ( ! comments_open() ) {
	return;
}

global $post;
$listing = rtcl()->factory->get_listing( $post->ID );
if ( ! $listing->exists() ) {
	return;
}
$comment_class=have_comments() ? 'has-comment':'no-comments';
?>
<div id="reviews" class="clproperty-accordion-item rtcl-sl-section <?php echo esc_attr($comment_class); ?> single-listing-reviews-main-wrapper">
	<div class="accordion-header" id="clproperty_listing_review_heading">
		<h3 class="mb-0">
			<button class="btn" data-toggle="collapse" data-target="#review-form-wrapper" aria-expanded="true" aria-controls="review-form-wrapper">
			    <?php esc_html_e( 'Leave Feedback About This', 'clproperty' ); ?>
			</button>
		</h3>
    </div>
    <div id="review-form-wrapper" class="collapse show" data-parent="#review-form-wrapper">
		<div class="clproperty-accordion-content">
			<div id="review-form">
				<?php if ( have_comments() ) :
					$average = $listing->get_average_rating();
					$rating_count = $listing->get_rating_count();
					?>
					<!-- Single Listing Review / Meta -->
					<div class="item-heading">
						<div class="comments-count">
							<?php echo wp_kses_post( Functions::get_rating_html( $average, $rating_count ) ); ?>
						</div>
						<div class="heading-title">
							<?php printf( '<span class="reviews-rating-count">(%s</span> %s)', absint( $rating_count ), esc_html__( 'Reviews', 'clproperty' ) ); ?>
						</div>
					</div>
				<?php else : ?>
					<p class="rtcl-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'clproperty' ); ?></p>
				<?php endif; ?>
				<?php
				$commenter = wp_get_current_commenter();

				$comment_form = [
					'title_reply'         => have_comments() ? __( 'Leave A Review', 'clproperty' )
						: sprintf(
					/* translators: %s: listing title. */
					__( 'Be the first to review &ldquo;%s&rdquo;', 'clproperty' ),
					get_the_title()
				),
					/* translators: %s: commenter name. */
				'title_reply_to'      => __( 'Leave a Reply to %s', 'clproperty' ),
					'title_reply_before'  => '<h4 id="reply-title" class="comment-reply-title">',
					'title_reply_after'   => '</h4>',
					'comment_notes_after' => '',
					'fields'              => [
						'author' => '<div class="row"><div class="col-md-6"><div class="comment-form-author form-group">' . '<label for="author">' . esc_html__( 'Name', 'clproperty' )
									. '<span class="required"> *</span></label> ' .
									'<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] )
									. '" size="30" aria-required="true" required /></div></div>',
						'email'  => '<div class="col-md-6"><div class="comment-form-email form-group"><label for="email">' . esc_html__( 'Email', 'clproperty' )
									. '<span class="required"> *</span></label> ' .
									'<input id="email" name="email" class="form-control" type="email" value="' . esc_attr( $commenter['comment_author_email'] )
									. '" size="30" aria-required="true" required /></div></div></div>',
					],
					'label_submit'        => esc_attr__( 'Submit Now', 'clproperty' ),
					'class_submit'        => 'submit-btn',
					'logged_in_as'        => '',
					'comment_field'       => '',
				];

				if ( $account_page_url = Link::get_my_account_page_link() ) {
					/* translators: %s: login page URL. */
				$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'clproperty' ),
							esc_url( $account_page_url ) ) . '</p>';
				}

				if ( Functions::get_option_item( 'rtcl_single_listing_settings', 'enable_review_rating', false, 'checkbox' ) ) {
					$comment_form['comment_field'] = '<div class="comment-form-title  form-group"><label for="title">' . esc_html__( 'Review title', 'clproperty' )
													. '<span class="required"> *</span></label><input type="text" class="form-control" name="title" id="title"  aria-required="true" required/></div>';
					$comment_form['comment_field'] .= '<div class="comment-form-rating  form-group"><label for="rating">' . esc_html__( 'Your rating', 'clproperty' ) . '<span class="required"> *</span></label><select name="rating" id="rating" class="form-control" aria-required="true" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'clproperty' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'clproperty' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'clproperty' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'clproperty' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'clproperty' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'clproperty' ) . '</option>
							</select></div>';
				}

				$comment_form['comment_field'] .= '<div class="comment-form-comment  form-group"><label for="comment">' . esc_html__( 'Your review', 'clproperty' )
												. '<span class="required"> *</span></label><textarea id="comment" class="form-control" name="comment" cols="45" rows="8" aria-required="true" required></textarea></div>';

				comment_form( apply_filters( 'rtcl_listing_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>
    </div>
</div>