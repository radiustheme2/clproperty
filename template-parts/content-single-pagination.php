<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$previous = get_previous_post();
$next = get_next_post();
if ( $previous && $next ) {
	$cols = 'half-width';
} else {
	$cols = 'full-width';
}
if ( $previous || $next ):
	?>
    <div class="thumb-pagination <?php echo esc_attr( $cols ) ?>">
		<?php if ( $previous ):
         ?>
            <div class="col prev">
                <div class="post-nav prev-post">
                    <a href="<?php echo esc_url( get_permalink( $previous ) ); ?>" class="pg-prev">
                        <svg width="13" height="25" viewBox="0 0 13 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.2414 12.5058L12.7281 1.6419C13.0906 1.26626 13.0906 0.657272 12.7281 0.281639C12.3655 -0.0938798 11.7777 -0.0938798 11.4151 0.281639L0.271861 11.8257C-0.0906202 12.2013 -0.0906202 12.8103 0.271861 13.1859L11.4151 24.73C11.784 25.0991 12.3718 25.0885 12.7281 24.7063C13.0756 24.3335 13.0756 23.7425 12.7281 23.3697L2.2414 12.5058Z" fill="#828282"></path>
                        </svg>
                        <h5 class="item-title"><span><?php esc_html_e( 'Previous Post', 'clproperty' ) ?></span><?php echo esc_html( get_the_title( $previous ) ); ?></h5>
                    </a>
                </div>
            </div>
		<?php endif; ?>

		<?php if ( $next ):
			 ?>
            <div class="col next">
                <div class="post-nav next-post">
                    <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="pg-next">
                        <h5 class="item-title"><span><?php esc_html_e( 'Next Post', 'clproperty' ) ?></span><?php echo esc_html( get_the_title( $next ) ); ?></h5>
                        <svg width="13" height="25" viewBox="0 0 13 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.7586 12.5058L0.271902 1.6419C-0.0906334 1.26626 -0.0906334 0.657272 0.271902 0.281639C0.634546 -0.0938798 1.22234 -0.0938798 1.58493 0.281639L12.7281 11.8257C13.0906 12.2013 13.0906 12.8103 12.7281 13.1859L1.58493 24.73C1.21603 25.0991 0.62818 25.0885 0.271902 24.7063C-0.0756159 24.3335 -0.0756159 23.7425 0.271902 23.3697L10.7586 12.5058Z" fill="#828282"></path>
                      </svg>
                    </a>
                </div>
            </div>
		<?php endif; ?>
    </div>
<?php endif; ?>