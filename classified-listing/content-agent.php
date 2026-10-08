<?php
/**
 * Agent single content
 *
 * @author     RadiusTheme
 * @package    rtcl-agent/templates
 * @version    1.0
 *
 */

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
$name     = trim((string) implode(' ', array_filter([ $user->first_name, $user->last_name ])));
$name     = $name ? $name : $user->display_name;
$phone    = get_user_meta( $user_id, '_rtcl_phone', true );
$pp_id    = absint( get_user_meta( $user_id, '_rtcl_pp_id', true ) );
$store    = new Store( $store_id );

?>

<div class="agent-holder">
    <div class="agent-block">
        <div class="agent-block__top">
            <div class="agent-block__social">
                <?php
                    $social_list = Functions::get_user_social_profile( $user_id );
                    if ( ! empty( $social_list ) ) { 
                ?>
                    <ul>
                        <li>
                            <a href="#" class="social-hover-icon social-link">
                                <i class="icon-rt-icon-share-line"></i>
                            </a>
                            <ul>
                                <?php foreach ( $social_list as $item => $value ) { ?>
                                    <li>
                                        <a target="_blank" href="<?php echo esc_url( $value ) ?>" class="<?php echo esc_attr( $item ) ?>">
	                                        <?php if ( 'twitter' === $item ) { ?>
                                                <i class="rtcl-icon fa-brands fa-x-twitter"></i>
	                                        <?php } elseif ( 'tiktok' === $item ) { ?>
                                                <i class="rtcl-icon fa-brands fa-tiktok"></i>
	                                        <?php } else { ?>
                                                <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $item ) ?>"></i>
	                                        <?php } ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>
            </div>
            <div class="agent-block__figure">
			    <?php echo wp_kses_post( $pp_id ? wp_get_attachment_image( $pp_id, [
					168,
					197,
				] ) : get_avatar( $user_id, 340 ) ); ?>
            </div>
            <div class="agent-block__shape">
                <svg width="198" height="67" viewBox="0 0 198 67" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M195.644 1.51209C197.813 2.83041 198.617 5.59754 197.399 7.82399C187.925 25.1443 174.606 39.5984 158.581 49.9032C141.344 60.9876 121.584 66.8807 101.286 66.9904C80.9885 67.1001 60.8687 61.4225 42.949 50.5282C26.2674 40.3866 12.0396 26.057 1.48895 8.81458C0.167819 6.65552 0.865556 3.85701 2.99456 2.48794C5.30078 1.00491 8.37216 1.7711 9.82084 4.09904C19.4574 19.5844 32.3428 32.4637 47.4079 41.6225C63.8601 51.6247 82.3323 56.8373 100.968 56.7366C119.603 56.6359 137.745 51.2254 153.571 41.0488C167.967 31.7914 179.984 18.8869 188.643 3.43866C190.036 0.952335 193.209 0.0314329 195.644 1.51209Z">
                </path>
                </svg>
            </div>
        </div>
        <div class="agent-block__content">
            <?php if( class_exists( Rtrs\Modules\Review\Helpers\ReviewFns::class ) && RDTheme::$options['show_agent_ratings'] && Helper::get_total_ratings_by_review_schema() >=1 ){ ?>
                <div class="rating-icon">
                    <?php  
                        echo  wp_kses_post(Helper::get_total_review_star());  
                    ?>
                    <span><?php echo '('.esc_html( Helper::get_total_ratings_by_review_schema()).')'; ?></span>
                </div>
            <?php  } ?>
            <div class="item-title">
                <h3 class="agent-name"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( $name ); ?></a></h3>
                <div class="agency-name">
                    <a href="<?php echo esc_url( get_the_permalink( $store_id ) ); ?>"><?php echo esc_html( get_the_title( $store_id ) ); ?></a>
                </div>
            </div>
        </div>
        <div class="agent-block__footer">
            <?php if(RDTheme::$options['show_listing_count']){ ?>
                <span class="agent-block__listing">
                    <i class="icon-rt-icon-listing-line"></i>
                    <span>
                        <?php
                        $count = intval( count( $store->get_manager_listing_ids( $user_id ) ) );
                        $listing_title =__('Listing:','clproperty');
                        $count = $count == 0 ? 0 : $count;
                        printf( '%s %s', esc_html( $listing_title ), absint( $count ) ); ?>
                    </span>
                </span>
            <?php } ?>
            <?php if ( $phone && RDTheme::$options['show_agent_phone']): ?>
                <div class="item-phone">
                    <i class="icon-rt-icon-phone-line2"></i>
                    <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                </div>
			<?php endif; ?>
            <?php if(RDTheme::$options['show_agent_mail']){ ?>
                <div class="item-contact">
                    <i class="icon-rt-icon-message2"></i>
                    <a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a>
                </div>
            <?php } ?>
        </div>
        
    </div>
</div>
<?php

