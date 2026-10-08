<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Check is Enabled Header Button
	 */
	if ( ! function_exists( 'rttheme_is_header_btn_enabled' ) ) {
		function rttheme_is_header_btn_enabled() {
			$btn_flag = get_theme_mod( 'header_btn' );
			if ( empty( $btn_flag ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * Check is Enabled Header Login Button
	 */
	if ( ! function_exists( 'rttheme_is_login_btn_enabled' ) ) {
		function rttheme_is_login_btn_enabled() {
			$btn_flag = get_theme_mod( 'header_login_icon' );
			if ( empty( $btn_flag ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * Check is Enabled Header Fav Button
	 */
	if ( ! function_exists( 'rttheme_is_fav_btn_enabled' ) ) {
		function rttheme_is_fav_btn_enabled() {
			$btn_flag = get_theme_mod( 'header_fav_icon' );
			if ( empty( $btn_flag ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * Check is Enabled Header Fav Button
	 */
	if ( ! function_exists( 'rttheme_is_compare_btn_enabled' ) ) {
		function rttheme_is_compare_btn_enabled() {
			$btn_flag = get_theme_mod( 'header_compare_icon' );
			if ( empty( $btn_flag ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * Check is Enabled Header Fav Button
	 */
	if ( ! function_exists( 'rttheme_is_cart_btn_enabled' ) ) {
		function rttheme_is_cart_btn_enabled() {
			$btn_flag = get_theme_mod( 'header_cart_icon' );
			if ( empty( $btn_flag ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * Check is Enabled Copyright Area
	 */
	if ( ! function_exists( 'rttheme_is_copyright_area_enabled' ) ) {
		function rttheme_is_copyright_area_enabled() {
			$flag = get_theme_mod( 'copyright_area' );
			if ( empty( $flag ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * Check is Transparent Header
	 */
	if ( ! function_exists( 'rttheme_is_trheader_enable' ) ) {
		function rttheme_is_trheader_enable() {
			$tr_header = get_theme_mod( 'tr_header' );
			if ( empty( $tr_header ) ) {
				return false;
			}

			return true;
		}
	}

	/**
	 * URL sanitization
	 *
	 * @param  string    Input to be sanitized (either a string containing a single url or multiple, separated by commas)
	 *
	 * @return string    Sanitized input
	 */
	if ( ! function_exists( 'rttheme_url_sanitization' ) ) {
		function rttheme_url_sanitization( $input ) {
			if ( strpos( $input, ',' ) !== false ) {
				$input = explode( ',', $input );
			}
			if ( is_array( $input ) ) {
				foreach ( $input as $key => $value ) {
					$input[ $key ] = esc_url_raw( $value );
				}
				$input = implode( ',', $input );
			} else {
				$input = esc_url_raw( $input );
			}

			return $input;
		}
	}

	/**
	 * Switch sanitization
	 *
	 * @param  string        Switch value
	 *
	 * @return integer    Sanitized value
	 */

	if ( ! function_exists( 'rttheme_switch_sanitization' ) ) {
		function rttheme_switch_sanitization( $input ) {
			if ( true === $input ) {
				return 1;
			} else {
				return 0;
			}
		}
	}

	if ( ! function_exists( 'rttheme_textarea_sanitization' ) ) {
        function rttheme_textarea_sanitization( $input ) {
            return $input;
        }
    }

	/**
	 * Radio Button and Select sanitization
	 *
	 * @param  string        Radio Button value
	 *
	 * @return integer    Sanitized value
	 */
	if ( ! function_exists( 'rttheme_radio_sanitization' ) ) {
		function rttheme_radio_sanitization( $input, $setting ) {
			//get the list of possible radio box or select options
			$choices = $setting->manager->get_control( $setting->id )->choices;

			if ( array_key_exists( $input, $choices ) ) {
				return $input;
			} else {
				return $setting->default;
			}
		}
	}

	/**
	 * Integer sanitization
	 *
	 * @param  string        Input value to check
	 *
	 * @return integer    Returned integer value
	 */

	if ( ! function_exists( 'rttheme_sanitize_integer' ) ) {
		function rttheme_sanitize_integer( $input ) {
			return (int) $input;
		}
	}

	/**
	 * Text sanitization
	 *
	 * @param  string    Input to be sanitized (either a string containing a single string or multiple, separated by commas)
	 *
	 * @return string    Sanitized input
	 */
	if ( ! function_exists( 'rttheme_text_sanitization' ) ) {
		function rttheme_text_sanitization( $input ) {
			if ( strpos( $input, ',' ) !== false ) {
				$input = explode( ',', $input );
			}
			if ( is_array( $input ) ) {
				foreach ( $input as $key => $value ) {
					$input[ $key ] = sanitize_text_field( $value );
				}
				$input = implode( ',', $input );
			} else {
				$input = sanitize_text_field( $input );
			}

			return $input;
		}
	}

	/**
	 * Google Font sanitization
	 *
	 * @param  string    JSON string to be sanitized
	 *
	 * @return string    Sanitized input
	 */

	if ( ! function_exists( 'rttheme_google_font_sanitization' ) ) {
		function rttheme_google_font_sanitization( $input ) {
			$val = json_decode( $input, true );
			if ( is_array( $val ) ) {
				foreach ( $val as $key => $value ) {
					$val[ $key ] = sanitize_text_field( $value );
				}
				$input = json_encode( $val );
			} else {
				$input = json_encode( sanitize_text_field( $val ) );
			}

			return $input;
		}
	}

	/**
	 * Array sanitization
	 *
	 * @param  array    Input to be sanitized
	 *
	 * @return array    Sanitized input
	 */
	if ( ! function_exists( 'rttheme_array_sanitization' ) ) {
		function rttheme_array_sanitization( $input ) {
			if ( is_array( $input ) ) {
				foreach ( $input as $key => $value ) {
					$input[ $key ] = sanitize_text_field( $value );
				}
			} else {
				$input = '';
			}

			return $input;
		}
	}

	/**
	 * Only allow values between a certain minimum & maxmium range
	 *
	 * @param  number    Input to be sanitized
	 *
	 * @return number    Sanitized input
	 */
	if ( ! function_exists( 'rttheme_in_range' ) ) {
		function rttheme_in_range( $input, $min, $max ) {
			if ( $input < $min ) {
				$input = $min;
			}
			if ( $input > $max ) {
				$input = $max;
			}

			return $input;
		}
	}

	/**
	 * Date Time sanitization
	 *
	 * @param  string    Date/Time string to be sanitized
	 *
	 * @return string    Sanitized input
	 */

	if ( ! function_exists( 'rttheme_date_time_sanitization' ) ) {
		function rttheme_date_time_sanitization( $input, $setting ) {
			$datetimeformat = 'Y-m-d';
			if ( $setting->manager->get_control( $setting->id )->include_time ) {
				$datetimeformat = 'Y-m-d H:i:s';
			}
			$date = DateTime::createFromFormat( $datetimeformat, $input );
			if ( $date === false ) {
				$date = DateTime::createFromFormat( $datetimeformat, $setting->default );
			}

			return $date->format( $datetimeformat );
		}
	}

	/**
	 * Slider sanitization
	 *
	 * @param  string    Slider value to be sanitized
	 *
	 * @return string    Sanitized input
	 */
	if ( ! function_exists( 'rttheme_range_sanitization' ) ) {
		function rttheme_range_sanitization( $input, $setting ) {
			$attrs = $setting->manager->get_control( $setting->id )->input_attrs;

			$min  = ( isset( $attrs['min'] ) ? $attrs['min'] : $input );
			$max  = ( isset( $attrs['max'] ) ? $attrs['max'] : $input );
			$step = ( isset( $attrs['step'] ) ? $attrs['step'] : 1 );

			$number = floor( $input / $attrs['step'] ) * $attrs['step'];

			return rttheme_in_range( $number, $min, $max );
		}
	}

	/**
	 * File Input sanitization
	 *
	 * @param  string    File Type
	 *
	 * @return string    Mime Type
	 */
	function rttheme_sanitize_file( $file, $setting ) {
		//allowed file types
		$mimes = [
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
		];

		//check file type from file name
		$file_ext = wp_check_filetype( $file, $mimes );

		//if file has a valid mime type return it, otherwise return default
		return ( $file_ext['ext'] ? $file : $setting->default );
	}
}
