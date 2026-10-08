<?php
/**
 * Result Count
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;

?>
<div class="rtcl-result-count">
	<?php
	$post_per_pare = Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_top_per_page', 2 );
	$per_page      += absint( $post_per_pare );
	if ( 1 === $total ) {
		esc_html_e( 'Showing the single Listing', 'clproperty' );
	} elseif ( $total <= $per_page || - 1 === $per_page ) {
		/* translators: %d: total results */
		printf( esc_html( _n( '%d Listing Found', '%d Listings Found', $total, 'clproperty' ) ), absint( $total ) );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		echo wp_kses_post( sprintf( _nx( 'Showing %1$d&ndash;%2$d of %3$d Listing', 'Showing %1$d&ndash;%2$d of %3$d Listings', $total, 'with first and last result', 'clproperty' ), absint( $first ), absint( $last ),
			absint( $total ) ) );
	}
	?>
</div>
