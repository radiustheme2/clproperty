<?php
/**
 * Render all custom fields from car-listing form
 * Exclude: select_make, select_model
 * Grouped: Checkbox/Radio in top wrapper, other fields in bottom wrapper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Services\FormBuilder\FBHelper;

// Get car listing form
$listingForm = FBHelper::getFormBySlug('clproperty-form');

if (!$listingForm || !is_object($listingForm)) {
	return;
}

// Get all fields from the form
$allFields = $listingForm->getFields();

if (empty($allFields) || !is_array($allFields)) {
	return;
}

// Fields to exclude
$excludeFields = ['select_make', 'select_model'];

// Separate fields into two groups
$checkboxRadioFields = [];
$otherFields = [];

foreach ($allFields as $field) {
	$filterable = isset($field['filterable']) ? $field['filterable'] : '';

	if ($filterable == 1) {
		// Skip if field name is in exclude list
		if (!isset($field['name']) || in_array($field['name'], $excludeFields)) {
			continue;
		}

		$fieldElement = isset($field['element']) ? $field['element'] : '';

		// Group checkbox and radio fields
		if (in_array($fieldElement, ['checkbox', 'radio'])) {
			$checkboxRadioFields[] = $field;
		} else {
			$otherFields[] = $field;
		}
	}
}

// ========== CHECKBOX & RADIO WRAPPER (TOP) ==========
if (!empty($checkboxRadioFields)) {
	?>
    <div class="rtcl-checkbox-radio-wrapper">
		<?php
		foreach ($checkboxRadioFields as $field) {
			$cfField = $field['name'];
			$fieldElement = isset($field['element']) ? $field['element'] : '';
			$fieldLabel = isset($field['label']) ? $field['label'] : ucwords(str_replace(['_', '-'], ' ', $cfField));
			$field_id = 'rtcl-cf-' . $cfField;

			$options = isset($field['options']) ? $field['options'] : [];
			$items = [];

			// Build options array
			foreach ($options as $option) {
				if (isset($option['value']) && isset($option['label'])) {
					$items[$option['value']] = esc_html($option['label']);
				}
			}

			if (empty($items)) {
				continue;
			}

			// RADIO BUTTONS
			if ($fieldElement === 'radio') {
				$current_value = isset($_GET['cf_' . $cfField]) ? sanitize_text_field($_GET['cf_' . $cfField]) : '';
				?>
                <div class="rtcl-form-group ws-item ws-radio rtcl-flex rtcl-flex-column">
                    <label class="rtcl-from-label">
						<?php echo esc_html($fieldLabel); ?>
                    </label>
                    <div class="rtcl-search-radio">
						<?php foreach ($items as $key => $value) {
							$radio_id = $field_id . '_' . sanitize_title($key);
							$checked = ($current_value === $key) ? ' checked' : '';
							?>
                            <label for="<?php echo esc_attr($radio_id); ?>" class="rtcl-radio-label">
                                <input
                                        type="radio"
                                        id="<?php echo esc_attr($radio_id); ?>"
                                        name="cf_<?php echo esc_attr($cfField); ?>"
                                        value="<?php echo esc_attr($key); ?>"
									<?php echo esc_attr( $checked ); ?>
                                />
                                <span><?php echo esc_html($value); ?></span>
                            </label>
						<?php } ?>
                    </div>
                </div>
				<?php
			}

			// CHECKBOXES
            elseif ($fieldElement === 'checkbox') {
				$current_values = isset($_GET['cf_' . $cfField]) ? (array) $_GET['cf_' . $cfField] : [];
				?>
                <div class="rtcl-form-group ws-item ws-checkbox rtcl-flex rtcl-flex-column">
                    <label class="rtcl-from-label">
						<?php echo esc_html($fieldLabel); ?>
                    </label>
                    <div class="rtcl-search-checkbox">
						<?php foreach ($items as $key => $value) {
							$checkbox_id = $field_id . '_' . sanitize_title($key);
							$checked = in_array($key, $current_values) ? ' checked' : '';
							?>
                            <label for="<?php echo esc_attr($checkbox_id); ?>" class="rtcl-checkbox-label">
                                <input
                                        type="checkbox"
                                        id="<?php echo esc_attr($checkbox_id); ?>"
                                        name="cf_<?php echo esc_attr($cfField); ?>[]"
                                        value="<?php echo esc_attr($key); ?>"
									<?php echo esc_attr( $checked ); ?>
                                />
                                <span><?php echo esc_html($value); ?></span>
                            </label>
						<?php } ?>
                    </div>
                </div>
				<?php
			}
		}
		?>
    </div>
	<?php
}

// ========== OTHER FIELDS WRAPPER (BOTTOM) ==========
if (!empty($otherFields)) {
	?>
    <div class="rtcl-other-fields-wrapper">
		<?php
		foreach ($otherFields as $field) {
			$cfField = $field['name'];
			$fieldElement = isset($field['element']) ? $field['element'] : '';
			$fieldLabel = isset($field['label']) ? $field['label'] : ucwords(str_replace(['_', '-'], ' ', $cfField));
			$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
			$field_id = 'rtcl-cf-' . $cfField;

			// SELECT FIELDS
			if ($fieldElement === 'select') {
				$options = isset($field['options']) ? $field['options'] : [];
				$items = [];

				foreach ($options as $option) {
					if (isset($option['value']) && isset($option['label'])) {
						$items[$option['value']] = esc_html($option['label']);
					}
				}

				if (empty($items)) {
					continue;
				}

				// Prepare placeholder text
				if (!empty($placeholder)) {
					$typeText = $placeholder;
				} else {
					$firstChar = $fieldLabel ? explode(' ', $fieldLabel)[0] : '';
					$typeText = $firstChar;
				}
				?>
                <div class="rtcl-form-group ws-item ws-type rtcl-flex rtcl-flex-column">
                    <label class="rtcl-from-label" for="<?php echo esc_attr($field_id); ?>">
						<?php echo esc_html($fieldLabel); ?>
                    </label>
                    <div class="rtcl-search-type">
                        <select class="rtcl-form-control" id="<?php echo esc_attr($field_id); ?>" name="cf_<?php echo esc_attr($cfField); ?>">
                            <option value=""><?php echo esc_html($typeText); ?></option>
							<?php
							foreach ($items as $key => $value) {
								$selected = isset($_GET['cf_' . $cfField]) && trim($_GET['cf_' . $cfField]) == $key ? ' selected' : '';
								?>
                                <option value="<?php echo esc_attr($key); ?>"<?php echo esc_attr( $selected ); ?>>
									<?php echo esc_html($value); ?>
                                </option>
							<?php } ?>
                        </select>
                    </div>
                </div>
				<?php
			}

			// TEXT, TEXTAREA FIELDS
            elseif (in_array($fieldElement, ['text', 'textarea'])) {
				$inputType = $fieldElement === 'textarea' ? 'textarea' : 'text';
				$currentValue = isset($_GET['cf_' . $cfField]) ? sanitize_text_field($_GET['cf_' . $cfField]) : '';

				// Prepare placeholder text
				if (!empty($placeholder)) {
					$placeholderText = $placeholder;
				} else {
					$firstChar = $fieldLabel ? explode(' ', $fieldLabel)[0] : '';
					$placeholderText = $firstChar;
				}
				?>
                <div class="rtcl-form-group ws-item ws-input rtcl-flex rtcl-flex-column">
                    <label class="rtcl-from-label" for="<?php echo esc_attr($field_id); ?>">
						<?php echo esc_html($fieldLabel); ?>
                    </label>
                    <div class="rtcl-search-input">
						<?php if ($inputType === 'textarea') { ?>
                            <textarea
                                    class="rtcl-form-control"
                                    id="<?php echo esc_attr($field_id); ?>"
                                    name="cf_<?php echo esc_attr($cfField); ?>"
                                    placeholder="<?php echo esc_attr($placeholderText); ?>"
                            ><?php echo esc_textarea($currentValue); ?></textarea>
						<?php } else { ?>
                            <input
                                    type="text"
                                    class="rtcl-form-control"
                                    id="<?php echo esc_attr($field_id); ?>"
                                    name="cf_<?php echo esc_attr($cfField); ?>"
                                    value="<?php echo esc_attr($currentValue); ?>"
                                    placeholder="<?php echo esc_attr($placeholderText); ?>"
                            />
						<?php } ?>
                    </div>
                </div>
				<?php
			}

			// NUMBER FIELDS (Range)
            elseif ($fieldElement === 'number') {
				$fMinValue = !empty($_GET['filters'][$cfField]['min']) ? esc_attr($_GET['filters'][$cfField]['min']) : '';
				$fMaxValue = !empty($_GET['filters'][$cfField]['max']) ? esc_attr($_GET['filters'][$cfField]['max']) : '';
				?>
                <div class="rtcl-form-group ws-item ws-number-range rtcl-flex rtcl-flex-column">
                    <label class="rtcl-from-label"><?php echo esc_html($fieldLabel); ?></label>
                    <div class="rtcl-search-number-range">
                        <div class="rtcl-flex">
                            <div class="rtcl-flex rtcl-flex-column rtcl-form-group ws-item">
                                <div class="ui-field">
                                    <input
                                            id="filters_<?php echo esc_attr($cfField); ?>_min"
                                            name="filters[<?php echo esc_attr($cfField); ?>][min]"
                                            type="number"
                                            value="<?php echo esc_attr($fMinValue); ?>"
                                            class="ui-input form-control rtcl-form-control"
                                            placeholder="<?php echo esc_attr__('Min.', 'clproperty'); ?>"
                                            step="any"
                                    />
                                </div>
                            </div>
                            <div class="rtcl-flex rtcl-flex-column rtcl-form-group ws-item">
                                <div class="ui-field">
                                    <input
                                            id="filters_<?php echo esc_attr($cfField); ?>_max"
                                            name="filters[<?php echo esc_attr($cfField); ?>][max]"
                                            type="number"
                                            value="<?php echo esc_attr($fMaxValue); ?>"
                                            class="ui-input form-control rtcl-form-control"
                                            placeholder="<?php echo esc_attr__('Max.', 'clproperty'); ?>"
                                            step="any"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}

			// DATE FIELDS
            elseif ($fieldElement === 'date') {
				$value = !empty($_GET['filters'][$cfField]) ? esc_attr($_GET['filters'][$cfField]) : '';

				if (!empty($placeholder)) {
					$placeholderText = $placeholder;
				} else {
					$placeholderText = esc_html__('Date / Time', 'clproperty');
				}

				$dateType = isset($field['date_type']) ? $field['date_type'] : 'single';
				$dateFormat = isset($field['date_format']) ? $field['date_format'] : 'Y-m-d H:i';

				$js_options = [
					'Y-m-d'  => 'YYYY-MM-DD',
					'm/d/Y'  => 'MM/DD/YYYY',
					'd/m/Y'  => 'DD/MM/YYYY',
					'F j, Y' => 'MMMM D, YYYY',
					'j F, Y' => 'D MMMM, YYYY',
					'j F Y'  => 'D MMMM YYYY',
					'h:i:s'  => 'hh:mm:ss',
					'g:i a'  => 'h:mm a',
					'g:i A'  => 'h:mm A',
					'H:i'    => 'HH:mm'
				];

				$find = array_keys($js_options);
				$replace = array_values($js_options);
				$jsFormat = str_replace($find, $replace, $dateFormat);

				$filterableDateType = isset($field['filterable_date_type']) ? $field['filterable_date_type'] : $dateType;

				$dateOptions = [
					'singleDatePicker' => $filterableDateType === 'single',
					'showDropdowns'    => true,
					'timePicker'       => false !== strpos($dateFormat, 'h:i A') || false !== strpos($dateFormat, 'H:i'),
					'timePicker24Hour' => false !== strpos($dateFormat, 'H:i'),
					'autoUpdateInput'  => false,
					'locale'           => [
						'format' => $jsFormat
					]
				];

				$dateOptions = apply_filters('rtcl_custom_field_date_options', $dateOptions, $field);
				?>
                <div class="rtcl-form-group ws-item ws-date rtcl-flex rtcl-flex-column">
                    <label class="rtcl-from-label" for="filters_<?php echo esc_attr($cfField); ?>">
						<?php echo esc_html($fieldLabel); ?>
                    </label>
                    <div class="rtcl-search-date">
                        <div class="form-group">
                            <div class="ui-field">
                                <input
                                        id="filters_<?php echo esc_attr($cfField); ?>"
                                        autocomplete="false"
                                        name="filters[<?php echo esc_attr($cfField); ?>]"
                                        type="text"
                                        value="<?php echo esc_attr($value); ?>"
                                        data-options="<?php echo esc_attr( wp_json_encode( $dateOptions ) ); ?>"
                                        class="ui-input form-control rtcl-form-control rtcl-date"
                                        placeholder="<?php echo esc_attr($placeholderText); ?>"
                                        readonly
                                />
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
		?>
    </div>
	<?php
}
?>