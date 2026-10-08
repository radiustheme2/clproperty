<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\ClProperty\RDTheme;

$login_icon_title = is_user_logged_in() ? esc_html__( " My Account", 'clproperty' ) : esc_html__( " Sign in", 'clproperty' );

if (!empty(RDTheme::$options['header_btn_url'])){
    $button_url = RDTheme::$options['header_btn_url'];
} else {
    $button_url = Link::get_listing_form_page_link();
}
?>

<div class="listing-area">
    <div class="header-action">
        <ul class="header-btn">

			<?php if ( class_exists( 'RtclPro' ) && Fns::is_enable_compare() && RDTheme::$options['header_compare_icon'] ):
				$compare_ids = rtcl()->session->get( 'rtcl_compare_ids', [] );
				if ( ! empty( $compare_ids ) || is_array( $compare_ids ) ) {
					$compare_ids = count( $compare_ids );
				}
				?>
                <li class="compare-btn has-count-number button">
                    <a class="listing-btn"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php echo esc_attr( 'Compare' ); ?>"
                       href="<?php echo esc_url( Link::get_page_permalink( 'compare_page' ) ); ?>">
                        <i class="icon-rt-icon-compare-line"></i>
                        <span class="count rt-compare-count"><?php echo esc_html( $compare_ids ) ?></span>
                    </a>
                </li>
			<?php endif; ?>

			<?php if ( class_exists( 'Rtcl' ) && Functions::is_enable_favourite() && RDTheme::$options['header_fav_icon'] ):
				$favourite_posts = get_user_meta( get_current_user_id(), 'rtcl_favourites', true );
				if ( ! empty( $favourite_posts ) || is_array( $favourite_posts ) ) {
					$favourite_posts = count( $favourite_posts );
				}
				$favourite_posts = $favourite_posts ? $favourite_posts : '0';
				
				
				?>
                <li class="favourite has-count-number button">
                    <a class="listing-btn"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php esc_attr_e( 'Favourites', 'clproperty' ); ?>"
                       href="<?php echo esc_url( Link::get_my_account_page_link( 'favourites' ) ); ?>">
                        <i class="icon-rt-icon-heart-line"></i>
                        <span class="count rt-header-favourite-count"><?php echo esc_html( $favourite_posts ) ?></span>
                    </a>
                </li>
			<?php endif; ?>
			
			<?php if ( class_exists( 'Rtcl' ) && RDTheme::$options['header_login_icon'] ):
				?>
                <li class="login-btn button">
                    <a class="listing-btn"
                       data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php echo esc_attr( $login_icon_title ); ?>"
                       href="<?php echo esc_url( Link::get_my_account_page_link() ); ?>">
                        <i class="icon-rt-icon-user-line"></i>
                    </a>
                </li>
			<?php endif; ?>
            <?php
			if ( RDTheme::$options['header_search_icon'] ) {
				?>
                <li class="search-icon button icon-hover-item" >
	                <?php get_template_part( 'template-parts/header/search', 'icon' ); ?>
                </li>
            <?php
			}
			?>
			<?php if ( (RDTheme::$has_header_btn==1 || RDTheme::$has_header_btn ==='on') && RDTheme::$options['header_btn_txt']):
				?>
                <li class="header-add-property-btn">
                    <a href="<?php echo esc_url( $button_url );?>">
                        <span class="plus">
                            <?php echo esc_html('+','clproperty'); ?>
                        </span>
                        <span class="text"><?php echo esc_html(RDTheme::$options['header_btn_txt']); ?></span>
                    </a>
                </li>

			<?php endif; ?>
            <li class="offcanvar_bar button">
                <span class="sidebarBtn ">
                    <span class="fa fa-bars">
                    </span>
                </span>
            </li>

        </ul>
    </div>
</div>
