<?php
/**
 * Listing Form
 *
 * @var int   $post_id
 * @var array $selectedCat
 * @var array $categories
 * @author    RadiusTheme
 * @package   classified-listing/templates
 * @version   1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="rtcl-yelp-review-category rtcl-post-section">
    <div class="rtcl-post-section-title">
        <h3><i class="rtcl-icon rtcl-icon-tags"></i><?php esc_html_e( "Yelp Nearby Places", "clproperty" ); ?></h3>
    </div>
    <div class="form-group main-label">
        <label><?php esc_html_e( 'Select Category', 'clproperty' ); ?></label>
    </div>
	<?php
	if ( ! empty( $categories ) ) {
		foreach ( $categories as $key => $category ) {
			?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="clproperty_yelp_categories[]" <?php echo in_array( $key, $selectedCat ) ? "checked" : '' ?>
                       id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>">
                <label class="form-check-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $category['title'] ); ?></label>
            </div>
			<?php
		}
	}
	?>
</div>