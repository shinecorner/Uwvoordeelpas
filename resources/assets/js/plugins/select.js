/**
 *
 *
 * @author Jan Ebsen <xicrow@gmail.com>
 */
(function ($) {
	window.select = {
		/**
		 * Default settings
		 */
		settings: {
			maxOptionsInLabel: 3
		},

		/**
		 * Counter for selects
		 */
		counter: 1,

		/**
		 * Language translations
		 */
		ln: {
			'oneOptionSelected': 'geselecteerd',
			'multipleOptionsSelected': 'geselecteerd',
			'defaultPlaceholder': 'Klik hier om te kiezen'
		},

		/**
		 * Initializes the plugin
		 *
		 * @param    {object}    options    The plugin options (Merged with default settings)
		 * @return    {object}    this    The current element itself
		 */
		init: function (options) {
			// Merge default settings with provided settings, and save to element
			$(this).data('settings', $.extend(select.settings, options));

			// Loop through each matched element
			return this.each(function () {
				// Get select element
				var $select = $(this);

				// If element is not a select
				if ($select.prop('tagName') != 'SELECT') {
					// Return error
					return $.error('jquery.select only applies to <SELECT> elements not <' + $select.prop('tagName') + '>');
				}

				// Build trigger
				$select.select('buildTrigger');

				// Build container
				$select.select('buildContainer');
				
				// Update labels
				$select.select('updateLabel');

				// Hide select element
				$select.hide();

				// Increment counter
				select.counter++;
			});
		},

		/**
		 *
		 */
		buildTrigger: function () {
			// Get select element
			var $select = $(this);
			
			var $trigger;
			
			// If trigger has allready been created
			if ($select.data('jquery.select.trigger')) {
				// Get trigger
				$trigger = $select.data('jquery.select.trigger');
			} else {
				// Create trigger
				$trigger = $('<a>')
					.attr('href', 'javascript:void(0)')
					.attr('id', 'jquery-select-trigger-' + select.counter)
					.addClass('jquery-select-trigger')
					.css({
						width: $select.outerWidth(true)
					})
					.data('jquery.select.no', select.counter)
					.data('jquery.select.source.element', $select)
					.on('click', function (event) {
						$(this).select('toggle');
					})
					.on('keypress', function (event) {
						// If space is pressed
						if (event.keyCode == 0) {
							$(this).select('toggle');
						}
					})
					/*
					.on('focus', function (event) {
						$(this).select('open');
					})
					.on('blur', function (event) {
						console.log(event.target);
						if (!$(event.target).closest('.jquery-select-container').length) {
							$(this).select('close');
						}
					})
					*/
					.insertAfter($select);
			}

			// Create label
			var $triggerLabel = $('<span>')
				.addClass('jquery-select-trigger-label')
				.appendTo($trigger);

			// Create arrow
			var $triggerArrow = $('<span>')
				.addClass('jquery-select-trigger-arrow')
				.appendTo($trigger);

			// Create arrow inner
			var $triggerArrowInner = $('<span>')
				.addClass('jquery-select-trigger-arrow-inner')
				.appendTo($triggerArrow);

			// Check if element has a placeholder
			if ($select.data('placeholder')) {
				// Add placeholder and class to label
				$triggerLabel
					.html($select.data('placeholder'))
					.addClass('placeholder');
			} else {
				// Add default placeholder and class to label
				$triggerLabel
					.html(select.ln.defaultPlaceholder)
					.addClass('placeholder');
			}

			// Save trigger element on select
			$select.data('jquery.select.trigger', $trigger);
			
			return $select;
		},

		/**
		 *
		 */
		buildContainer: function () {
			// Get select element
			var $select = $(this);
			
			// Get trigger
			var $trigger = $select.data('jquery.select.trigger');
			
			var $container, $containerInner;
			
			// If container has allready been created
			if ($select.data('jquery.select.container')) {
				// Get container
				$container = $select.data('jquery.select.container');
				
				// Get inner container
				$containerInner = $container.children();
			} else {
				// Create container
				$container = $('<div>')
					.attr('id', 'jquery-select-container-' + select.counter)
					.addClass('jquery-select-container')
					.data('jquery.select.no', select.counter)
					.data('jquery.select.source.element', $select)
					.insertAfter($trigger);
				
				// Create inner container
				$containerInner = $('<div>')
					.addClass('jquery-select-container-inner')
					.appendTo($container);
			}
			
			// Loop through option groups and options
			$select.find('optgroup, option').each(function (index, element) {
				// Get element
				var $element = $(element);

				// If element is an <OPTGROUP>
				if ($element.prop('tagName') == 'OPTGROUP') {
					// Create and add group to container
					var $group = $('<a>')
						.attr('href', 'javascript:void(0)')
						.addClass('jquery-select-container-inner-group')
						.html($element.attr('label'))
						.on('keypress', function (event) {
							// If arrow up is pressed
							if (event.keyCode == 38) {
								event.preventDefault();
								event.stopPropagation();
								$(this).prev().focus();
								return false;
							}

							// If arrow down is pressed
							if (event.keyCode == 40) {
								event.preventDefault();
								event.stopPropagation();
								$(this).next().focus();
								return false;
							}
						})
						.appendTo($containerInner);

					if (!$select.attr('multiple')) {
						$group.on('click', function () {
							// Select/unselect all in group
						});
					}
				} else {
					// Create item and add to container
					var $item = $('<a>')
						.attr('href', 'javascript:void(0)')
						.addClass('jquery-select-container-inner-item')
						.html($element.text())
						.data('jquery.select.value', $element.val())
						.data('jquery.select.multiple', $select.attr('multiple'))
						.on('keypress', function (event) {
							// If arrow up is pressed
							if (event.keyCode == 38) {
								event.preventDefault();
								event.stopPropagation();
								$(this).prev().focus();
								return false;
							}
							
							// If arrow down is pressed
							if (event.keyCode == 40) {
								event.preventDefault();
								event.stopPropagation();
								$(this).next().focus();
								return false;
							}
							
							// If space is pressed
							if (event.keyCode == 0) {
								event.preventDefault();
								event.stopPropagation();
								
								// Get item
								var $item = $(this);

								// If item is disabled
								if ($item.hasClass('disabled')) {
									// Stop here
									return;
								}

								// Get container
								var $container = $item.parents('.jquery-select-container');

								// Get trigger
								var $trigger = $('#jquery-select-trigger-' + $container.data('jquery.select.no'));

								// If not multiple select
								if (!$trigger.data('jquery.select.source.element').attr('multiple')) {
									// Unselect all items
									$container.find('.selected').removeClass('selected');
								}

								// If item is selected
								if ($item.hasClass('selected')) {
									// Unselect
									$item.removeClass('selected');
								} else {
									// Select
									$item.addClass('selected');
								}

								// Update selected option on source element
								if ($trigger.data('jquery.select.source.element').attr('multiple')) {
									// Get selected items
									var $selectedItems = $container.find('.selected');

									// Get array with selected values
									var selectedValues = [];
									$selectedItems.each(function (index, element) {
										selectedValues.push($(element).data('jquery.select.value'));
									});

									// Variable to determine if selected index has been set
									var selectedIndex = false;

									// Loop through source elements options
									$trigger.data('jquery.select.source.element').find('option').each(function (index, element) {
										// If selected option value in is selected elements value
										if ($.inArray(element.value, selectedValues) !== -1) {
											// Set option to selected
											element.selected = true;

											// If selected index has not been set
											if (!selectedIndex) {
												// Set selected index on source element
												$trigger.data('jquery.select.source.element')[0].selectedIndex = index;

												// Selected index has been set
												selectedIndex = true;
											}
										} else {
											// Set option to not selected
											element.selected = false;
										}
									});
								} else {
									// Get selected item
									var $selectedOption = $container.find('.selected');

									// Loop through source elements options
									$trigger.data('jquery.select.source.element').find('option').each(function (index, element) {
										// If selected option value equals option value
										if ($selectedOption.data('jquery.select.value') == element.value) {
											// Set option to selected
											element.selected = true;

											// Set selected index on source element
											$trigger.data('jquery.select.source.element')[0].selectedIndex = index;
										} else {
											// Set option to not selected
											element.selected = false;
										}
									});
								}

								// Trigger change event on select element
								$trigger.data('jquery.select.source.element').trigger('change');

								// Call updateLabel method
								$trigger.data('jquery.select.source.element').select('updateLabel');

								// If not multiple select
								if (!$trigger.data('jquery.select.source.element').attr('multiple')) {
									// Hide options
									$trigger.select('close');
								}
								
								return false;
							}
						})
						.on('click', function (event) {
							// Get item
							var $item = $(this);

							// If item is disabled
							if ($item.hasClass('disabled')) {
								// Stop here
								return;
							}

							// Get container
							var $container = $item.parents('.jquery-select-container');

							// Get trigger
							var $trigger = $('#jquery-select-trigger-' + $container.data('jquery.select.no'));
							
							// If not multiple select
							if (!$trigger.data('jquery.select.source.element').attr('multiple')) {
								// Unselect all items
								$container.find('.selected').removeClass('selected');
							}
							
							// If item is selected
							if ($item.hasClass('selected')) {
								// Unselect
								$item.removeClass('selected');
							} else {
								// Select
								$item.addClass('selected');
							}
							
							// Update selected option on source element
							if ($trigger.data('jquery.select.source.element').attr('multiple')) {
								// Get selected items
								var $selectedItems = $container.find('.selected');

								// Get array with selected values
								var selectedValues = [];
								$selectedItems.each(function (index, element) {
									selectedValues.push($(element).data('jquery.select.value'));
								});

								// Variable to determine if selected index has been set
								var selectedIndex = false;

								// Loop through source elements options
								$trigger.data('jquery.select.source.element').find('option').each(function (index, element) {
									// If selected option value in is selected elements value
									if ($.inArray(element.value, selectedValues) !== -1) {
										// Set option to selected
										element.selected = true;

										// If selected index has not been set
										if (!selectedIndex) {
											// Set selected index on source element
											$trigger.data('jquery.select.source.element')[0].selectedIndex = index;

											// Selected index has been set
											selectedIndex = true;
										}
									} else {
										// Set option to not selected
										element.selected = false;
									}
								});
							} else {
								// Get selected item
								var $selectedOption = $container.find('.selected');

								// Loop through source elements options
								$trigger.data('jquery.select.source.element').find('option').each(function (index, element) {
									// If selected option value equals option value
									if ($selectedOption.data('jquery.select.value') == element.value) {
										// Set option to selected
										element.selected = true;

										// Set selected index on source element
										$trigger.data('jquery.select.source.element')[0].selectedIndex = index;
									} else {
										// Set option to not selected
										element.selected = false;
									}
								});
							}

							// Trigger change event on select element
							$trigger.data('jquery.select.source.element').trigger('change');

							// Call updateLabel method
							$trigger.data('jquery.select.source.element').select('updateLabel');

							// If not multiple select
							if (!$trigger.data('jquery.select.source.element').attr('multiple')) {
								// Hide options
								$trigger.select('close');
							}
							
							return false;
						})
						.appendTo($containerInner);
					
					// If option is selected
					if ($element.prop('selected')) {
						// Add selected class to item
						$item.addClass('selected');
					}
					
					// If option is disabled
					if ($element.prop('disabled')) {
						// Add disabled class to item
						$item.addClass('disabled');
					}

					// If element has a title
					if ($element.attr('title') && $element.attr('title') != '') {
						// Append title to label as description
						$item.append('<span class="jquery-select-container-inner-item-description">' + $element.attr('title') + '</span>');
					}
				}
			});

			// Save container element on select
			$select.data('jquery.select.container', $container);
			
			return $select;
		},

		/**
		 *
		 */
		updateLabel: function () {
			// Get select element
			var $select = $(this);

			// Get trigger element
			var $trigger = $select.data('jquery.select.trigger');
			
			// Get label
			var $label = $trigger.find('.jquery-select-trigger-label');

			// Get container
			var $container = $('#jquery-select-container-' + $trigger.data('jquery.select.no'));

			// Save original label, for later use
			if (typeof($label.data('jquery.select.original.label')) == 'undefined') {
				$label.data('jquery.select.original.label', $label.html());
			}

			// Get selected items
			var $selectedItems = $container.find('.selected');

			// Update label HTML and class
			if ($selectedItems.length > select.settings.maxOptionsInLabel) {
				$label.removeClass('placeholder').html($selectedItems.length + ' ' + ($selectedItems.length == 1 ? select.ln.oneOptionSelected : select.ln.multipleOptionsSelected));
			} else if ($selectedItems.length > 0 && $selectedItems.length <= select.settings.maxOptionsInLabel) {
				var html = '';
				$selectedItems.each(function(index, element){
					if (html != '') {
						html+= ', ';
					}
					html+= $(element).clone().children().remove().end().text();
				});
				
				$label.removeClass('placeholder').html(html);
			} else {
				$label.addClass('placeholder').html($label.data('jquery.select.original.label'));
			}
		},

		/**
		 * Open options
		 */
		open: function () {
			// Hide all other visible options
			$('.jquery-select-container').each(function (index, element) {
				var $container = $(element);
				if ($container.is(':visible')) {
					var $trigger = $('#jquery-select-trigger-' + $container.data('jquery.select.no'));
					$trigger.select('close');
				}
			});

			// Get trigger
			var $trigger = $(this);

			// Get container
			var $container = $('#jquery-select-container-' + $trigger.data('jquery.select.no'));

			// Show the container
			$container.show();

			// Add active class on the trigger
			$trigger.addClass('active');
			
			// Set focus to first element in the container
			$container.find('a').first().focus();

			// Resize the container
			var documentWidth = $(document).width();
			var triggerWidth = $trigger.outerWidth(true);
			var triggerOffset = $trigger.offset();
			var width = triggerWidth;
			if ($container.hasClass('jquery-select-x3') && (triggerOffset.left + (triggerWidth * 3)) < documentWidth) {
				width = (triggerWidth * 3);
			} else if ($container.hasClass('jquery-select-x2') && (triggerOffset.left + (triggerWidth * 2)) < documentWidth) {
				width = (triggerWidth * 2);
			} else if ($container.hasClass('jquery-select-x.5')) {
				width = (triggerWidth / 2);
			}
			$container.css('width', width);

			// Trigger open event on the source element
			$trigger.data('jquery.select.source.element').trigger('open');

			return $trigger;
		},

		/**
		 * Close options
		 */
		close: function () {
			// Get trigger
			var $trigger = $(this);

			// Get container
			var $container = $('#jquery-select-container-' + $trigger.data('jquery.select.no'));

			// Hide the container
			$container.hide();

			// Remove active class from trigger
			$trigger.removeClass('active');

			// Trigger close event on the source element
			$trigger.data('jquery.select.source.element').trigger('close');
			
			return $trigger;
		},

		/**
		 * Toggle visibility of options
		 */
		toggle: function () {
			// Get trigger
			var $trigger = $(this);

			// Get container
			var $container = $('#jquery-select-container-' + $trigger.data('jquery.select.no'));

			// If container is visible
			if ($container.is(':visible')) {
				// Call close method
				$trigger.select('close');
			} else {
				// Call open method
				$trigger.select('open');
			}

			return $trigger;
		}
	};

	/**
	 * jquery plugin
	 *
	 * @param method
	 */
	$.fn.select = function (method) {
		// If method exists
		if (select[method]) {
			// Call method with arguments
			return select[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			// Call initialize method
			return select.init.apply(this, arguments);
		} else {
			// Method not found
			return $.error('Method ' + method + ' does not exist on jquery.select');
		}
	};

	// On document click or touchstart
	$(document).on('click touchstart', function (event) {
		// If we're not inside a trigger or option container
		if (!$(event.target).closest('.jquery-select-trigger').length && !$(event.target).closest('.jquery-select-container').length) {
			// Hide all visible options
			$('.jquery-select-container').each(function (index, element) {
				var $container = $(element);
				if ($container.is(':visible')) {
					var $trigger = $('#jquery-select-trigger-' + $container.data('jquery.select.no'));
					$trigger.select('close');
				}
			});
		}
	});
	$(document).on('keypress', function(event) {
		if (event.keyCode == 27) {
			// Hide all visible options
			$('.jquery-select-container').each(function (index, element) {
				var $container = $(element);
				if ($container.is(':visible')) {
					var $trigger = $('#jquery-select-trigger-' + $container.data('jquery.select.no'));
					$trigger.select('close');
				}
			});
		}
	});
})(jQuery);
