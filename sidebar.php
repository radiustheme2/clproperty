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

?>
<div id="sticky_sidebar" class="<?php Helper::the_sidebar_class(); ?>">
	<?php
   
	if ( RDTheme::$sidebar && is_active_sidebar( RDTheme::$sidebar ) ) { ?>
        <aside class="sidebar-widget main-sidebar-wrapper">
            <?php dynamic_sidebar( RDTheme::$sidebar ); ?>
        </aside>
    <?php
	} elseif( is_active_sidebar( 'sidebar' ) ) { ?>
        <aside class="sidebar-widget main-sidebar-wrapper sidebar__inner">
			<?php dynamic_sidebar( 'sidebar' ); ?>
        </aside>
		<?php
	}
	?>
</div>