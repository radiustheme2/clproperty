<?php
/**
 * @var PaymentGateway $gateway
 *
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @author        RadiusTheme
 */

use Rtcl\Helpers\Functions;
use Rtcl\Models\PaymentGateway;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="list-group-item rtcl-no-margin-left rtcl-payment-method form-check">
    <input type="radio" name="payment_method" id="gateway-<?php echo esc_attr( $gateway->id ) ?>"
           value="<?php echo esc_attr( $gateway->id ) ?>" required>
	<?php Functions::print_html( $gateway->get_icon() ); ?>
    <label for="gateway-<?php echo esc_attr( $gateway->id ) ?>"><?php echo esc_html( $gateway->get_title() ) ?></label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) {
		echo wp_kses_post( sprintf( '<div class="payment_box payment_method_%s" %s>%s</div>',
			esc_attr( $gateway->id ),
			! $gateway->chosen ? 'style="display:none;"' : '',
			$gateway->payment_fields()
		) );
	} ?>
</li>