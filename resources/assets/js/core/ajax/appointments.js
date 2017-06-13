if (typeof activateAjax !== 'undefined') {
	if (activateAjax == 'appointmentsadmin') {
		var $ajaxDateInput = $('.ajax-datepicker').pickadate({
			formatSubmit: 'yyyy-m-d',
			format: 'd mmmm, yyyy',
			hiddenName: true
		});

		var ajaxDatePicker = $ajaxDateInput.pickadate('picker');

		ajaxDatePicker.on({ 
			set: function(thingSet) {
				if (thingSet.select) {
				    var redirectDate = moment(thingSet.select).year() + '' + zeroPad(parseInt(moment(thingSet.select).month() + 1)) + '' + zeroPad(moment(thingSet.select).date());
				    location.href = $('input[name="redirectUrl"]').val() + '?date=' + redirectDate;
				}
			}
		});
	}
}