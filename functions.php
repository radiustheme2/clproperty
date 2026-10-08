<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $content_width ) ) {
	$content_width = 1240;
}

define( 'CLPROPERTY_BASE_URL',    get_template_directory_uri(). '/' );
define( 'CLPROPERTY_ASSETS_URL',  CLPROPERTY_BASE_URL . 'assets/' );
define( 'CLPROPERTY_CSS_URL',     CLPROPERTY_ASSETS_URL . 'css/' );
define( 'CLPROPERTY_JS_URL',      CLPROPERTY_ASSETS_URL . 'js/' );

class ClProperty_Main {

	public $theme = 'clproperty';
	public $action = 'clproperty_theme_init';

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
		$this->includes();

//		show_admin_bar( false );
	}

	public function load_textdomain() {
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}

	public function includes() {
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/helper.php';
		require_once get_template_directory() . '/inc/includes.php';

		do_action( $this->action );
	}
	
}

new ClProperty_Main;
