function closeBrowser() {
    $('form.edit-changes :input').change(function() {
   		$(window).on('beforeunload');


	    $(window).bind('beforeunload', function() {
	        return '>>>>>Before You Go<<<<<<<< \n Your custom message go here';
	    });
    });


    $('form.edit-changes button').click(function() {
    	$(window).off('beforeunload');
    });
}
