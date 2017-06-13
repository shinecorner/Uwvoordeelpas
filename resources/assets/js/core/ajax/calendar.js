times = {
	refresh: function(jsonArray) {
		$.ajax({
			method: 'GET',
			url: baseUrl + 'ajax/available/time',
			data: {
				company: $('input[name="company_id"]').val(),
				persons: $('input[name="persons"]').val(),
				group_res: jsonArray.groupReservation,
				date: jsonArray.date
			}
		})
		  	.done(function(response) {
				var jsonParse = JSON.parse(response); 
				var jsonKeys = Object.keys(jsonParse)[0];

				if (jsonKeys != undefined) {
					if (jsonParse[Object.keys(jsonParse)[0]][$('input[name="company_id"]').val()] != undefined) {
						var availablePersons = jsonParse[Object.keys(jsonParse)[0]][$('input[name="company_id"]').val()]['availablePersons'];
					}

					$.each ($('#personsField .item'), function(key, value) {
						$(this).show();
						
						if (availablePersons != undefined && availablePersons < $(value).data('value')) {
							$(this).hide();
						}
					});

					if (typeof jsonArray.groupReservation != 'undefined') {
						var selectorElement = '#timeField-2';
					} else {
						var selectorElement = '#timeField';
					}

					// Date with times
					if (!$.isEmptyObject(jsonParse) && jsonArray.firstDate == 1) {
						$(selectorElement).removeClass('disabled');
						$(selectorElement + ' .menu').empty();

						for (var i in jsonParse) {
							$(selectorElement + ' .menu').append('<div class="item" data-value="' + i + '">' + i + '</div>');
						}

						if (jsonArray.defaultValue != null) {
							console.log(selectorElement + jsonArray.defaultValue);

							$(selectorElement).dropdown('set value', jsonArray.defaultValue);
							$(selectorElement).dropdown('set text', jsonArray.defaultValue);
						} else {
														console.log(selectorElement + ' a');

							$(selectorElement).dropdown('set value', jsonKeys);
							$(selectorElement).dropdown('set text', jsonKeys);
						}

				        if (typeof errorResEdit !== 'undefined' && errorResEdit == 1) {
				        	$('.ui.selection.dropdown.time').dropdown('set value', oldTimeValue);
							$('.ui.selection.dropdown.time').dropdown('set text', oldTimeValue);
				        }
					}
				}
		  	});
	},
	disabled: function() {
		$('.ui.selection.dropdown.time').dropdown('set text', 'Tijd');
		$('.ui.selection.dropdown.time').dropdown('set value', 0);

		$('#timeField .menu').empty();
		$('#timeField').addClass('disabled');
	}
}

calendar = {
	template: function() {
		return "<div class='clndr-controls'>" +
	            "<div class='clndr-control-button'>" +
	                "<span class='clndr-previous-button'>previous</span>" +
	            "</div>" +
	            "<div class='month'><%= month %> <%= year %></div>" +
	            "<div class='clndr-control-button rightalign'>" +
	                "<span class='clndr-next-button'>next</span>" +
	            "</div>" +
	        "</div>" +
	        "<table class='clndr-table' border='0' cellspacing='0' cellpadding='0'>" +
	            "<thead>" +
	                "<tr class='header-days'>" +
	                "<% for(var i = 0; i < daysOfTheWeek.length; i++) { %>" +
	                    "<td class='header-day'><%= daysOfTheWeek[i] %></td>" +
	                "<% } %>" +
	                "</tr>" +
	            "</thead>" +
	            "<tbody>" +
	            "<% for(var i = 0; i < numberOfRows; i++){ %>" +
	                "<tr>" +
	                "<% for(var j = 0; j < 7; j++){ %>" +
	                "<% var d = j + i * 7; %>" +
	                    "<td data-date='<%= days[d].date.format('YYYY-MM-DD') %>' class='<%= days[d].classes %> <%= days[d].date.format('MMM') %>'>" +
	                        "<div class='day-contents'><%= days[d].day %></div>" +
	                    "</td>" +
	                "<% } %>" +
	                "</tr>" +
	            "<% } %>" +
	            "</tbody>" +
	        "</table>";
	},
	init: function(calendarTemplate) {
		return $('#calendar').clndr({
			weekOffset: 1,
			daysOfTheWeek: ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
			clickEvents: {
	 			click: function (target) {
	 				if (!$(target.element).hasClass('past') && $(target.element).hasClass('event')) {
	 					$.each($('.event'), function(key, value) {
	 						$(this).removeClass('active');
	 						$(this).removeClass('today');
						});

	 					$(target.element).addClass('active');
						calendar.setFirstDate(target.date._i, 1);
					}
				}	
			},
			render: function (data) {
			 	var renderData = _.template(calendarTemplate);
			 	var renderData = renderData(data);
			 	var renderData = $(renderData).clone();
			 	var calendarMonth = moment().month(data.month).format('MMM');

				$('.loader').show();

				var eventsFirst = renderData.find('.' + calendarMonth + '.event:first');

				if ($(eventsFirst).hasClass('adjacent-month') == false && $(eventsFirst).data('date') != undefined) {
					eventsFirst.addClass('active');
					calendar.setFirstDate($(eventsFirst).data('date'), 0);
				}

				$('.loader').hide();

				return renderData;
		    }
		});
	},
	setMonthYear: function(calendarInit, month, year) {
		$('.loader').show();
		$('#personsField').removeClass('disabled');

		calendarInit.setMonth(month - 1);
		calendarInit.setYear(year);
	},
	setFirstDate: function(date, clickEvent) {
		$('input[name="date"]').val(date);
		$('input[name="date_hidden"]').val(date);

		if (typeof activateAjaxTwo !== 'undefined' && activateAjaxTwo == 'reservation-groups' && clickEvent == 0) {
			reservationDatePicker.set('select',
				date, 
				{ format: 'yyyy-mm-dd' }
			);
		}

		times.refresh({
			'date': date,
			'firstDate': 1, 
			'defaultValue': null
		});
	},
	setDates: function(calendarInit) {
		$.ajax({
			url: baseUrl + 'ajax/available/reservation',
			data: {
				persons: $('input[name="persons"]').val(),
				month: $('input[name="month"]').val(),
				year: $('input[name="year"]').val(),
				company:  $('input[name="company_id"]').val()
			},	
			success: function(response) {
				var jsonParse = JSON.parse(response);

				// Add dates to calendar
				calendarInit.setEvents(jsonParse.dates);

				// Disable time field when there is no date available
				if(!$.isEmptyObject(jsonParse)) {
					times.disabled();
				}
				
				$.each($('#personsField .item'), function(key, value) {
					$(this).show();
						
					if (jsonParse.availablePersons <= key) {
						$(this).hide();
					}
				});
			}
		});
	}
 }

 if (typeof activateAjax !== 'undefined') {
	if (activateAjax == 'restaurant') {	
		$('.persons .item').on('click', function() {
			$('input[name="persons"]').val($(this).attr('data-value'));
		});

		var calendarTemplate = calendar.template();
		var calendarInit = calendar.init(calendarTemplate);
	 					
		calendar.setDates(calendarInit);
		
		$('#month .item').on('click', function() {
			$('input[name="month"]').val($(this).attr('data-month'));
			$('input[name="year"]').val($(this).attr('data-year'));

			calendar.setMonthYear(
				calendarInit, 
				parseInt($('input[name="month"]').val()),
				parseInt($('input[name="year"]').val())
			);

			calendar.setDates(calendarInit);
		});

		$('.persons.calendarInput .item').on('click', function() {
			calendar.setDates(calendarInit);
		});
	}
}