<?php
/**
 * Modal
 *
 * @var Store  $store
 * @var string $store_oh_type
 * @var array  $store_oh_hours
 * @var string $today
 * @package    classified-listing-store/templates
 * @version    1.0.0
 *
 * @author     RadiusTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RtclStore\Models\Store;
use RtclStore\Resources\Options;
use radiustheme\ClProperty\Helper;
use radiustheme\ClProperty\Listing_Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//global $store;
//$store_oh_type  = get_post_meta( $store->get_id(), 'oh_type', true );
//$store_oh_hours = get_post_meta( $store->get_id(), 'oh_hours', true );
//$store_oh_hours = is_array( $store_oh_hours ) ? $store_oh_hours : ( $store_oh_hours ? (array) $store_oh_hours : [] );
//$today          = strtolower( gmdate( 'l' ) );
//$days           = Options::store_open_hour_days();

global $store;
$store_oh_type  = get_post_meta( $store->get_id(), 'oh_type', true );
$store_oh_hours = get_post_meta( $store->get_id(), 'oh_hours', true );
$store_oh_hours = is_array( $store_oh_hours ) ? $store_oh_hours : ( $store_oh_hours ? (array) $store_oh_hours : array() );
$days           = Options::store_open_hour_days();
$today          = strtolower( gmdate( 'l' ) );
?>

<ul class="single-store-meta">
    <?php if ( $store_phone = $store->get_phone() ) : ?>
        <li>
            <i class="icon-rt-icon-phone-line2"></i>
            <span class="title"><?php esc_html_e( "Phone: ", "clproperty" ) ?></span>
            <a target="_blank" href="tel:<?php echo esc_attr( $store_phone ) ?>">
                <?php echo esc_html( $store_phone ) ?>
            </a>
        </li>
    <?php endif; ?>
    <?php if ( $store_email = $store->get_email() ) : ?>
        <li>
            <i class="icon-rt-icon-email"></i>
            <span class="title"><?php esc_html_e( "E-mail: ", "clproperty" ) ?></span>
            <a href="mailto:<?php echo esc_attr( $store_email ) ?>">
                <?php echo esc_html( $store_email ) ?>
            </a>
        </li>
    <?php endif; ?>
    <?php if ( $store_website = $store->get_website() ) : ?>
        <li>
            <i class="icon-rt-icon-web"></i>
            <span class="title"><?php esc_html_e( "Website: ", "clproperty" ) ?></span>
            <a target="_blank" href="<?php echo esc_url_raw( $store_website ) ?>" <?php echo Functions::is_external( $store_website ) ? ' rel="nofollow"'
                : ''; ?>><?php echo esc_html( $store_website ) ?></a>
        </li>
    <?php endif; ?>
    <?php if ( $stor_view_count = Listing_Functions::rt_get_post_view_count( $store->get_id() ) ) : ?>
        <?php if ( $stor_view_count > 999 ): ?>
            <li data-toggle="tooltip" data-placement="top" title="<?php
                /* translators: %s: view count number. */
                echo esc_attr( sprintf( __( 'Total Views: %s', 'clproperty' ), $stor_view_count ) ); ?>">
        <?php else : ?>
            <li>
        <?php endif; ?>
        <i class="fas fa-eye"></i>
        <?php
        $label = $stor_view_count < 10 ? esc_html__( 'View: ', 'clproperty' ) : esc_html__( 'Views: ', 'clproperty' );
        echo '<span class="count-label">' . esc_html( $label ) . '</span>'
            . '<span class="count-number">' . esc_html( Helper::rt_number_shorten( $stor_view_count, 1 ) ) . '</span>';
        ?>
        </li>
    <?php endif; ?>
        <?php
            $op_title = __('Opening Hours:','clproperty');
            $top_title = __('Today\'s Opening Hour:','clproperty');
            $_op_title = $store_oh_type=='always' ? $op_title : $top_title;
        ?>

        <?php if( $store_oh_type == "selected" ){ ?>
            <li class="store-today-schedule">
                <?php if(is_array( $store_oh_hours ) && ! empty( $store_oh_hours )){ ?>
                    <i class="icon-rt-icon-clock"></i>
                    <span class="title"><?php echo esc_html($_op_title ); ?></span>
                    <?php foreach ( $store_oh_hours as $hKey => $oh_hour ){
                        if( strtolower($days[ $hKey ]) == strtolower($today) && isset( $oh_hour['active'] ) ){ ?>
                            <span class="open-hour"><?php echo isset( $oh_hour['open'] ) ? esc_html( $oh_hour['open'] ) : ''; ?></span>
                            <span class="close-hour"><?php echo isset( $oh_hour['close'] ) ? esc_html( $oh_hour['close'] ) : ''; ?></span>
                            <?php break;
                        }
                        if( $hKey == $today && empty($oh_hour['active']) ){
                             esc_html_e( "Closed", "clproperty" );
                        }
                    }
                    ?>
                <?php } else { ?>
                    <i class="icon-rt-icon-clock"></i>
                    <span class="title"><?php echo esc_html($_op_title ); ?></span>
                    <span class="always-open"><?php esc_html_e( "Permanently Close", "clproperty" ) ?></span>
                <?php } ?>
            </li>
        <?php } elseif( $store_oh_type == 'always'){ ?>
            <li class="store-today-schedule">
                <i class="icon-rt-icon-clock"></i>
                <span class="title"><?php echo esc_html( $_op_title ); ?></span>
                <span class="always-open"><?php esc_html_e( "Always Open", "clproperty" ) ?></span>
            </li>
        <?php } ?>
    <?php
    ?>
</ul>
<div class="other-store-schedule">
    <?php if($store_oh_type == "selected"){ ?>
        <button class="store-share" data-toggle="modal" data-target="#storeScheduleModal">
            <i class="icon-rt-icon-clock"></i>
            <span><?php esc_html_e('Other\'s Date','clproperty'); ?></span>
        </button>
        <?php  if(is_array( $store_oh_hours ) && ! empty( $store_oh_hours )){ ?>
            <div class="modal fade" id="storeScheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content store-schedule-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php esc_html_e( 'Store Opening Hours', 'clproperty' ); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php foreach($store_oh_hours as $hKey => $oh_hour):
                                if($hKey==$today && isset( $oh_hour['active'])){
                                    continue;
                                } ?>
                                <div class="schedule-label">
                                    <span class="hour-day"><?php echo esc_html( $days[ $hKey ] ?? $hKey ); ?></span>
                                    <?php if(isset( $oh_hour['active'])) { ?>
                                        <span class="open-hour"><?php echo isset( $oh_hour['open'] ) ? esc_html( $oh_hour['open'] ) : ''; ?></span>
                                        <span class="close-hour"><?php echo isset( $oh_hour['close'] ) ? esc_html( $oh_hour['close'] ) : ''; ?></span>
                                <?php } else { ?>
                                        <span class="off-day"><?php esc_html_e( "Closed", "clproperty" ) ?></span>
                                    <?php } ?>
                                </div>
                            <?php endforeach;
                            ?>
                        </div>
                    </div>
                </div>
        </div>
        <?php } ?>
    <?php } ?>
</div>
