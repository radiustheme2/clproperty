<?php
/**
 * CLProperty Mortgage Calculator
 *
 * Drop this file into your theme's inc/ folder and require it from functions.php:
 * require_once get_template_directory() . '/inc/clproperty-mortgage-calculator.php';
 *
 * Then call clproperty_render_mortgage_calculator() wherever you need it in your template.
 * It auto-detects the listing price from the current post meta.
 *
 * @package CLProperty
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Render the mortgage calculator HTML.
 *
 * Outputs a self-contained mortgage calculator with enqueued styles and JS.
 * Auto-populates the property price from listing meta if available.
 *
 * @param array $args {
 *     Optional. Configuration arguments.
 *
 *     @type float  $default_price         Default property price. Auto-detected from post meta if empty.
 *     @type float  $default_down_payment   Default down payment percentage. Default 20.
 *     @type float  $default_interest_rate  Default annual interest rate. Default 6.5.
 *     @type int    $default_loan_term      Default loan term in years. Default 30.
 *     @type string $currency_symbol        Currency symbol. Default '$'.
 *     @type string $title                  Calculator section title. Default 'Mortgage Calculator'.
 * }
 * @return void
 */
function clproperty_render_mortgage_calculator( $args = array() ) {

	$defaults = apply_filters( 'clproperty_mortgage_default_values', array(
		'default_price'         => 0,
		'default_down_payment'  => 20,
		'default_interest_rate' => 6.5,
		'default_loan_term'     => 30,
		'currency_symbol'       => '$',
		'title'                 => esc_html__( 'Mortgage Calculator', 'clproperty' ),
	) );

	$args = wp_parse_args( $args, $defaults );

	// Auto-detect listing price from post meta.
	$price = clproperty_get_listing_price( $args['default_price'] );

	$down_payment  = floatval( $args['default_down_payment'] );
	$interest_rate = floatval( $args['default_interest_rate'] );
	$loan_term     = intval( $args['default_loan_term'] );
	$currency      = esc_attr( $args['currency_symbol'] );
	$title         = esc_html( $args['title'] );

	$unique_id = 'clp-mortgage-' . wp_unique_id();

	?>
    <div id="<?php echo esc_attr( $unique_id ); ?>"
         class="clp-mortgage-calculator"
         data-currency="<?php echo esc_attr( $currency ); ?>"
         data-term="<?php echo esc_attr( $loan_term ); ?>">

        <div class="clp-mortgage-header">
            <svg class="clp-mortgage-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <h3 class="clp-mortgage-title"><?php echo esc_html( $title ); ?></h3>
        </div>

        <div class="clp-mortgage-body">
            <div class="clp-mortgage-fields">
                <!-- Property Price -->
                <div class="clp-mortgage-field">
                    <label><?php esc_html_e( 'Property Price', 'clproperty' ); ?></label>
                    <div class="clp-input-wrapper clp-input-currency">
                        <span class="clp-input-prefix"><?php echo esc_html( $currency ); ?></span>
                        <input
                                type="text"
                                class="clp-mortgage-input clp-field-price"
                                value="<?php echo esc_attr( $price ); ?>"
                                autocomplete="off"
                        />
                    </div>
                </div>

                <!-- Down Payment -->
                <div class="clp-mortgage-field">
                    <label><?php esc_html_e( 'Down Payment', 'clproperty' ); ?></label>
                    <div class="clp-mortgage-field-row">
                        <div class="clp-input-wrapper clp-input-currency clp-input-flex">
                            <span class="clp-input-prefix"><?php echo esc_html( $currency ); ?></span>
                            <input
                                    type="text"
                                    class="clp-mortgage-input clp-field-down-amount"
                                    value="<?php echo esc_attr( $price * $down_payment / 100 ); ?>"
                                    autocomplete="off"
                            />
                        </div>
                        <div class="clp-input-wrapper clp-input-percent">
                            <input
                                    type="number"
                                    class="clp-mortgage-input clp-field-down-percent"
                                    value="<?php echo esc_attr( $down_payment ); ?>"
                                    min="0"
                                    max="100"
                                    step="1"
                                    autocomplete="off"
                            />
                            <span class="clp-input-suffix">%</span>
                        </div>
                    </div>
                </div>

                <!-- Interest Rate -->
                <div class="clp-mortgage-field">
                    <label><?php esc_html_e( 'Interest Rate', 'clproperty' ); ?></label>
                    <div class="clp-input-wrapper clp-input-percent-only">
                        <input
                                type="number"
                                class="clp-mortgage-input clp-field-rate"
                                value="<?php echo esc_attr( $interest_rate ); ?>"
                                min="0"
                                max="30"
                                step="0.1"
                                autocomplete="off"
                        />
                        <span class="clp-input-suffix">%</span>
                    </div>
                </div>

                <!-- Loan Term -->
                <div class="clp-mortgage-field">
                    <label><?php esc_html_e( 'Loan Term', 'clproperty' ); ?></label>
                    <div class="clp-mortgage-term-options">
						<?php
						/**
						 * Filter the available loan term options.
						 *
						 * @param array $term_options Array of loan term years.
						 */
						$term_options = apply_filters( 'clproperty_mortgage_term_options', array( 10, 15, 20, 25, 30 ) );
						foreach ( $term_options as $term_option ) :
							$is_active = ( $term_option === $loan_term ) ? ' clp-term-active' : '';
							?>
                            <span
                                    class="clp-term-btn<?php echo esc_attr( $is_active ); ?>"
                                    data-term="<?php echo esc_attr( $term_option ); ?>"
                                    role="button"
                                    tabindex="0"
                            >
								<?php echo esc_html( $term_option ); ?>
								<small><?php esc_html_e( 'yrs', 'clproperty' ); ?></small>
							</span>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="clp-mortgage-results">
                <div class="clp-result-monthly">
                    <span class="clp-result-label"><?php esc_html_e( 'Monthly Payment', 'clproperty' ); ?></span>
                    <span class="clp-result-value clp-out-monthly"><?php echo esc_html( $currency ); ?>0</span>
                </div>
                <div class="clp-result-details">
                    <div class="clp-result-item">
                        <span class="clp-result-detail-label"><?php esc_html_e( 'Loan Amount', 'clproperty' ); ?></span>
                        <span class="clp-result-detail-value clp-out-loan"><?php echo esc_html( $currency ); ?>0</span>
                    </div>
                    <div class="clp-result-item">
                        <span class="clp-result-detail-label"><?php esc_html_e( 'Total Interest', 'clproperty' ); ?></span>
                        <span class="clp-result-detail-value clp-out-interest"><?php echo esc_html( $currency ); ?>0</span>
                    </div>
                    <div class="clp-result-item">
                        <span class="clp-result-detail-label"><?php esc_html_e( 'Total Payment', 'clproperty' ); ?></span>
                        <span class="clp-result-detail-value clp-out-total"><?php echo esc_html( $currency ); ?>0</span>
                    </div>
                </div>
                <!-- Visual Breakdown Bar -->
                <div class="clp-result-bar-wrapper">
                    <div class="clp-result-bar">
                        <div class="clp-bar-principal"></div>
                        <div class="clp-bar-interest"></div>
                    </div>
                    <div class="clp-bar-legend">
						<span class="clp-legend-item">
							<span class="clp-legend-dot clp-legend-principal"></span>
							<?php esc_html_e( 'Principal', 'clproperty' ); ?>
						</span>
                        <span class="clp-legend-item">
							<span class="clp-legend-dot clp-legend-interest"></span>
							<?php esc_html_e( 'Interest', 'clproperty' ); ?>
						</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}


/**
 * Get the listing price from post meta.
 *
 * Tries multiple common meta keys used by CLProperty / RadiusTheme listing themes.
 *
 * @param float $fallback Fallback price if no meta found.
 * @return float
 */
function clproperty_get_listing_price( $fallback = 0 ) {

	if ( ! is_singular() ) {
		return floatval( $fallback );
	}

	$post_id = get_the_ID();

	// Common meta keys for CLProperty and RadiusTheme listing themes.
	$meta_keys = array(
		'price',
		'_price',
		'listing_price',
		'_listing_price',
		'property_price',
		'_property_price',
		'rt_price',
	);

	/**
	 * Filter the meta keys used to detect listing price.
	 *
	 * @param array $meta_keys Array of meta key strings.
	 * @param int   $post_id   Current post ID.
	 */
	$meta_keys = apply_filters( 'clproperty_mortgage_price_meta_keys', $meta_keys, $post_id );

	foreach ( $meta_keys as $key ) {
		$value = get_post_meta( $post_id, $key, true );
		if ( ! empty( $value ) && is_numeric( $value ) ) {
			return floatval( $value );
		}
	}

	return floatval( $fallback );
}