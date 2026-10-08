<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.4
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

</div><!-- #content -->
<?php 

if ( function_exists( '_mc4wp_load_plugin' ) && (RDTheme::$has_newsletter ==1 || RDTheme::$has_newsletter ==='on' )) {
	get_template_part( 'template-parts/rt', 'newsletter' );
}
$footer_style = RDTheme::$footer_style ? RDTheme::$footer_style : 1;
?>

<?php
get_template_part( 'template-parts/footer/footer', $footer_style); ?>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>