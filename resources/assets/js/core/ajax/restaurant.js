var openRestaurantPopup = function(response) {
    openPrompt({
		'id' : 'cashback',
		'title' : 'Hoe werkt het?',
		'response' : response
	});

	return false;
}

// Open cashback popup when the paramater is active
if(getParameterByName('open_popup_res')) {
	var html = '<a href="' + baseUrl + 'hoe-werkt-het" class="ui button fluid blue">Meer informatie? Klik hier</a>' + $("#restaurantSteps").html();
	openRestaurantPopup(html);
}

$('.changeTableNr').bind('keyup input', function() {
	$.ajax({
		url: baseUrl + 'ajax/reservations/changetablenr',
		method: 'POST',
		data: {
			_token: $('meta[name="_token"]').attr('content'),
			id: $(this).data('id'),
			tablenr: $(this).val()
		},
		success: function(response) {
			console.log('goldfish');
		}	
	});
});