<?php
/**
 * @var string $category_filter
 * @var string $location_filter
 * @var string $ad_type_filter
 * @var string $custom_field_filter
 * @var string $price_filter
 * @var Filter $object
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Widgets\Filter;
use RtclPro\Helpers\Fns;

?>
<div class="panel-block">
    <form class="rtcl-filter-form"
          action="<?php echo esc_url( Functions::get_filter_form_url() ) ?>">
		<?php do_action( 'rtcl_widget_before_filter_form', $object, $data ) ?>
        <div class="ui-accordion">
			<?php Functions::print_html( $ad_type_filter, true ); ?>
			<?php Functions::print_html( $category_filter, true ); ?>
			<?php
			if ( method_exists('Rtcl\Helpers\Functions','location_type') && 'local' === Functions::location_type() ):
				Functions::print_html( $location_filter, true );
			endif;
			?>
			<?php Functions::print_html( $custom_field_filter, true ); ?>
			<?php Functions::print_html( $price_filter, true ); ?>
			<?php do_action( 'rtcl_widget_filter_form_end', $object, $data ) ?>
        </div>
		<?php do_action( 'rtcl_widget_after_filter_form', $object, $data ) ?>
    </form>
</div>
