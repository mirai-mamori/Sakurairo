jQuery(function($) {

	function runAjaxAction(button, action) {
		button = $(button);
		var panel = button.closest('.puc-debug-bar-panel-v5');
		var responseBox = button.closest('td').find('.puc-ajax-response');

		responseBox.text('Processing...').show();
		$.post(
			ajaxurl,
			{
				action  : action,
				uid     : panel.data('uid'),
				_wpnonce: panel.data('nonce')
			},
			function(data) {
				//The response contains HTML that should already be escaped in server-side code.
				//phpcs:ignore WordPressVIPMinimum.JS.HTMLExecutingFunctions.html
				responseBox.html(data);
			},
			'html'
		);
	}

	$('.puc-debug-bar-panel-v5 input[name="puc-check-now-button"]').on('click', function() {
		runAjaxAction(this, 'puc_v5_debug_check_now');
		return false;
	});

	$('.puc-debug-bar-panel-v5 input[name="puc-request-info-button"]').on('click', function() {
		runAjaxAction(this, 'puc_v5_debug_request_info');
		return false;
	});


	// Debug Bar uses the panel class name as part of its link and container IDs. This means we can
	// end up with multiple identical IDs if more than one plugin uses the update checker library.
	// Fix it by replacing the class name with the plugin slug.
	var panels = $('#debug-menu-targets').find('.puc-debug-bar-panel-v5');
	panels.each(function() {
		var panel = $(this);
		var uid = panel.data('uid');
		var target = panel.closest('.debug-menu-target');

		//Change the panel wrapper ID.
		target.attr('id', 'debug-menu-target-puc-' + uid);

		//Change the menu link ID as well and point it at the new target ID.
		$('#debug-bar-menu').find('.puc-debug-menu-link-' + uid)
			.closest('.debug-menu-link')
			.attr('id', 'debug-menu-link-puc-' + uid)
			.attr('href', '#' + target.attr('id'));
	});
});