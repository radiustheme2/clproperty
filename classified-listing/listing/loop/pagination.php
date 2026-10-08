<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * 
 *
 *
 * @package classified-listing/Templates
 * @version 1.3
 */

use Rtcl\Helpers\Functions;

if (!defined('ABSPATH')) {
    exit;
}

$total = isset($total) ? $total : Functions::get_loop_prop('total_pages');
$current = isset($current) ? $current : Functions::get_loop_prop('current_page');
$base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', get_pagenum_link(999999999, false)));
$format = isset($format) ? $format : '';

if ($total <= 1) {
    return;
}
?>
<nav class="rtcl-pagination">
    <?php
    echo wp_kses_post( paginate_links( apply_filters( 'rtcl_pagination_args', array(
        'base'      => $base,
        'format'    => $format,
        'add_args'  => false,
        'current'   => max(1, $current),
        'total'     => $total,
        'prev_text' => '<i class="icon-rt-icon-angel-left-tiny"></i><i class="icon-rt-icon-angel-left-tiny"></i>',
        'next_text' => '<i class="icon-rt-icon-angel-right-tiny"></i><i class="icon-rt-icon-angel-right-tiny"></i>',
        'type'      => 'list',
        'end_size'  => 3,
        'mid_size'  => 3,
    ))));
    ?>
</nav>
