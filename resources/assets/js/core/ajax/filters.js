$(document).ready(function() {
	$('#filterGuests').on('click', function() {
    	$.ajax({
			url: baseUrl + 'ajax/querystring/guests',
			data: {
				query: document.location.search.length == 1 ? currentUrl.substring(currentUrl.indexOf('?') + 1 ) : '',
				sort: getParameterByName('sort'),
				order: getParameterByName('order'),
				limit: getParameterByName('limit'),
				city: $('#citySelect').val(),
				allergies: $('#allergiesSelect').val(),
				preferences: $('#preferencesSelect').val()
			},
			success: function(response) {
				if (response.length >= 1) {
					window.location = $('#filterGuests').data('url') + '?' + response;
				} else{
					return false;
				}
			}
		});
    });

    $('#filterDayTime').on('click', function() {
    	$.ajax({
			url: baseUrl + 'ajax/querystring/reservations',
			data: {
				query:  document.location.search.length == 1 ? currentUrl.substring(currentUrl.indexOf('?') + 1 ) : '',
				sort: getParameterByName('sort'),
				order: getParameterByName('order'),
				limit: getParameterByName('limit'),
				days: $('#daySelect').val(),
				time: $('#timeSelect').val()
			},
			success: function(response) {
				if (response.length >= 1) {
					window.location = $('#filterDayTime').data('url') + '?' + response;
				} else{
					return false;
				}
			}
		});
    });
});