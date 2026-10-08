<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$header_style=RDTheme::$header_style ? RDTheme::$header_style:1;
?>
    <header id="site-header" class="site-header">
		<div id="header-<?php echo esc_attr($header_style); ?>" class="header-area">
			<?php
			if ( RDTheme::$has_top_bar ) {
				get_template_part( 'template-parts/header/header-top', '1' );
			}
			get_template_part( 'template-parts/header/header', $header_style );
			?>
		</div>
    </header>

	<?php get_template_part( 'template-parts/header/header', 'offscreen' ); ?>