<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$plugins = get_plugins();
$active_plugins  = get_option( 'active_plugins', array() );
$saved_plugins = get_option( 'wpau_avoid_update_plugins', array() );
?>
<div class="wrap">
	<h1 class="wpau_header"> <?php _e( 'Plugins', WP_AVOID_UPDATE_TEXT_DOMAIN );?> </h1>
	<p><strong> <?php _e( 'Just check the plugin you want to avoid for update. That is all you have to do. We will do the rest. ', WP_AVOID_UPDATE_TEXT_DOMAIN );?> </strong></p>
	<table id="wpau_select_plugin_table" class="wp-list-table widefat striped">
		<thead>
			<tr>
				<th></th>
				<th><?php _e( 'Plugin', WP_AVOID_UPDATE_TEXT_DOMAIN );?></th>
				<th><?php _e( 'Description', WP_AVOID_UPDATE_TEXT_DOMAIN );?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($plugins as $key => $plugin) {
					$active = '';
					if( in_array( $key, $active_plugins ) ) {
						$active = 'class="active"';
					}
					$checked = '';
					if( in_array( $key, $saved_plugins ) ) {
						$checked = 'checked="checked"';
					}
					?>
					<tr <?php echo $active;?>>
						<td <?php echo $active;?>>
							<input type="checkbox" name="wpau_select_plugin" value="<?php echo $key;?>" class="wpau_select_plugin" autocomplete="off" <?php echo $checked;?>>
						</td>
						<td>
							<?php echo $plugin['Name'];?>
						</td>
						<td>
							<?php echo $plugin['Description'];?>
							<br/>
							<?php echo __( 'Version', WP_AVOID_UPDATE_TEXT_DOMAIN ).' '.$plugin['Version'].' | '.__( 'By', WP_AVOID_UPDATE_TEXT_DOMAIN ).' '.$plugin['Author'];?>
						</td>
					</tr>
					<?php
				}
			?>
		</tbody>
	</table>
</div>
<div class="wpau_modal"></div>