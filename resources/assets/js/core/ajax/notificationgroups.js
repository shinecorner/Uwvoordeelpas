notification = {
	group: function(id) {
		$('.coupled.modal')
		  .modal({
		    allowMultiple: false
		  })
		;

		// attach events to buttons
		$('.second.modal')
		  .modal('attach events', '.first.modal .button')
		;

		// show first now
		$('.first.modal')
		  .modal('show')
		;
	}
}
