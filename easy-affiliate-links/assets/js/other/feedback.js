var eafl_admin = eafl_admin || {};

eafl_admin.give_feedback = function(answer) {
	var data = {
		action: 'eafl_feedback',
		security: eafl_modal.nonce,
		answer: answer
	};

	jQuery.post(eafl_modal.ajax_url, data);
};

jQuery(document).ready(function($) {
	var feedback_notice = jQuery('.eafl-feedback-notice');

	if (feedback_notice.length > 0) {
		jQuery('#eafl-feedback-stop').on('click', function() {
			eafl_admin.give_feedback('stop');
			feedback_notice.slideUp();
		});

		jQuery('#eafl-feedback-no').on('click', function() {
			eafl_admin.give_feedback('no');
			var message = '<strong>How could we make it better?</strong><br/>';
			message += 'Please send any issues or suggestions you have to <a href="mailto:support@bootstrapped.ventures?subject=Easy%20Affiliate%20Links%20feedback">support@bootstrapped.ventures</a> and we\'ll see what we can do!';
			feedback_notice.html(message);
		});

		jQuery('#eafl-feedback-yes').on('click', function() {
			eafl_admin.give_feedback('yes');
			var message = '<strong>Happy to hear!</strong><br/>';
			message += 'It would be really helpful if you could leave us an honest review over at <a href="https://wordpress.org/support/plugin/easy-affiliate-links/reviews/#new-post" target="_blank">wordpress.org</a><br/>';
			message += 'Suggestions to make the plugin even better are also very welcome at <a href="mailto:support@bootstrapped.ventures?subject=Easy%20Affiliate%20Links%20suggestions">support@bootstrapped.ventures</a>';
			feedback_notice.html(message);
		});
	}
});
