<?php
/**
 * Show options for ordering
 *
 * @var array  $catalog_orderby_options
 * @var string $orderby
 * @version     1.5.5
 *
 */

use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( empty( $catalog_orderby_options ) ) {
	return;
}
unset( $catalog_orderby_options['date-desc'] );
$newvalue                    = [ 'date-desc' => __( 'Default', 'clproperty' ) ];
$new_catalog_orderby_options = $newvalue + $catalog_orderby_options;
?>

<form class="rtcl-ordering" method="get">
    <label for="orderby"><?php echo esc_html__( 'Sort by:', 'clproperty' ); ?></label>
    <select name="orderby" id="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Listing order', 'clproperty' ); ?>">
		<?php foreach ( $new_catalog_orderby_options as $id => $name ) : ?>
            <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
    </select>
    <input type="hidden" name="paged" value="1"/>
	<?php Functions::query_string_form_fields( null, [ 'orderby', 'submit', 'paged' ] ); ?>
</form>
