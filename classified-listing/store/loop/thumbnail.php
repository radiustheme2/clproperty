<?php
use radiustheme\ClProperty\RDTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $store;

?>
<div class="store-thumb">
	<?php if(RDTheme::$options['show_ad_count']){
			$store->the_metas();
		}
	 ?>
	<?php $store->the_logo(); ?>
</div>
