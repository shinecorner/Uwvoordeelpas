var currentUrl = $(location).attr('href');
var getUrl = window.location;

if (document.location.hostname == 'localhost' || document.location.hostname == '127.0.0.1') {
	var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
} else {
	var baseUrl = getUrl .protocol + "//" + getUrl.host + "/";
}

var openPrompt = function(params) { 
	var buttons = {};
	
	if(params.submit != undefined) {
		buttons[params.title] = 1;
	} else {
		buttons[''] = 0; // Disable submit if the param submit is empty
	}

	var promptInfo = {
		state0: {
			title: params.title,
			html: params.response,
			uid: params.id,
			buttons: buttons,
			
			submit: function(e, v, m, f) { 
				e.preventDefault();
			}
		}
	}

	$.prompt(promptInfo);
}

var getMyLocation = function() { 
 	if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var positionUser = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
   			var geocoder = new google.maps.Geocoder();

   			console.log(positionUser);
    		geocoder.geocode({'latLng': positionUser}, function(results, status) {
				$.ajax({
					url: baseUrl + 'ajax/users/regio',
					data: {
						city: results[2]['address_components'][1].long_name
					},
					success: function(response) {					
					}
				});
    		});
        });
    }
}

function zeroPad(n) {
    return (n < 10) ? ('0' + n) : n;
}

function callAjax(restaurantsArray, map) {
	if (restaurantsArray.length > 0) {
	    var nextId = restaurantsArray[0];
	    restaurantsArray.shift();

	    if (typeof nextId != 'undefined') {
			$.ajax({
				url: baseUrl + 'ajax/companies/nearby/company',
				data: {
					address: nextId.address,
					zipcode: nextId.zipcode
				},
				success: function(response) {					
					var companyJson = JSON.parse(response);

					if (typeof companyJson[0] != 'undefined') {
						var latlng = new google.maps.LatLng(companyJson[0].lat, companyJson[0].lng);
								
						var marker = new google.maps.Marker({
							position: latlng,
							map: map,
							icon: restaurantsArray[0].kitchen
						});

						var infowindow = new google.maps.InfoWindow({
							content: '<a href="' + companyJson[0].url + '"><h5>' + companyJson[0].name + '</h5></a>' + companyJson[0].address + '<br>' + companyJson[0].zipcode + ' ' + companyJson[0].city
						});

						marker.addListener('click', function() {
							infowindow.open(map, marker);
						});
					}

					setTimeout(callAjax(restaurantsArray, map), 2000);
				}
			});		
		}	
	}
}

function initMap() {
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10,
		center: {
			lat: 51.441642,
			lng: 5.469722
		}
	});

 	$.ajax({
		url: baseUrl + 'ajax/companies/nearby',
		success: function(response) {
			var jsonParse = JSON.parse(response);

			callAjax(jsonParse, map);
		}
	});
}
	   
function Redirect(url) {
	var ua        = navigator.userAgent.toLowerCase(),
		isIE      = ua.indexOf('msie') !== -1,
		version   = parseInt(ua.substr(4, 2), 10);

	// Internet Explorer 8 and lower
	if(isIE && version < 9) {
	    var link = document.createElement('a');
	    
	    link.href = url;
	    document.body.appendChild(link);
	    link.click();
	}
	else { window.location.href = url; }
}

function getParameterByName(name){
    var regexS = "[\\?&]"+name+"=([^&#]*)", regex = new RegExp(regexS), results = regex.exec(window.location.search);
	 
	if (results == null) {
	    return "";
	} else{
	   return decodeURIComponent(results[1].replace(/\+/g, " "));
	}
}

$(document).ready(function() {
	getMyLocation();

	if ($(window).width() < 769) {
		$('footer .ui.inverted').addClass('accordion');
	}

	$(window).resize(function() {
    	if ($(window).width() < 769) {
			$('footer .ui.inverted').addClass('accordion');
			$('footer .dropdown.icon').show();
		} else {
			$('footer .ui.inverted').removeClass('accordion');
			$('footer .dropdown.icon').hide();
		}
	});

	// Change placeholder text
	$('.searchRedirectCategories').on('change', function (e) {
		$('#affiliateSearch-1').search('destroy');
		$('#affiliateSearch-1').search('clear cache', $('#affiliateSearch-1 input').val());

		$('#affiliateSearch-1').search('hide results');
		$('#affiliateSearch-1 input').val('');
		$('#affiliateSearch-1 .results').remove();

		switch ($('.searchRedirectCategories').dropdown('get value')) {
		    case 'restaurant':
				textReplace = 'Waar wilt u gaan reserveren?';
				buttonReplace = 'Reserveer nu';

				$('#affiliateSearch-1').search({
				    apiSettings: {
				        url: baseUrl + 'ajax/companies/users?q={query}'
				    },
				    fields: {
				        results: 'items',
				        title: 'name',
				        url: 'link'
				    },
				    minCharacters: 2,
				    maxResults: 15,
				    error: {
				        noResults: 'Er zijn geen zoekresultaten gevonden.',
				        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
				    }
				});
		        break;

		    case 'saldo':
				buttonReplace = 'Spaar direct';
				textReplace = 'In welke webshop wilt u een aankoop gaan doen?';

				$('#affiliateSearch-1').search({
				    apiSettings: {
				        url: baseUrl + 'ajax/affiliates?q={query}'
				    },
				    fields: {
				        results: 'items',
				        title: 'name',
				        image: 'image',
				        url: 'link',
				        description: 'commission'
				    },
				    minCharacters: 2,
				    maxResults: 15,
				    error: {
				        noResults: 'Er zijn geen zoekresultaten gevonden.',
				        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
				    }
				});
		        break;

		    case 'faq':
				buttonReplace = 'Zoeken';
				textReplace = 'Wat wilt u ons vragen?';

				$('#affiliateSearch-1').search({
				    apiSettings: {
				        url: baseUrl + 'ajax/faq?q={query}'
				    },
				    fields: {
				        results: 'items',
				        title: 'name',
				        url: 'link',
				    },
				    minCharacters: 2,
				    maxResults: 15,
				    error: {
				        noResults: 'Er zijn geen zoekresultaten gevonden.',
				        serverError: 'Er is een fout opgetreden met het uitvoeren van een query.'
				    }
				});

		        break;
		}

		$('#affiliateSearch-1 input, #searchFull input').attr('placeholder', textReplace);
		$('#sliderSubmitButton').html(buttonReplace);
	});


	// Old browsers
	$.reject({  
        reject: { 
        	msie: 9,
        	unknown: true,
        }, // Reject all renderers for demo  
        header: 'Uw browser is verouderd.', // Header Text  
        paragraph1: 'U maakt waarschijnlijk gebruik van een verouderde browser, waardoor er onderdelen op deze website niet optimaal te zien zijn.', // Paragraph 1  
        paragraph2: 'Installeer een van de volgende webbrowsers',  
        imagePath: baseUrl + '/public/images/',  

        closeLink: 'Sluit scherm', // Message below close window link  
        closeMessage: '' // Message below close window link  
    }); // Customized Text  
  
	$('#sliderImage img.background').responsify();

	$('.card').slice(0, 9).show();
	var showRecommended = $('.recommend .company').slice(0, 5).show();
    var moreNumber = 1;

	showRecommended.removeClass('hidden');

	$('#loadMoreRecommend').on('click', function (e) {
        e.preventDefault();

       	var showRecommended2 = $('.recommend .company:hidden').slice(0, 4).slideDown();

        if ($('.recommend .company:hidden').length == 0) {        	
            $('.load').fadeOut('slow');
            $('#loadMoreRecommend').fadeOut('slow');
        }

        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);

        showRecommended2.removeClass('hidden');
    });

	$('#loadMoreHome').on('click', function (e) {
        e.preventDefault();
        $(".home.card:hidden").slice(0, 4).slideDown();

        if ($(".home.card:hidden").length == 0) {
            $("#load").fadeOut('slow');
        }

        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);
    });

	$('.ui.sticky')
	  .sticky({
	    context: '#example1'
	  })
	;

	$('#flexiselDemo1').flexisel({
	    visibleItems: 10,
	    itemsToScroll: 10,
	    animationSpeed: 400,
	    infinite: false,
	    navigationTargetSelector: null,
	    autoPlay: {
	      	enable: false,
	      	interval: 5000,
	      	pauseOnHover: true
	    },
	    responsiveBreakpoints: { 
	      portrait: { 
	        changePoint:480,
	        visibleItems: 5,
	        itemsToScroll: 1
	      }, 
	        landscape: { 
	        changePoint:640,
	        visibleItems: 5,
	        itemsToScroll: 2
	      },
	        tablet: { 
	        changePoint:768,
	        visibleItems: 5,
	        itemsToScroll: 3
	      }
	    }
	});

	$('#howBlock').flexisel({
	    visibleItems: 4,
	    itemsToScroll: 0,
	    animationSpeed: 400,
	    infinite: false,
	    navigationTargetSelector: null,
	    autoPlay: {
	      	enable: false,
	      	interval: 5000,
	      	pauseOnHover: true
	    },
	    responsiveBreakpoints: { 
	      portrait: { 
	        changePoint:480,
	        visibleItems: 2,
	        itemsToScroll: 1
	      }, 
	        landscape: { 
	        changePoint:640,
	        visibleItems: 2,
	        itemsToScroll: 2
	      },
	        tablet: { 
	        changePoint:768,
	        visibleItems: 2,
	        itemsToScroll: 3
	      }
	    }
	});

	// Newsletter
	$('#guestListNewsletter').on('click', function() {
		$('.guests-mode').show();
		$('.text-mode').hide();
		$('#previewButton').hide();
		$('#editButton').hide();

		var options = {
		  	valueNames: [
		  		'id',
		  		'name',
		  		'company',
		  	],
			item: '<li class="item">' +
				  	'<div class="image"><input class="checkbox user" name="guest[]" type="checkbox" /></div>' +
				  	'<div class="content">' +
				  		'<span class="id" style="display: none;"></span>' + 
				  		'<span class="name"></span>' + 
				  		'<strong class="company"></strong>' + 
				  	'</div>' + 
				   '</li>'
		};

		var userList = new List('users', options);

		// Load Users
		$.ajax({
			method: 'GET',
			url: baseUrl + 'ajax/newsletter/guests',
			data: {
				companies: $('.ui.companies.dropdown') .dropdown('get value')
			}
		})
			.success(function(response) {
				var results = JSON.parse(response);

				if (results.length >= 1) {
					$(results).each(function(key, result) {
						userList.add({
						  	id: result.id,
						  	name: result.name,
						  	company: result.companyName
						});
					});
				} else {
					$('.guests-mode .ui.list').html('Er zijn geen gasten gevonden.');
				}
			});
	});

	$('#affiliateSearchForm .search.icon').on('click', function() {
		$('#affiliateSearchForm').submit();
	});

	$('#previewButton').on('click', function() {
		$('.preview-mode').show();
		$('.preview-content').html(tinyMCE.get('text-editor').getContent());
		$('#previewButton').hide();
		$('.text-mode').hide();
		$('#editButton').show();
	});

	$('#editButton').on('click', function() {
		$('.preview-mode').hide();
		$('#previewButton').show();
		$('.text-mode').show();
		$('#editButton').hide();
	});

	// Notifications
	$('#notificationView').on('click', function() {
		ajaxFramework.openNotificationById(
			$('input[name="id"]').val(), 
			$('input[name="width"]').val(), 
			$('input[name="height"]').val(),
			encodeURIComponent(tinymce.get('text').getContent())
		);
	});

	$('#typeInvoice').on('change', function() {
		switch($(this).val()) {
		    case 'products':
		   		 $('.periodDropdown').dropdown('restore defaults');
		   		 $('.periodDropdown .menu').prepend('<div class="item" data-value="0">Eenmalig</div>');

				$('#products').show();

		        break;
		    case 'reservation':
		        $('#products').hide();
		        $('#productsMessage').hide();
		        $('#periodField .menu .item[data-value="0"]').hide();

				$('.periodDropdown').dropdown('set text', $('#periodField .menu .item[data-value="7"]').html());
		        break;
		    default:
		}
	});

	// Search modal

	var content = $('#searchFull').html();
	var contentInput = $('#searchFull #searchInput').clone();

	$('.ui.modal .header').html('Zoeken');

	$('.ui.modal .content').html(content);
	$('.search-full-open, #search-open').on('click', function() {
		$('.ui.modal').modal('show');

		$('.searchRedirectCategories2 select').change(function() {
			switch($('.searchRedirectCategories2').dropdown('get value')[1]) {
			    case 'restaurant':
					textReplace = 'Waar wilt u gaan reserveren?';
					
			        break;

			    case 'saldo':
					textReplace = 'In welke webshop wilt u een aankoop gaan doen?';
			        break;

			    case 'faq':
					textReplace = 'Wat wilt u ons vragen?';
			        break;
			}
			
			contentInput.find('input').attr('placeholder', textReplace);
			$('.ui.modal .content #searchInput').html(contentInput);


		});
	});

	/* Restaurant */
	$('#moreMap').click(function() {
		$('#mapArrow').toggleClass('down up');
		$('#map').animate({
			height: $('#mapArrow').hasClass('up') ? 400 : 290 + 'px'
		}, 'slow');
	});

	/* Footer */
	$('footer .ui.floating.basic.button').mouseenter(function() {
	 	$(this).transition('pulse');
	});
	
	var index = currentUrl.indexOf("open-menu");
	if(index != -1) {
	    $('.ui.sidebar').sidebar('toggle');
	}

	$('.ui.normal.dropdown').dropdown({
	    useLabels: true,
	    fullTextSearch: true
	});

	$('.ui.special.dropdown').dropdown({
	    useLabels: false
	});

	$('.ui.normal.categories.dropdown').dropdown({
	    allowCategorySelection: true
	});

	$('.ui.bread.dropdown').dropdown({
	    on: 'hover'
	});

	$('.ui.accordion').accordion();
	$('select.multipleSelect').select({
		maxOptionsInLabel: 1
	});

	// Datepicker
   	var $datepickerInput = $('.datepicker').pickadate({
		min: new Date(),
		formatSubmit: 'yyyy-mm-dd',
		format: 'd mmmm, yyyy',
		hiddenName: true,
    	selectYears: true,
    	selectMonths: true
	});
	
	var datepicker = $datepickerInput.pickadate('picker');

    $('.timepicker').pickatime({
        format: 'H:i',
        formatLabel: 'H:i',
        formatSubmit: 'H:i',
        interval: 15,
        clear: 'verwijderen'
    });

    $('.timepicker2').pickatime({
        format: 'H:i',
        formatLabel: 'H:i',
        formatSubmit: 'H:i',
        interval: 15,
        clear: 'verwijderen',
        min: [8,00],
    });
    
	$('.green.label, .thumb-discount-label').popup({
		position: 'top right',
		hoverable: true
	});

	$('.tool.hover').popup({
		position: 'bottom center',
		hoverable: true
	});

	$('.icon.link').popup({ position: 'bottom center'});
	$('.loginAs').popup({ position: 'bottom center'});
	$('img').popup({ position: 'right center'});

	$('.slideshow').bxSlider({
	  	mode: 'fade',
	  	pagerCustom: '#bx-pager'
	});

	$('.ui.top.attached.tabular.menu .item').tab();

    // Rating
    $('.rating').rating({ initialRating: 2, maxRating: 10 });
	$('.no-rating').rating('disable');

    $('#decor').click(function() { $('input[name="decor"]').val($('#decor').rating('get rating')); });
    $('#food').click(function() { $('input[name="food"]').val($('#food').rating('get rating')); });
    $('#service').click(function() { $('input[name="service"]').val($('#service').rating('get rating')); });
	$('.ui.checkbox').checkbox();

	$('.ui.master.checkbox').checkbox({
	    onChecked: function() {
	      	var $childCheckbox  = $(this).closest('.ui.child.checkbox').siblings('.list').find('.checkbox');
	      	$('.ui.child.checkbox').checkbox('check');
	    },
	    onUnchecked: function() {
	      	var $childCheckbox  = $(this).closest('.ui.child.checkbox').siblings('.list').find('.checkbox');
	      	$('.ui.child.checkbox').checkbox('uncheck');
	    }
  	});

	$('#typePage').change(function() {
		$('#optionOne').css('display', 'none');
		$('#optionTwo').css('display', 'none');
		$('#optionThree').css('display', 'none');
		$('#optionFour').css('display', 'none');
		$('.filter.toolbar').css('display', 'none');

		if($(this).val() == 1) {
			$('#optionOne').css('display', 'block');
		} else if($(this).val() == 2) {
			window.location = baseUrl + 'news';
		} else if($(this).val() == 3) {
			$('#optionThree').css('display', 'block');
		} else if($(this).val() == 4) {
			window.location = baseUrl + 'tegoed-sparen';
		} else if($(this).val() == 5) {
			window.location = baseUrl + 'faq';
		}
	});

	$('#typePageRestaurant').change(function() {
		$('.ui.bottom.attached.tab').each(function( index ) {
  			$(this).removeClass('active');
  		});

		if($(this).val() == 1) {
			$('#restaurantMenu').addClass('active');
		} else if($(this).val() == 2) {
			$('#restaurantAbout').addClass('active');
		} else if($(this).val() == 3) {
			$('#restaurantDetails').addClass('active');
		} else if($(this).val() == 4) {
			$('#restaurantContact').addClass('active');
		} else if($(this).val() == 5) {
			$('#restaurantNews').addClass('active');
		}
	});

	$('#typePageRestaurantTwo').change(function() {
		$('#optionOne').css('display', 'none');
		$('#optionTwo').css('display', 'none');

		if($(this).val() == 1) {
			$('#optionOne').css('display', 'block');
		} else if($(this).val() == 2) {
			$('#optionTwo').css('display', 'block');
		} 
	});

	// Search
	if(currentUrl.indexOf('mobilefilter') >= 1) {
	 	$('.filter.toolbar').show();
	}

	$('.display.filter').click(function() {
		if($('#typePage').val() != 1) {
			$('.filter.toolbar').hide()
		} else {
			$('.filter.toolbar').show();
			$(document.body).animate({
			    'scrollTop': $('.filter.toolbar').offset().top
			}, 4000);
		}
		return false;
	});

 //  	$('#search-open').click(function() {
	// 	$('.search.form').show();
	// 	$('#search-open').hide();
	// 	return false;
	// });

 //  	$('.search-openup').click(function() {
	// 	$('.search.form').show();
	// 	$('#search-open').hide();
	// 	return false;
	// });

  	$('#siteSearch').click(function() {
		$('.head.search.form').submit();
	});

  	// Sidebar
	$('.close.bar').click(function() {
		$('.ui.sidebar').sidebar('toggle');
	});

	$('#contactOpen').click(function() {
		Tawk_API.showWidget();
	});

	$('.sidebar.open').click(function() {
		Tawk_API.hideWidget();

		$('.ui.sidebar').sidebar({	
	      	transition: 'push',
	      	dimPage: false, 
	     	closable: true,
	     	onHide: function() {
				Tawk_API.showWidget();
	     	},
	     	onShow: function() {

	     	}
	    });	

		$('.ui.sidebar').sidebar('toggle');
	});

	$('#cityRedirect').change(function() {
	  	if(currentUrl.indexOf("city") == -1) {
    		currentUrl += currentUrl.indexOf("?") === -1 ? "?" : "&";
    		window.location = currentUrl + 'city=' + $(this).val();
    	} else {
		  	window.location = currentUrl.replace(/(city=)[^\&]+/, '$1' + $(this).val());
		}
	});

	$('#newsletterRedirect').change(function() {
	  	if(currentUrl.indexOf("newsletter") == -1) {
    		currentUrl += currentUrl.indexOf("?") === -1 ? "?" : "&";
    		window.location = currentUrl + 'newsletter=' + $(this).val();
    	} else {
		  	window.location = currentUrl.replace(/(newsletter=)[^\&]+/, '$1' + $(this).val());
		}
	});

	$('#formList table tr th').click(function() {
		if($(this).attr('class') != 'disabled' && $(this).attr('data-slug') != 'disabled') {
			if(currentUrl.indexOf('order') == -1) {
				// When the parametr doesn't exists.
			  	var orderBy = $(this).attr('data-column-order') == 'desc' ? 'asc' : 'desc';
	    	} else {
			  	if(
			  		currentUrl.indexOf('sort') != $(this).attr('data-slug')
			  	 && getParameterByName('order') == 'asc'
			  	) {
			  		var orderBy = 'desc';
			  	} else {
			  		var orderBy = 'asc';
			  	}
			}

		  	if(currentUrl.indexOf('sort') == -1) {
	    		currentUrl += currentUrl.indexOf("?") === -1 ? "?" : "&";
	    		window.location = currentUrl + 'sort=' + $(this).attr('data-slug') + '&order=' + orderBy;
	    	} else {
			  	window.location = currentUrl.replace(/(sort=)[^\&]+/, '$1' + $(this).attr('data-slug')).replace(/(order=)[^\&]+/, '$1' + orderBy); 
			}
		}
	});

	$('#filteRedirect').change(function() { 
    	if(currentUrl.indexOf("filter") == -1) {
    		currentUrl += currentUrl.indexOf("?") === -1 ? "?" : "&";
    		window.location = currentUrl + 'filter=' + $(this).val();
    	} else {
		  	window.location = currentUrl.replace(/(filter=)[^\&]+/, '$1' + $(this).val());
		}
	});

	// Reservations
	$('.guestClick').on('click', function() {
		$form = $('#reservationForm');

		// check if the form id exists
		if ($form[0]) {
			$(this).attr('data-redirect',  $('input[name="reservation_url"]').val() + '?date=' + $('input[name="date"]').val().replace(/-/g, '') + '&time=' + $('input[name="time"]').val().replace(':', '') + '&persons=' + $('input[name="persons"]').val() + ($('input[name="iframe"]').val() == 1 ?  '&iframe=1' : ''));
		}
	});

	$('#guestComplete .item').on('click', function() {
		$('input[name="phone"]').val($(this).attr('data-phone'));	
		$('input[name="email"]').val($(this).attr('data-email'));	
		$('input[name="name"]').val($(this).attr('data-name'));	
	});

	//Slider datepicker
   	var $datepickerInputSlider = $('.slider .pickadate').pickadate({
		min: new Date(),
		formatSubmit: 'yyyy-m-d',
		format: 'd mmmm, yyyy',
		hiddenName: true
	});
	
	var datepickerSlider = $datepickerInputSlider.pickadate('picker');
	var splitCurrentUrl = currentUrl.replace('https://', '').split('/');
	
	if (typeof datepickerSlider !== 'undefined') {
		datepickerSlider.on({
			set: function(dateSelect) {
				var selectedDate = moment(dateSelect.select).format('YYYY-MM-DD');

				$.each($('.slider #timeSliderField .item'), function(key, value) {
					$(this).show();
					$(this).addClass('available');

					// check if the date is in the past
					var day = selectedDate + ' ' + $(this).data('value');
					
					if (day < moment().format('YYYY-MM-DD HH:mm')) {
						$(this).hide();
						$(this).removeClass('available');
					}
				});

				var firstValue = $('.slider #timeSliderField .item.available:first').data('value');
				if (typeof searchPage !== 'undefined' && getParameterByName('sltime') != '') {
					$('.slider #timeSliderField').dropdown('set value', getParameterByName('sltime'));
					$('.slider #timeSliderField').dropdown('set text', getParameterByName('sltime'));
				} else {
					$('.slider #timeSliderField').dropdown('set value', firstValue);
					$('.slider #timeSliderField').dropdown('set text', firstValue);
				}
			}
		});

		if (typeof searchPage !== 'undefined') {
			var dateNow = (getParameterByName('date') != '' ? getParameterByName('date') : new Date());

			datepickerSlider.set('select', moment(dateNow).format('YYYY-MM-DD'), { format: 'yyyy-mm-dd' });
		} else {
			datepickerSlider.set('select', new Date());
		}
	}

	// Birthday datepicker
    var $bdayInput = $('.bdy-datepicker').pickadate({
    	formatSubmit: 'yyyy-m-d',
    	format: 'd mmmm, yyyy',
    	hiddenName: true,
    	min: new Date(1920,1,01),
  		max: new Date(),
    	selectYears: 100,
    	selectMonths: true,
    });

    var bdyDatePicker = $bdayInput.pickadate('picker');

    var $ajaxDateInput = $('.ajax-datepicker').pickadate({
    	formatSubmit: 'yyyy-m-d',
    	format: 'd mmmm, yyyy',
    	hiddenName: true,
    });

    var ajaxDatePicker = $ajaxDateInput.pickadate('picker');

 	if (typeof activateAjax !== 'undefined') {
		if (activateAjax == 'callcenteradmin') {
			ajaxDatePicker.on({
				set: function(thingSet) {
					var view = ajaxDatePicker.get('view');

					if (thingSet.select) {
				        var redirectDate = moment(thingSet.select).year() + '' + (moment(thingSet.select).month() < 10 ? 0 : '') + parseInt(moment(thingSet.select).month() + 1) + '' + (moment(thingSet.select).date() < 10 ? 0 : '') + moment(thingSet.select).date();
				        location.href = $('input[name="redirectUrl"]').val() + '?date=' + redirectDate;
				    }
				}
			});
		}
	}

	var owlWrap = $('.owl-wrapper');

    // checking if the dom element exists
    if (owlWrap.length > 0) {
    	var startTime = new Date(2016, 2, 20, 7, 45);
		var endTime = new Date(2016, 2, 20, 23, 45);

		var slidesArray = {};
		var i = -1;

		while (startTime < endTime) {
		    i++;
		    startTime.setMinutes(startTime.getMinutes() + 15);

		    if (endTime >= startTime) {
		        var time = (startTime.getHours() < 10 ? '0' : '') + startTime.getHours() + (startTime.getMinutes() < 10 ? '0' : '') + startTime.getMinutes();
		        slidesArray[time] = i;
		    }
		}

        // check if plugin is loaded
        if (jQuery().owlCarousel) {
            owlWrap.each(function() {
            	var positionTop = $(this).parent().parent().position().top - 600;
				
				var carousel = $(this).find('.owl-carousel'),
		            navigation = $(this).find('.customNavigation'),
		            nextBtn = navigation.find('.next'),
		            prevBtn = navigation.find('.prev'),
		            playBtn = navigation.find('.play'),
		            stopBtn = navigation.find('.stop');

		        var wrapper = $(this);

		        wrapper.addClass('active');

		        var i = 0;

				if (currentUrl.indexOf('sltime') >= 0) {
	         		if (!wrapper.parent().parent().hasClass('recommended')) {
		         		if (wrapper.find('.time-available[data-time="' + $('#timeSliderField input').val().replace(':', '') + '"]').length == 0) {
							i++;
						}

						if (i == 1) {
							sweetAlert("Helaas", 'Er zijn geen resultaten gevonden met het opgegeven tijdstip.', "error");
						}
					}
				}

            	$(window).scroll(function() {
            		 if ($(document).scrollTop() > positionTop) {
            		 	if (wrapper.hasClass('active')) {
				            carousel.owlCarousel({
			                    items: 5,
			                    lazyLoad : true,
			                    navigation : false,
			                    stopOnHover : true,
			                    autoPlay : false,
			                    responsiveBaseWidth: window,
			                    itemsMobile: [479,5]
				            });

				            if (currentUrl.indexOf('sltime') >= 0 && $('#time-' + index).hasClass('firstTime') == false) {
			            		var activeSlide = slidesArray[$('#timeSliderField input').val().replace(':', '')];

	         					if (wrapper.find('.time-available[data-time="' + $('#timeSliderField input').val().replace(':', '') + '"]').length == 1) {
									carousel.trigger('owl.goTo', activeSlide);
								} else {
									carousel.trigger('owl.goTo', slidesArray[wrapper.find('.available-1').data('time')]);
								}
			               	} else if (wrapper.find('.available-1').data('time') != undefined) {
			                	carousel.trigger('owl.goTo', slidesArray[wrapper.find('.available-1').data('time')]);
			                }

							wrapper.removeClass('active');
			        	}
			        }
            	});

	            // Custom Navigation Events
	            prevBtn.click(function(){
	                carousel.trigger('owl.prev');
	            });

	            nextBtn.click(function(){
	            	carousel.trigger('owl.next');
	            });
            });
        };
    };
});