$(document).ready(function() {
	$('.newsletter.checkbox').checkbox({
		onUnchecked: function() {
			var guestId = $(this).parent().data('id');
			var thisRow = $(this).parent().parent().parent();

			$.ajax({
				url: baseUrl + 'ajax/newsletter/remove/guest',
				method: 'POST',
				data: {
					_token: $('meta[name="_token"]').attr('content'),
					user_id: $(this).parent().data('id'),
					company_id: $(this).parent().data('company-id'),
					newsletter_id: $(this).parent().data('newsletter-id'),
					no_show: 1
				},
				success: function(response) {
					$(thisRow).addClass('negative');
				}
			});
		},
		onChecked: function() {
			var guestId = $(this).parent().data('id');
			var thisRow = $(this).parent().parent().parent();

			$.ajax({
				url: baseUrl + 'ajax/newsletter/remove/guest',
				method: 'POST',
				data: {
					_token: $('meta[name="_token"]').attr('content'),
					user_id: $(this).parent().data('id'),
					company_id: $(this).parent().data('company-id'),
					newsletter_id: $(this).parent().data('newsletter-id'),
					no_show: 0
				},
				success: function(response) {
					$(thisRow).removeClass('negative');
				}
			});
		}
	});
});