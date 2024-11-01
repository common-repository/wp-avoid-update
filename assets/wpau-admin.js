
jQuery(document).ready(function(){
	jQuery( 'table#wpau_select_plugin_table' ).DataTable();
});

jQuery( document.body ).on( 'click', '.wpau_select_plugin', function() {
	if( jQuery( this ).is(':checked') ) {
		wpau_update_plugin_list( jQuery( this ).val(), 'add' );
	}
	else {
		wpau_update_plugin_list( jQuery( this ).val(), 'delete' );
	}
});

function wpau_update_plugin_list( plugin_name, action_to_do) {
	jQuery( 'body' ).addClass( 'wpau_loading' );
	jQuery.ajax({
		url : wpau_admin_script_params.ajax_url,
		type : 'post',
		data : {
			action : 'wpau_update_plugin_list',
			plugin_name : plugin_name,
			action_to_do : action_to_do
		},
		success : function( response ) {
			jQuery( 'body' ).removeClass( 'wpau_loading' );
		}
	});
}	

