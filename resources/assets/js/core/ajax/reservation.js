 if (typeof activateAjax !== 'undefined') {
	if (activateAjax == 'reservation' || activateAjaxTwo !== 'undefined' && activateAjaxTwo == 'reservation-groups') {
		var $reservationDateInput = $('.reservationDatepicker').pickadate({
			min: new Date(),
			formatSubmit: 'yyyy-m-d',
			format: 'd mmmm, yyyy',
			hiddenName: true
		});

		var reservationDatePicker = $reservationDateInput.pickadate('picker');
		
		reservationDatePicker.set('disable', true);

		// Refresh time dropdown
		reservationDatePicker.on({ 
			open: function(thingSet) {
				var view = reservationDatePicker.get('view');
				$('input[name="setTimeBack"]').val(1);

				reservation.refresh(
					view.month,
					view.year,
					$('input[name="company_id"]').val()
				);
			},
			set: function(thingSet) {
				var view = reservationDatePicker.get('view');

				if (thingSet.highlight) {
			        reservation.refresh(
						view.month,
						view.year,
						$('input[name="company_id"]').val()
					);
			   	} else if(thingSet.select) {
			        var date = new Date(thingSet.select);
			        var formattedDate = date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2)  + '-' + date.getDate();

				    if ($('input[name="setTimeBack"]').val() == 0) {
				    	times.refresh({
				    		'date': formattedDate, 
				    		'firstDate': 1, 
				    		'defaultValue': ($('#reservationForm #timeInput').val() != '' ? $('#reservationForm #timeInput').val() : null),
				    		'groupReservation': $('input[name="group_reservation"]').val()
				    	});
				    } else {
						times.refresh({
				    		'date': formattedDate, 
				    		'firstDate': 1, 
				    		'defaultValue': null,
							'groupReservation': $('input[name="group_reservation"]').val()
				    	});
				    }
			    }
			}
		});

		// Refresh time dropdown
		if (typeof activateAjax !== 'undefined' && activateAjax == 'reservation') {
			reservationDatePicker.set(
				'select',
				$('input[name="date_hidden"]').val(), 
				{ format: 'yyyy-mm-dd' }
			);
		}

		$('.persons .item').on('click', function() {
			$('input[name="persons"]').val($(this).attr('data-value'));
		});

		// Refresh time dropdown
	    $('.persons .item').on('click', function() {
	    	times.refresh({
	    		'date': $('input[name="date_hidden"]').val(), 
	    		'firstDate': 1, 
	    		'defaultValue': null
	    	});
	    });
	}

	if (activateAjax == 'reservationindexadmin') {
		var $ajaxDateInput = $('.ajax-datepicker').pickadate({
		    min: new Date(),
		    formatSubmit: 'yyyy-m-d',
		    format: 'd mmmm, yyyy',
		   	hiddenName: true
		});

		var ajaxDatePicker = $ajaxDateInput.pickadate('picker');
		
		ajaxDatePicker.on({ 
			open: function(thingSet) {
				var view = ajaxDatePicker.get('view');
			},
			set: function(thingSet) {
				var view = ajaxDatePicker.get('view');

				if (thingSet.select) {
				    var redirectDate = moment(thingSet.select).year() + '' + zeroPad(parseInt(moment(thingSet.select).month() + 1)) + '' + zeroPad(moment(thingSet.select).date());
			        location.href = $('input[name="redirectUrl"]').val() + '?date=' + redirectDate;
			    }
			 }
		});
	}

	if (activateAjax == 'reservationadmin') {
		var $ajaxDateInput = $('.ajax-datepicker').pickadate({
		    formatSubmit: 'yyyy-m-d',
		    format: 'd mmmm, yyyy',
		    hiddenName: true
		});

		var ajaxDatePicker = $ajaxDateInput.pickadate('picker');

		ajaxDatePicker.on({ 
			open: function(thingSet) {
				var view = ajaxDatePicker.get('view');
				
				reservation.refresh(
					view.month,
					view.year,
					$('input[name="company"]').val(),
					1
				);
			},
			set: function(thingSet) {
				var view = ajaxDatePicker.get('view');

				if (thingSet.highlight) {	
					reservation.refresh(
						view.month,
						view.year,
						$('input[name="company"]').val(),
						1
					);
			    } else if(thingSet.select) {
				    var redirectDate = moment(thingSet.select).year() + '' + zeroPad(parseInt(moment(thingSet.select).month() + 1)) + '' + zeroPad(moment(thingSet.select).date());
			        location.href = $('input[name="redirectUrl"]').val() + '?date=' + redirectDate;
			    }
			}
		});
	}
}

reservation = {
	refresh: function(month, year, companyId, noDisable) {
		$.ajax({
			method: 'GET',
			url: baseUrl + 'ajax/available/dates',
			data: {
				company: companyId,
				year: year,
				month: month,
				jsdate: 1,
				persons: $('input[name="persons"]').val()
			}
		})
			.done(function(response) {
				var jsonParse = JSON.parse(response);
				var arr = [];

				$.each($("#personsField .item"), function( key, value ) {
					$(this).show();
				
					if(jsonParse.availablePersons <= key) {
						$(this).hide();
					}
				});

				$.each(jsonParse.dates, function(idx, obj) {
					var dateParse = new Date(
						moment(obj.date).year(), 
						moment(obj.date).month(), 
						parseInt(moment(obj.date).date())
					);

					arr.push(dateParse);
				});

				if (typeof noDisable == 'undefined') {
					if (typeof ajaxDatePicker !== 'undefined') {
						ajaxDatePicker.set(
							'disable',
							 arr, 
							 { muted: true }
						);
					} else {

						reservationDatePicker.set(
							'disable',
							 arr, 
							 { muted: true }
						);
					}
				}
		  	});
	}
}

