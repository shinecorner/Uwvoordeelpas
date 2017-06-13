var scrollDirection;

$(document).ready(function ($) {

    $('.button-collapse2, .sidebar.open').sideNav({
        menuWidth: 300, // Default is 300
        edge: 'right', // Choose the horizontal origin
        closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
        draggable: true // Choose whether you can drag to open on touch screens
    }
    );




    // Animate scrolling on hire me button
    $('.hire-me-btn').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: $("#contact").offset().top}, 500);
    });


    // window scroll Sections scrolling

    (function () {
        var sections = $(".scroll-section");

        function getActiveSectionLength(section, sections) {
            return sections.index(section);
        }

        if (sections.length > 0) {


            sections.waypoint({
                handler: function (event, direction) {
                    var active_section, active_section_index, prev_section_index;
                    active_section = $(this);
                    active_section_index = getActiveSectionLength($(this), sections);
                    prev_section_index = (active_section_index - 1);

                    if (direction === "up") {
                        scrollDirection = "up";
                        if (prev_section_index < 0) {
                            active_section = active_section;
                        } else {
                            active_section = sections.eq(prev_section_index);
                        }
                    } else {
                        scrollDirection = "Down";
                    }


                    if (active_section.attr('id') != 'home') {
                        var active_link = $('.menu-smooth-scroll[href="#' + active_section.attr("id") + '"]');
                        active_link.parent('li').addClass("current").siblings().removeClass("current");
                    } else {
                        $('.menu-smooth-scroll').parent('li').removeClass('current');
                    }
                },
                offset: '35%'
            });
        }

    }());



    // Map
    var mapStyle = [
        {
            "featureType": "landscape",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 50
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "poi",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 40
                },
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.highway",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 20
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "road.local",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 30
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "transit",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "administrative.province",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "on"
                },
                {
                    "lightness": -0
                },
                {
                    "saturation": -0
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [
                {
                    "hue": "#00baff"
                },
                {
                    "lightness": -10
                },
                {
                    "saturation": -95
                }
            ]
        }
    ];

    var $mapWrapper = $('#map'), draggableOp;


    if (jQuery.browser.mobile === true) {
        draggableOp = false;
    } else {
        draggableOp = true;
    }

    if ($mapWrapper.length > 0) {
        var map = new GMaps({
            div: '#map',
            lat: 23.79473005386213,
            lng: 90.41430473327637,
            scrollwheel: false,
            draggable: draggableOp,
            zoom: 16,
            disableDefaultUI: true,
            styles: mapStyle
        });

        map.addMarker({
            lat: 23.79473005386213,
            lng: 90.41430473327637,
            icon: 'images/marker-icon.png',
            infoWindow: {
                content: '<p>BD InfoSys Ltd, Dhaka, Bangladesh</p>'
            }
        });
    }

    // Date Picker from jQuery
    $("#datepicker").datepicker().datepicker("setDate", new Date());
    $("#datepicker").datepicker("option", "minDate", "0");
	$("#datepicker").datepicker("option", "dateFormat", "yy-mm-dd ");

	if($('[data-filter-todate]').length > 0 )
	{
		$('[data-filter-todate]').datepicker( 'option' , 'onSelect', function(date,inst) {
			var $this = ($(inst).data('filter-todate')) ? $(inst) : $(this);

			var idtime = $this.data('time');
			var objtime = $(idtime);
			var selectedDate = $this.datepicker('getDate');
			var currdate= new Date();
			var first = false;


			objtime.find('option').each(function(key, value) {
					$(this).show();
					$(this).removeAttr('selected');
					// check if the date is in the past
					var d = Date.parse(selectedDate.getFullYear() + '-' + (selectedDate.getMonth() +1)  + '-' + selectedDate.getDate() +' '+ $(this).data('value')+':00');
					var day = new Date(d);

					if (day  < currdate) {
						$(this).hide();
					} else if(first == false){
						$(this).attr('selected','selected');
						first= true;
					}
			});
			
		});
		
		$('[data-filter-todate]').each( function() {
			$(this).datepicker("option", "onSelect")('',$(this));
		});
		
	}

    if ($('[data-datepicker-ajax]').length > 0)
    {
        var currentDate = new Date();
        var jsonParse = [];

        var refresh_option = function (id, available) {
            // Disable time field when there is no date available								
            $(id + ' option').each(function (key) {
                if (key <= available)
                    $(this).removeAttr("disabled");
                else
                    $(this).attr("disabled", "disabled");
            });

            $(id).val("0");
        }

        $('#time-calendar').on('change', function () {
            var rules = $(this).data();
            if (rules)
                refresh_option('#persons-calendar', rules.availablePersons);
        });

        $('#time-dropdown').on('change', function () {
            var rules = $(this).data();
            if (rules)
                refresh_option('#persons-dropdown', rules.availablePersons);
        });

        $('[data-datepicker-ajax]').each(function () {

            $(this).datepicker({
                useCurrent: true,
                firstDay: 1,
                dateFormat: 'dd MM yy',
                onSelect: function (date, inst) {
                    var $this = ($(inst).data('datepicker-ajax')) ? $(inst) : $(this);

                    var lgroup_res = $this.data('group') | 0;
                    var ltimeselect = $this.data('timeselect');
                    var lpersons = $('select[name="persons"]').val(); /*$this.data('persons') */

					var ldate = $this.datepicker('getDate');
                    var dateISO = (ldate.toLocaleDateString());

                    $('input[name="date"]').val(dateISO);
                    $('input[name="date_hidden"]').val(dateISO);

                    $.ajax({
                        method: 'GET',
                        url: baseUrl + 'ajax/available/time',
                        data: {
                            company: $('input[name="company_id"]').val(),
                            persons: lpersons,
                            group_res: lgroup_res,
                            date: dateISO
                        },
                        success: function (response) {
                            var jsonParselocal = JSON.parse(response);
                            var jsonKeys = Object.keys(jsonParselocal);
                            var select_calendar = $(ltimeselect); /*$("#time-calendar")*/
                            ;

                            select_calendar.empty();
                            for (var time in jsonParselocal) {
                                var parse_local = jsonParselocal[time];
                                var key = Object.keys(parse_local)[0];

                                select_calendar.append($('<option></option>').val(time).html(time)).data(parse_local[key]);
                            }
                            ;
                        }
                    });
                    $(this).datepicker('setDate', date);

                },
                beforeShowDay: function (date) {
                    var $this = $(this);
                    var ltimeselect = $this.data('timeselect');

                    if (jsonParse[ltimeselect] && jsonParse[ltimeselect].dates.length) {
                        var dates_check = jsonParse[ltimeselect].dates;
                        var found = [false];
                        dates_check.forEach(function (val, i, arr) {
                            var val_date = Date.parse(val.date + " 00:00:00");
                            var select_date = new Date(val_date);
                            if (date.getTime() === select_date.getTime()) {
                                found = [true];
                            }
                        });
                        return found;
                    } else
                        return [false];
                },
                onChangeMonthYear: function (year, month, inst) {
                    var $this = ($(inst).data('datepicker-ajax')) ? $(inst) : $(this);

                    var lgroup_res = $this.data('group');
                    var ltimeselect = $this.data('timeselect');
                    var lpersons = $('select[name="persons"]').val() /*$this.data('persons')*/;
                    var data_lock = $this.data('lock');

                    $('input[name="month"]').val(month);
                    $('input[name="year"]').val(year);
                    $('input[name="monthDate"]').val(month + "-" + year);

                    if (data_lock) {
                        $(data_lock + " > *").attr('disabled', true);
                        $(data_lock).css('opacity', '0.4');
                    }

                    $.ajax({
                        url: baseUrl + 'ajax/available/reservation',
                        data: {
                            persons: lpersons,
                            month: month,
                            year: year,
                            company: $('input[name="company_id"]').val()
                        },
                        success: function (response) {
                            jsonParse[ltimeselect] = JSON.parse(response);
                            if (jsonParse[ltimeselect].dates[0])
                            {
                                var newDate = new Date(jsonParse[ltimeselect].dates[0].date + " 00:00:00");
                                $this.datepicker("setDate", newDate);
                                $this.datepicker("option", "onSelect")(newDate, $this);
                            }

                            refresh_option(lpersons, jsonParse[ltimeselect].availablePersons);
                            $this.datepicker('refresh');
                        },
                        complete: function () {
                            if (data_lock) {
                                $(data_lock + " > *").removeAttr('disabled');
                                ;
                                $(data_lock).css('opacity', '');
                            }
                            $this.datepicker('refresh');
                        }
                    });
                }
            }).datepicker($.datepicker.regional[ "nl" ]);

        });

        $('[data-datepicker-ajax]').each(function () {
            $(this).datepicker("option", "onChangeMonthYear")(currentDate.getFullYear(), currentDate.getMonth() + 1, $(this));

        });
    }


}(jQuery));



$(window).load(function ($) {




    // section calling
    $('.section-call-to-btn.call-to-home').waypoint({
        handler: function (event, direction) {
            var $this = $(this);
            $this.fadeIn(0).removeClass('btn-hidden');
            var showHandler = setTimeout(function () {
                $this.addClass('btn-show').removeClass('btn-up');
                clearTimeout(showHandler);
            }, 1500);
        },
        offset: '90%'
    });


    $('.section-call-to-btn.call-to-about').delay(1000).fadeIn(0, function () {
        var $this = jQuery(this);
        $this.removeClass('btn-hidden');
        var showHandler = setTimeout(function () {
            $this.addClass('btn-show').removeClass('btn-up');
            clearTimeout(showHandler);
        }, 1600);
    });



    // portfolio Mesonary
    if ($('#protfolio-msnry').length > 0) {
        // init Isotope
        var loading = 0;
        var portfolioMsnry = $('#protfolio-msnry').isotope({
            itemSelector: '.single-port-item',
            layoutMode: 'fitRows'
        });


        $('#portfolio-msnry-sort a').on('click', function (e) {

            e.preventDefault();

            if ($(this).parent('li').hasClass('active')) {
                return false;
            } else {
                $(this).parent('li').addClass('active').siblings('li').removeClass('active');
            }

            var $this = $(this);
            var filterValue = $this.data('target');

            // set filter for Isotope
            portfolioMsnry.isotope({filter: filterValue});

            return $(this);
        });

        $('#portfolio-item-loader').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            for (var i = 0; i < 3; i++) {
                $.get("portfolioitems.html", function (data, status) {
                    var lists, numb, target = $('#portfolio-msnry-sort li.active a').data('target');

                    lists = (target != '*') ? $(data).find('li' + target) : $(data).find('li');

                    if (lists.length > 0) {
                        numb = Math.floor(Math.random() * lists.length);
                        portfolioMsnry.isotope('insert', lists.eq(numb));

                        loading++;
                        (loading == 9) ? $this.remove() : "";
                    }

                });
            }

        });

        var portfolioModal = $('#portfolioModal'),
                portImgArea = portfolioModal.find('.model-img'),
                portTitle = portfolioModal.find('.modal-content .title'),
                portContent = portfolioModal.find('.modal-content .m-content'),
                portLink = portfolioModal.find('.modal-footer .modal-action');

        $('#protfolio-msnry').delegate('a.modal-trigger', 'click', function (e) {
            e.preventDefault();
            var $this = $(this);
            portfolioModal.openModal({
                dismissible: true,
                opacity: '.4',
                in_duration: 400,
                out_duration: 400,
                ready: function () {
                    var imgSrc = $this.data('image-source'),
                            title = $this.data('title'),
                            content = $this.data('content'),
                            demoLink = $this.data('demo-link');


                    if (imgSrc) {
                        portImgArea.html('<img src="' + imgSrc + '" alt="Portfolio Image" />');
                    }
                    ;


                    portTitle.text(title);
                    portContent.text(content);
                    portLink.attr('href', demoLink);
                }
            });
        });
    }

    // skills animation
    $('#skillSlider').waypoint({
        handler: function (event, direction) {
            $(this).find('.singel-hr-inner').each(function () {
                var height = $(this).data('height');
                $(this).css('height', height);
            });
        },
        offset: '60%'
    });


    // Wow init
    new WOW({
        offset: 200,
        mobile: false
    }).init();


    $('.flexslider').flexslider({
        selector: ".slides > .container",
        animation: "slide"
    });


    if ($('.tabs-content:visible .col4:hidden').length > 0) {
        $('.wr2').show();
    }
    else {
        ($('.tabs-content .col4:visible').find('.wr2')).hide();
    }

    $('.wr2').click(function () {
        $('.tabs-content:visible .col4').slice(0, $('.tabs-content .col4:visible').length + 3).css('display', 'block');
        if ($('.tabs-content:visible .col4:hidden').length == 0)
            $(this).hide();
        return false;
    });

    $("a.activation").click(function () {
        var elementClick = $(this).attr("href")
        var destination = $(elementClick).offset().top;
        jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1000);
        return false;
    });


    $('.bxslider').bxSlider({
        controls: true,
        pagerCustom: '.bx-pager'
    });

    var $j = jQuery.noConflict();
    var realSlider = $j("ul#bxslider").bxSlider({
        speed: 1000,
        pager: false,
        nextText: '',
        prevText: '',
        infiniteLoop: false,
        hideControlOnEnd: true,
        onSlideBefore: function ($slideElement, oldIndex, newIndex) {
            changeRealThumb(realThumbSlider, newIndex);

        }

    });

    var realSlider = $j("ul#bxslider").bxSlider({
        speed: 1000,
        pager: false,
        nextText: '',
        prevText: '',
        infiniteLoop: false,
        hideControlOnEnd: true,
        onSlideBefore: function ($slideElement, oldIndex, newIndex) {
            changeRealThumb(realThumbSlider, newIndex);
        }
    });

    var realThumbSlider = $j("ul#bxslider-pager").bxSlider({
        minSlides: 4,
        mode: 'vertical',
        maxSlides: 4,
        slideWidth: 205,
        slideMargin: 3,
        moveSlides: 1,
        pager: false,
        speed: 1000,
        infiniteLoop: false,
        hideControlOnEnd: true,
        nextText: '<span></span>',
        prevText: '<span></span>',
        onSlideBefore: function ($slideElement, oldIndex, newIndex) {
            /*$j("#sliderThumbReal ul .active").removeClass("active");
             $slideElement.addClass("active"); */
        }
    });
    linkRealSliders(realSlider, realThumbSlider);
    if ($j("#bxslider-pager li").length < 5) {
        $j("#bxslider-pager .bx-next").hide();
    }

// sincronizza sliders realizzazioni
    function linkRealSliders(bigS, thumbS) {
        $j("ul#bxslider-pager").on("click", "a", function (event) {
            event.preventDefault();
            var newIndex = $j(this).parent().attr("data-slideIndex");
            bigS.goToSlide(newIndex);
        });
    }
//slider!=$thumbSlider. slider is the realslider
    function changeRealThumb(slider, newIndex) {

        var $thumbS = $j("#bxslider-pager");
        $thumbS.find('.active').removeClass("active");
        $thumbS.find('li[data-slideIndex="' + newIndex + '"]').addClass("active");

        if (slider.getSlideCount() - newIndex >= 4)
            slider.goToSlide(newIndex);
        else
            slider.goToSlide(slider.getSlideCount() - 4);

    }


    jQuery('.tabs-link a').click(function () {

        var tabId = jQuery(this).attr('href');
        jQuery('.tabs-link a').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tabs-content > div').hide();
        jQuery(tabId).show();
        return false;
    });
    jQuery('.tabs-link a').eq(1).click();

    jQuery('.r a').click(function () {

        var tabId = jQuery(this).attr('href');
        jQuery('.r a').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tabs-content > ul').hide();
        jQuery(tabId).show();
        return false;
    });
    jQuery('.r a').eq(0).click();

    /*jQuery('.up > .more').show();
    jQuery('.tabs-content a').click(function () {
        jQuery('.up > .more').show();
        var tabId = jQuery(this).attr('href');
        jQuery('.tabs-content a').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.more > div').hide();
        jQuery('.up > .start').hide();
        jQuery(tabId).show();
        return false;
    });*/
// jQuery('.tabs-content a').eq(0).click();

}(jQuery));


(function () {
    /*=========== count up statistic ==========*/
    var $countNumb = $('.countNumb');

    if ($countNumb.length > 0) {
        $countNumb.counterUp({
            delay: 15,
            time: 1700
        });
    }



    $('#contactForm').on('submit', function (e) {
        e.preventDefault();
        var $this = $(this),
                data = $(this).serialize(),
                name = $this.find('#contact_name'),
                email = $this.find('#email'),
                message = $this.find('#textarea1'),
                loader = $this.find('.form-loader-area'),
                submitBtn = $this.find('button, input[type="submit"]');

        loader.show();
        submitBtn.attr('disabled', 'disabled');

        function success(response) {
            swal("Thanks!", "Your message has been sent successfully!", "success");
            $this.find("input, textarea").val("");
        }

        function error(response) {
            $this.find('input.invalid, textarea.invalid').removeClass('invalid');
            if (response.name) {
                name.removeClass('valid').addClass('invalid');
            }

            if (response.email) {
                email.removeClass('valid').addClass('invalid');
            }

            if (response.message) {
                message.removeClass('valid').addClass('invalid');
            }
        }

        $.ajax({
            type: "POST",
            url: "inc/sendEmail.php",
            data: data
        }).done(function (res) {

            var response = JSON.parse(res);

            if (response.OK) {
                success(response);
            } else {
                error(response);
            }


            var hand = setTimeout(function () {
                loader.hide();
                submitBtn.removeAttr('disabled');
                clearTimeout(hand);
            }, 1000);

        }).fail(function () {
            sweetAlert("Oops...", "Something went wrong, Try again later!", "error");
            var hand = setTimeout(function () {
                loader.hide();
                submitBtn.removeAttr('disabled');
                clearTimeout(hand);
            }, 1000);
        });
    });

});