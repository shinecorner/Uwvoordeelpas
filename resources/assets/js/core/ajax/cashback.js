var openCashbackPopup = function(response, urlValue) {
    openPrompt({
		'id' : 'cashback',
		'title' : 'UwVoordeelpas',
		'response' : response
	});

	$('#visitStore').html('<a class="ui blue no-radius button" href="' +  urlValue  + '" target="_blank">Bezoek webwinkel</a>');

	$('.checkbox').checkbox({
	    onChecked: function() {
	    	$.ajax({
				url: baseUrl + 'ajax/cashback/popup',
				method: 'POST',
				data: {
					_token:  $('#token').val()
				},
				success: function(response) {
					var jsonParse = JSON.parse(response);
				}
			});

			$('#cashbackCheckbox').hide('slow');
	    }
	});

	return false;
}

// Open cashback popup when the paramater is active
if(getParameterByName('open_cashbackinfo')) {
	$.ajax({
		url: baseUrl + 'ajax/cashback/info',
		success: function(response) {
			openCashbackPopup(response, $('.cashback.logged-in').attr('href'));
		}
	});
}

// Open cashback popup when you press the visit store button
$('.cashback.logged-in').api({
	url: baseUrl + 'ajax/cashback/info',
	method: 'GET',
	onComplete: function(response) {
		openCashbackPopup(response, $(this).attr('href'));
	}
});

$('.category-search').dropdown();

// Cashback search
$('.category-search').on('change', function() {
	$('.subcategory-search').removeClass('disabled');
	$('.subcategory-search .menu').empty();

	$.ajax({
		url: baseUrl + 'ajax/cashback/subcategories',
		data: {
			id: $('.category-search').dropdown('get item', $('.category-search').dropdown('get value')).attr('data-id')
		}
	})
	.done(function(data) {
		$.each($.parseJSON(data), function(index, item) {
		    $('.subcategory-search .menu')
				 .append($('<div class="item" data-order="' + (index + 1) +'"  data-name="' + this.name + '" data-value="' +  this.slug +'">' + this.name + '</div>'));
		 });
	});
});

 if (typeof activateAjax !== 'undefined') {
	if (activateAjax == 'searchCashback') {
		$.ajax({
			url: baseUrl + 'ajax/cashback/subcategories',
			data: {
				id: $('.category-search').dropdown('get item', $('.category-search').dropdown('get value')).attr('data-id')
			}
		})
		.done(function(data) {
			$('.subcategory-search').removeClass('disabled');
			$('.subcategory-search .menu').empty();

			$.each($.parseJSON(data), function(index, item) {
			    $('.subcategory-search .menu')
					 .append($('<div class="item" data-order="' + (index + 1) +'"  data-name="' + this.name + '" data-value="' +  this.slug +'">' + this.name + '</div>'));
			 });
		});
	}
}