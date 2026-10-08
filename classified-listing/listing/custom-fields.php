<?php
/**
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/views/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\ClProperty\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

$group_id = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;

if ( $group_id ) {
	$field_list = Functions::get_cf_ids_by_cfg_id( $group_id );
	$group_title = get_the_title( $group_id );
}
$group_title=$group_title ? $group_title:'';
if ( count( $fields ) ):
	ob_start();
	foreach ( $fields as $field ):
		if ( ! empty( $field_list ) && in_array( $field->ID, $field_list ) ) {
			continue;
		}

		$field = new RtclCFGField( $field->ID ); 
		$value = $field->getFormattedCustomFieldValue( $listing_id );
		$field_title = $field->getLabel();
		$field_icon  = $field->getIconClass();

		if ( ! empty( $value ) ): ?>
            <div class="rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">

				<?php if ( $field_title ) : ?>
                    <div class="field-title">
                        <h5>
                            <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $field_icon ? $field_icon : 'clone'  ) ?>"></i>
                            <span><?php echo esc_html( $field_title ); ?>:</span>
                        </h5>
                    </div>
				<?php endif; ?>

				<?php if ( $field->getType() === 'url' ):
					$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                    <a href="<?php echo esc_url( $value ); ?>"
                       target="<?php echo esc_attr( $field->getTarget() ) ?>"
						<?php echo esc_html( $nofollow ) ?>>
						<?php echo esc_html( $field->getLabel() ) ?>
                    </a>
				<?php else: ?>
                    <div class="text-muted cfp-value">
						
						<?php
						$c_val = explode( ',', $value );
						echo "<ul>";
						foreach ( $c_val as $val ) {
							echo "<li><i class='icon-rt-icon-verified'></i>" . esc_html( $val ) . "</li>";
						}
						echo "</ul>";
						?>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
	<?php endforeach;
	$fieldData = ob_get_clean();
	?>
	<?php
	if ( $fieldData ):
		echo '<div class="overview-list ' . esc_html( strtolower( $group_title ) ) . '">' . wp_kses_post( $fieldData ) . '</div>';
	endif; ?>
	
<?php endif;
