import '../../css/admin/tools.scss';

let action = false;
let args = {};
let items = [];
let items_total = 0;

function handle_items() {
	var data = {
		action: 'eafl_' + action,
		security: eafl_admin.nonce,
		items: JSON.stringify(items),
		args: args,
	};

	jQuery.post(eafl_admin.ajax_url, data, function(out) {
		if (out.success) {
            items = out.data.items_left;
            update_progress_bar();
            
			if(items.length > 0) {
                handle_items();
			} else {
				jQuery('#eafl-tools-finished').show();
			}
		} else {
			window.location = out.data.redirect;
		}
	}, 'json');
}

function update_progress_bar() {
	var percentage = ( 1.0 - ( items.length / items_total ) ) * 100;
	jQuery('#eafl-tools-progress-bar').css('width', percentage + '%');
};

jQuery(document).ready(function($) {
	// Import Process
	if(typeof window.eafl_tools !== 'undefined') {
		action = eafl_tools.action;
		args = eafl_tools.args;
		items = eafl_tools.items;
        items_total = eafl_tools.items.length;
		handle_items();
	}

	// Reset settings
	jQuery('#eafl_tools_reset_settings').on('click', function(e) {
		e.preventDefault();

		if ( confirm( 'Are you sure you want to reset all settings?' ) ) {
			var data = {
				action: 'eafl_reset_settings',
				security: eafl_admin.nonce,
			};
		
			jQuery.post(eafl_admin.ajax_url, data, function(out) {
				if (out.success) {
					window.location = out.data.redirect;
				} else {
					alert( 'Something went wrong.' );
				}
			}, 'json');
		}
	});
});
