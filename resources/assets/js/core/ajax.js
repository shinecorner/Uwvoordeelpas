register('registerButton', 'tegoed-sparen');
register('registerButton2', 'tegoed-sparen');
register('registerButton3', 'tegoed-sparen');
register('registerButton4', 'tegoed-sparen');

var recoverPassword = function(response) { 
	openPrompt({
		'id' : 'forgot-password',
		'title' : 'Wachtwoord vergeten',
		'submit' : 'Wachtwoord vergeten',
		'response' : response
	});

	$('.jqibuttons').api({
		url: baseUrl + 'forgot-password',
		method: 'POST',
		serializeForm: true,
		onComplete: function(response) {
			var output = '<div class="header">Er zijn fouten opgetreden</div><ul class="list">';

			for(var i in response) {
				output += '<li>' + response[i] + '</li>';
			}

			output += '</ul>';

			if(response.success == 1) {
				$('.error.forgot-password').hide(); // Hide error message
				$('.jqimessage .field').hide(); // Hide all fields
				$('.jqibuttons').hide(); // Hide submit button
				
				$('.success.forgot-password').show();
				$('.success.forgot-password').html('Er is een mail naar uw e-mailadres gestuurd voor het aanvragen van een nieuw wachtwoord.');	
								
			} else {
				$('.error.forgot-password').show();
				$('.errors.forgot-password').html(output);
			}
		}
	});	
}

function register(identifier, redirectTo) {
	$(document).ready(function() {
		$('#' + identifier).api({	
			url: baseUrl + 'register',
			onComplete: function(response) {
				openPrompt({
					'id' : 'register',
					'title' : 'Aanmelden',
					'response' : response
				});

				$('.next').api({
					url: baseUrl + 'register',
					method: 'POST',
					serializeForm: true,
					onComplete: function(response) {
						var output = '<ul class="list">';
							
						for(var i in response) {
							output += '<li>' + response[i] + '</li>';
						}

						output += '</ul>';

						if(response.success == 1) {
							Redirect(redirectTo);
						} else {
							$('.success.register').css('display', 'none');
							$('.errors.register').transition('pulse');
							$('.errors.register').html(output);
						}
					}
				});	
			}
		});
	});
}

ajaxFramework = {
	refreshServices: function(menu, number) {
		var number = number + 1;

		$.ajax({
			url: baseUrl + 'ajax/services',
			data: {
				company: $('#getServicesCompany').val()
			},
			success: function(response) {
				var jsonParse = JSON.parse(response);

				if (jsonParse.error != undefined) {
					$('#products').hide();
					$('#productsMessage').fadeIn('slow');

					$('#productsMessage').html(jsonParse.error);
				} else {
					$('#productsMessage').hide();
					$('#products').fadeIn('slow');

					var findMenu = menu.find('select[name="products[' + number + '][service]"]');

					$(findMenu).dropdown('set text', 'Kies een dienst');
					$(findMenu).parent().find('.menu').empty();

					$.each(jsonParse, function(key, value) {
						$(findMenu).parent().find('.menu').append('<div class="item" data-tax="' + value.tax  + '" data-price="' + value.price  + '" data-content="' + value.content  + '" data-value="' + value.name  + '">' + value.name + '</div>');
					});
				}
			}
		});
	},
	refreshPrices: function(groupItem) {
 		var elem = $(groupItem);
		var parents = $(groupItem).parent().parent().parent();
		var amountInput = parents.find('input[name="products[' + parents.index() + '][amount]"]');
		var priceInput = parents.find('input[name="products[' + parents.index() + '][price]"]');
		var totalInput = parents.find('input[name="products[' + parents.index() + '][total]"]');

		// Save current value of element
	   	elem.data('oldVal', elem.val());

	   	// Look for changes in the value
	   	elem.bind("propertychange change keyup input paste", function(event) {
	      	// If value has changed...
	      	if (elem.data('oldVal') != elem.val()) {
		       totalInput.val(priceInput.val() * amountInput.val());
		       // Updated stored value
		       elem.data('oldVal', elem.val());
		    }
	   	});
	},
	refreshAmounts: function(groupItem) {
	   	var elem = $(groupItem);
		var parents = $(groupItem).parent().parent();
		var amountInput = parents.find('input[name="products[' + parents.index() + '][amount]"]');
		var priceInput = parents.find('input[name="products[' + parents.index() + '][price]"]');
		var totalInput = parents.find('input[name="products[' + parents.index() + '][total]"]');

		// Save current value of element
	   	elem.data('oldVal', elem.val());

	   	// Look for changes in the value
	   	elem.bind("propertychange change keyup input paste", function(event){
	      	// If value has changed...
	      	if (elem.data('oldVal') != elem.val()) {
		       totalInput.val(priceInput.val() * amountInput.val());
		       // Updated stored value
		       elem.data('oldVal', elem.val());
		    }
	   	});
	},
	openNotification: function() {
		$.ajax({
			url: baseUrl + 'ajax/notifications',
			success: function(response) {
				var jsonParse = $.parseJSON(response);

				if (Cookies.get('notification-modal-' + jsonParse.id) != 1 && jsonParse.succes == 1) {
					$('.ui.modal .header').hide();
					$('.ui.modal').addClass('tiny');
					$('.ui.modal').addClass('notification');
					$('.ui.modal').addClass('long');

					$('.ui.modal')
						.modal({
							blurring: true
						})
						.modal('setting', 'transition', 'vertical flip')
						.modal('show')
					;

					$('.ui.modal .content').html(jsonParse.text);
					
					$('.ui.notification.modal .close.icon').on('click', function() {
						Cookies.set('notification-modal-' + jsonParse.id, 1, { expires: 365 });
					});
				}
			},
			error: function(response) {
			}
		});
	},
	openNotificationById: function(id, width, height, content) {
		$.ajax({
			url: baseUrl + 'ajax/notifications?id=' + id + '&height=' + height + '&width=' + width + '&content=' + content,
			success: function(response) {
				var jsonParse = $.parseJSON(response);

				$('.ui.modal .header').hide();
				$('.ui.modal').addClass('tiny');
				$('.ui.modal').addClass('notification');
				$('.ui.modal').addClass('long');

				$('.ui.modal').modal({
						blurring: true
					})
					.modal('setting', 'transition', 'vertical flip')
					.modal('show')
				;

				$('.ui.modal .content').html(jsonParse.text);
			}
		});
	}
}

$(document).ready(function() {
 	if (typeof pageId !== 'undefined') {
		if (pageId == 'appointmentCreate') {
			$.ajax({
				url: baseUrl + 'ajax/appointments/companies',
				method: 'GET',
				data: {
					'id': $('#companySelectAppointment').val(),
				},
				success: function(response) {
					var jsonParse = JSON.parse(response);
					
					$('#appointmentEmail').val(jsonParse[0].email);
					$('#appointmentContactName').val(jsonParse[0].contact_name);
					$('#appointmentComment').val(jsonParse[0].contact_name);
					$('#appointmentPlace').val(jsonParse[0].address + ', ' + jsonParse[0].zipcode + ', ' + jsonParse[0].city);
				}
			});

			$('#companySelectAppointment').on('change', function() {
				$.ajax({
					url: baseUrl + 'ajax/appointments/companies',
					method: 'GET',
					data: {
						'id': $(this).val(),
					},
					success: function(response) {
						var jsonParse = JSON.parse(response); 
						
						$('#appointmentContactName').val(jsonParse[0].contact_name);
						$('#appointmentEmail').val(jsonParse[0].email);
						$('#appointmentComment').val(jsonParse[0].contact_name);
						$('#appointmentPlace').val(jsonParse[0].address + ', ' + jsonParse[0].zipcode + ', ' + jsonParse[0].city);
					}
				});
			});
		}
	}

	if ($('body').is('.index'))  {
		ajaxFramework.openNotification();
	}

	$('#transferButton').on('click', function() {
		$('.ui.modal').modal('show');
		$('.ui.modal .header').html('Contract');
		$('.ui.modal .content').html($('#transfer').html());

		$('#transer').show();

		$('.ui.normal.dropdown').dropdown({
		    useLabels: true
		});

		$('.ui.transfer.dropdown').on('click', function() {
			$('button[name="submitTransfer"]').removeClass('disabled');
		});

		$('button[name="submitTransfer"]').on('click', function() {
			$.ajax({
				url: baseUrl + 'ajax/affiliates/transfer',
				method: 'POST',
				data: {
					'_token': $('#transfer input[name="_token"]').val(),
					affiliateIds: $('#idArray').val(),
					categoryId: $('.ui.transfer.dropdown').dropdown('get value')
				},
				complete: function(response) {
					$('.ui.modal .content').html('De desbtreffende categorie&euml;n zijn succesvol verplaatst.');
				}
			});
		});
	});

	$('.documents').on('click', function() {
		$('.ui.modal').modal('show');
		$('.ui.modal .header').html('Contract');

		$.ajax({
			url: baseUrl + 'ajax/companies/documents',
			data: {
				slug: $(this).data('slug')
			},
			complete: function(response) {
				$('.ui.modal .content').html(response.responseText);
			}
		});
	});

	/* Services */
	$('#getServicesCompany').on('change', function() {
		ajaxFramework.refreshServices($('#products'), -1);
	});

	var i = -1;
	$('#products').on('click', '.r-btnAdd', function() {
		i++;
		ajaxFramework.refreshServices($('#products'), i);

		$('.productPrice').each(function() {
		   	ajaxFramework.refreshPrices($(this));
		});

		$('.productAmount').each(function() {
		 	ajaxFramework.refreshAmounts($(this));
		});
	});

	$('#products').on('click', '.menu .item', function() {
		var parents = $(this).parent().parent().parent().parent();
		var amountInput = parents.find('input[name="products[' + parents.index() + '][amount]"]');
		var priceInput = parents.find('input[name="products[' + parents.index() + '][price]"]');
		var taxInput = parents.find('input[name="products[' + parents.index() + '][tax]"]');
		var totalInput = parents.find('input[name="products[' + parents.index() + '][total]"]');
		var descriptionInput = parents.find('input[name="products[' + parents.index() + '][description]"]');

		priceInput.val($(this).data('price'));
		taxInput.val($(this).data('tax'));
		totalInput.val($(this).data('price') * amountInput.val());
		descriptionInput.val($(this).data('content'));
	});

	$('.productAmount').each(function() {
		ajaxFramework.refreshAmounts($(this));
	});

	$('.productPrice').each(function() {
	   	ajaxFramework.refreshPrices($(this));
	});

	/* FAQ */
	$('#getCategories').on('change', function() {
    	$.ajax({
			url: baseUrl + 'ajax/faq/subcategories',
			data: {
				category: $(this).val()
			},
			success: function(response) {
				var json = jQuery.parseJSON(response);

				$('#getSubCategories').dropdown('set text', 'Kies een subcategorie');

				$('#getSubCategories').removeClass('disabled');
				$('#getSubCategories .menu').empty();

				$('#getSubCategories .menu').append('<div class="item" data-value="">Geen</div>');

				$.each(json, function(key, value) {
					$('#getSubCategories .menu').append('<div class="item" data-value="' + value.id  + '">' + value.name + '</div>');
				});
			}
		});
    });
    
	$('#faq .title').on('click', function() {
    	$.ajax({
			url: baseUrl + 'ajax/faqs',
			data: {
				id: $(this).attr('id')
			}
		});
    });

	// Checkbox active mailtemplate
	$('.ui.checkbox.activeChange').checkbox({
		onUnchecked: function() {
			$.ajax({
				url: baseUrl + 'ajax/mailtemplates',
				method: 'POST',
				data: {
					id: $(this).parent().data('id'),
					is_active: 1,
					_token: $('meta[name="_token"]').attr('content'),
				}
			});
		},
		onChecked: function() {
			$.ajax({
				url: baseUrl + 'ajax/mailtemplates',
				method: 'POST',
				data: {
					id: $(this).parent().data('id'),
					is_active: 0,
					_token: $('meta[name="_token"]').attr('content'),
				}
			});
		}
	});

	var redirectUrlDefault = baseUrl + 'tegoed-sparen';

	$('.login[data-redirect]').on('click', function() {
		redirectUrlDefault = $(this).data('redirect');
	});

	/* Login */
	$('.login').api({
		url: baseUrl + 'login',
		onSuccess: function(response) {
			var redirectUrl = $(this).data('redirect');
			var type = $(this).data('type');
			var typeRedirect = $(this).data('type-redirect');

			openPrompt({
				'id' : 'login',
				'title' : 'Inloggen',
				'submit' : 'Inloggen',
				'response' : response.view
			});

			$('#facebookButton').click(function() {
				$form = $('#reservationForm');
				$encode = $('input[name="encode_url"]').val();

				// check if the form id exists
				if ($(this).attr('id') == 'submitField') {
					if ($form[0]) {
						if ($encode !== undefined) {
							var reservationUrl = encodeURIComponent($('input[name="reservation_url"]').val() + '?date=' + $('input[name="date_hidden"]').val().replace(/-/g, '') + '&time=' + $('input[name="time"]').val().replace(':', '') + '&persons=' + $('input[name="persons"]').val() + ($('input[name="iframe"]').val() == 1 ?  '&iframe=1' : ''));
						} else {
							var reservationUrl = encodeURIComponent($('input[name="reservation_url"]').val() + '?date=' + $('input[name="date"]').val().replace(/-/g, '') + '&time=' + $('input[name="time"]').val().replace(':', '') + '&persons=' + $('input[name="persons"]').val() + ($('input[name="iframe"]').val() == 1 ?  '&iframe=1' : ''));
						}
					}
				}
					
				var redirectdiscount = $('#discountCardButton').data('redirect');
				var redirectcashback = $(this).find('.cashback').data('redirect');

				if(redirectdiscount !== undefined) {
					Redirect(baseUrl + 'social/login/facebook?redirect=' + encodeURIComponent(redirectdiscount));
					return false;
				} 

				if(redirectcashback !== undefined) {
					window.open(baseUrl + 'social/login/facebook?redirect=' + encodeURIComponent(redirectcashback));
					return false;	
				}
				
				if(reservationUrl !== undefined) {
					window.open(baseUrl + 'social/login/facebook?redirect=' + reservationUrl);
					return false;
				}

				if(redirectUrl !== undefined) {
					if(type == 'iframe') {
						Redirect(baseUrl + 'social/login/facebook?redirect=' + encodeURIComponent(redirectUrl));
						return false;
					} else {
						window.open(baseUrl + 'social/login/facebook?redirect=' + encodeURIComponent(redirectUrl));
						return false;
					}
				}
			});

			$('#googleButton').click(function() {
				var redirect = $('#discountCardButton').data('redirect');
					
				$form = $('#reservationForm');
				$encode = $('input[name="encode_url"]').val();

				// check if the form id exists
				if ($form[0]) {
					if ($encode !== undefined) {
						var reservationUrl = encodeURIComponent($('input[name="reservation_url"]').val() + '?date=' + $('input[name="date_hidden"]').val().replace(/-/g, '') + '&time=' + $('input[name="time"]').val().replace(':', '') + '&persons=' + $('input[name="persons"]').val() + ($('input[name="iframe"]').val() == 1 ?  '&iframe=1' : ''));
					} else {
						var reservationUrl = encodeURIComponent($('input[name="reservation_url"]').val() + '?date=' + $('input[name="date"]').val().replace(/-/g, '') + '&time=' + $('input[name="time"]').val().replace(':', '') + '&persons=' + $('input[name="persons"]').val() + ($('input[name="iframe"]').val() == 1 ?  '&iframe=1' : ''));
					}
				}

				if (redirect !== undefined) {
					Redirect(baseUrl + 'social/login/google?redirect=' +  encodeURIComponent(redirect));
					return false;
				} 

				if (reservationUrl !== undefined) {
					window.open(baseUrl + 'social/login/google?redirect=' + reservationUrl);
					return false;
				}

				if (redirectUrl !== undefined) {
					window.open(baseUrl + 'social/login/google?redirect=' + encodeURIComponent(redirectUrl));
					return false;
				}
			});

			if (type == 'iframe') {
				$('#guestAccount').hide();
			} else if(type !== undefined) {
				$('#guestAccount').hide();
			}

			$('#guestAccount').on('click', function() {
				if(typeRedirect == 1) {
					Redirect(redirectUrl);
				} else {
					$('#reservationForm').submit();
				}
			});

			$('.jqibuttons').api({
				url: baseUrl + 'login',
				method: 'POST',
				serializeForm: true,
				onComplete: function(response) {
					var output = '<div class="header">Er zijn fouten opgetreden</div><ul class="list">';

					for(var i in response) {
						output += '<li>' + response[i] + '</li>';
					}

					output += '</ul>';

					if (response.success == 1) {
						if (redirectUrl !== undefined) {
							Redirect(redirectUrl);
						} else {
							Redirect(baseUrl + 'open-menu'); 
						}
					} else if(response.throttling == 1) {
						$('.error.login').css('display', 'block');
						$('.errors.login').html('U heeft 5 foutieve login pogingen gedaan! Gelieve 15 minuten te wachten voordat u terug probeert in te loggen.');
					} else if(response.activation == 1) {
						$('.error.login').css('display', 'block');
						$('.errors.login').html(' Uw account is nog niet geactiveerd. Gelieve eerst op de link in de mail klikken om uw account te activeren.');
					} else if(response.tokemismatch == 1) {
						$('.error.login').css('display', 'block');
						$('.errors.login').html('Er is een fout opgetreden. Sluit het login scherm en probeer het opnieuw.');
					} else {
						$('.errors.login').transition('pulse');
						$('.errors.login').html(output);
					}
				}
			});	

			$('.recover.password').api({
				url: baseUrl + 'forgot-password',
				onComplete: function(response) {
					recoverPassword(response);
				}
			});
			
			register('registerButton3', redirectUrlDefault);
		}
	});
});