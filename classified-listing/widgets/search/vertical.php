<?php
/**
 * @var boolean $can_search_by_category
 * @var boolean $can_search_by_keyword
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;

$orderby = strtolower(Functions::get_option_item('rtcl_archive_listing_settings', 'taxonomy_orderby', 'name'));
$order = strtoupper(Functions::get_option_item('rtcl_archive_listing_settings', 'taxonomy_order', 'DESC'));
?>
<div class="rtcl rtcl-store-search  rtcl-store-search-vertical">
    <form action="<?php echo esc_url(get_permalink(Functions::get_page_id('store'))); ?>"
          class="rtcl-store-widget-search-vertical rtcl-store-widget-search-form">

        <?php if ($can_search_by_keyword) : ?>
            <div class="form-group">
                <input type="text" name="q" class="form-control"
                       placeholder="<?php esc_html_e('Enter your keyword here ...', 'clproperty'); ?>"
                       value="<?php if (isset($_GET['q'])) {
                           echo esc_attr( Functions::clean( wp_unslash( $_GET['q'] ) ) );
                       } ?>">
            </div>
	    <?php endif; ?>

        <?php if ($can_search_by_category) : ?>
            <!-- Category field -->
            <div class="form-group">
                <label><?php esc_html_e('Select a category', 'clproperty'); ?></label>
                <?php
                $category = 0;
                if ($cat_slug = get_query_var('store_category')) {
                    $cTerm = get_term_by('slug', $cat_slug, rtclStore()->category);
                    if ($cTerm) {
                        $category = $cTerm->term_id;
                    }
                }
                Functions::dropdown_terms(array(
                    'show_option_none'  => '-- ' . esc_html__('Select a category', 'clproperty') . ' --',
                    'option_none_value' => -1,
                    'taxonomy'          => rtclStore()->category,
                    'name'              => 'c',
                    'class'             => 'form-control rtcl-store-category-search',
                    'selected'          => $category
                ));
                ?>
            </div>
        <?php endif; ?>
        <!-- Action buttons -->
        <button type="submit" class="agent-search__button"><i class="icon-rt-icon-search-line"></i></button>
        <div class="reset-button">
            <a href="<?php echo esc_url(strtok($_SERVER["REQUEST_URI"], '?')); ?>">
                <i class="fas fa-sync-alt"></i>
                <?php esc_html_e('Reset', 'clproperty'); ?>
            </a>
        </div>
    </form>
</div>