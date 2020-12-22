import {jarallax} from '../../parallax/jarallax.min';
import slick from '../../slick/slick.min';

(function($) {

	/*'use strict';*/
	$.isMobile = function(type) {
        var reg = [];
        var any = {
            blackberry: 'BlackBerry',
            android: 'Android',
            windows: 'IEMobile',
            opera: 'Opera Mini',
            ios: 'iPhone|iPad|iPod'
        };
        type = 'undefined' == $.type(type) ? '*' : type.toLowerCase();
        if ('*' == type) reg = $.map(any, function(v) {
            return v;
        });
        else if (type in any) reg.push(any[type]);
        return !!(reg.length && navigator.userAgent.match(new RegExp(reg.join('|'), 'i')));
    };


	/*LAUNCH  AJAX */
	function launch_ajax_form($container,$custom_query,$taxonomy) {
		/**/
		 $action='cdgajax_results';
		if( typeof $custom_query['medium']!== 'undefined')  $action='cdgajax_mediums';

		$.ajax({
			type : 'post',
			url : cdgajax.ajaxurl,
			data : {
				action : $action,
				query_vars: cdgajax.query_vars,
				custom_query:$custom_query,
				taxonomy:$taxonomy,
				//page : page,
			},
			beforeSend: function() {
				//console.log('before');
				$container.addClass('loading');
			},
			success : function( response ) {
				//console.log('sucess');
				$container.removeClass('loading');
				$newContent = $(response).hide();
				//console.log('paged '+$custom_query['paged']);
				if (typeof $custom_query['paged'] !== 'undefined') {
					$container.append($newContent);
					//console.log('append');
				}
				else {
					$container.html($newContent);
					//console.log('addHTML');
				}
				$newContent.fadeIn('slow');
				CDG.keepProportions();

			}
		});
		/**/
	}


	var CDG = {

		//OnReady functions

        addMSGonReady: function() {
            console.log('Codigo Ready');
		},
		
		handleHTMLVideo: function() {
			if ($('.component_video.controlled').length) {
				
				$('.component_video.controlled').find('video').each(function(){

					var htmlVideo = $(this).get(0); 
					var $playButton = $(this).parent().find('button.video_control'); 

					
					htmlVideo.onplaying = function() {
						$playButton.removeClass("paused");
					};
					
					htmlVideo.addEventListener("pause", function() {
						$playButton.addClass("paused");
					}, true);
					
					$(this).on('click',function(){
						CDG.playPauseHTMLVideo($playButton,htmlVideo);
					});
					$playButton.on('click',function(){
						console.log('button clicked');
						CDG.playPauseHTMLVideo($playButton,htmlVideo);
					});
				});
			}
		},
		playPauseHTMLVideo: function($playButton,htmlVideo) {
			if ($playButton.hasClass("paused")){
				CDG.playHTMLVideo($playButton,htmlVideo);
			} else {
				CDG.pauseHTMLVideo($playButton,htmlVideo);
			}
		},
		playHTMLVideo: function($playButton,htmlVideo) {
			htmlVideo.play();
			setTimeout(
				function() {
					$playButton.removeClass("paused");
				}, 
			500);
		},
		pauseHTMLVideo: function($playButton,htmlVideo) {
			//console.log(htmlVideo);
			htmlVideo.pause();
			$playButton.addClass("paused");
		},
		
		slickslider: function(){
			console.log('launching slick');
			if ($(document.body).find('.slick-carousel').length) {
				console.log('loading carousels');
				var int = 0;
				var $carousels = [];
				$(document.body).find('.slick-carousel').each(function(){

					var $carousel = $(this);
					var autoplay = $carousel.data('autoplay'),
					dots       = $carousel.data('dots'),
					arrows     = $carousel.data('arrows'),
					slides     = $carousel.data('slides'),
					info       = $carousel.data('info'),
					fullbutton = $carousel.data('fullbutton');

					if(arrows === 1) {
						arrows = true;
					}
					if(dots === 1) {
						dots = true;
					}


					var slickOptions = {
						lazyLoad: 'ondemand',
						infinite: true,
						autoplay: autoplay,
						dots: dots,
						arrows: arrows,
						slide:'div',
						prevArrow: '<button class="slick-prev slick-arrow"></button>',
						nextArrow: '<button class="slick-next slick-arrow"></button>',
					}

					if(typeof slides !== 'undefined' && slides > 1){
						slickOptions['slidesToShow'] = slides;
						slickOptions['centerMode'] = true;
						slickOptions['variableWidth'] = true;

			            /**
			            $carousel.slick('slickSetOption', {
			              'slidesToShow': slides,
			              'centerMode': true,
			              'variableWidth': true,
			            }, true);
			            /**/
		        	}

		          	//console.log(slickOptions);

		          	$carousel.slick(slickOptions);


			        if(typeof fullbutton !== 'undefined'){
			          	$("#"+fullbutton).on("click", function () {
			          		var elem = $carousel.get(0);

			          		if (document.fullscreenElement) {
			          			document.exitFullscreen();
			          		} else {
			          			if (elem.requestFullscreen) {
			          				elem.requestFullscreen();
			          			} 
				                /**
				                else if (elem.msRequestFullscreen) {
				                  elem.msRequestFullscreen();
				                } else if (elem.mozRequestFullScreen) {
				                  elem.mozRequestFullScreen();
				                } else if (elem.webkitRequestFullscreen) {
				                  elem.webkitRequestFullscreen();
				                }
				                /**/
				            }
			        	});

			          	if (document.addEventListener) {
			          		document.addEventListener('fullscreenchange', changeFullScreen, false);
			          		document.addEventListener('mozfullscreenchange', changeFullScreen, false);
			          		document.addEventListener('MSFullscreenChange', changeFullScreen, false);
			          		document.addEventListener('webkitfullscreenchange', changeFullScreen, false);
			          	}
			        }

			        function changeFullScreen() {
			          	if (!document.fullscreenElement) {
			              //console.log('exit fullscreen');

			              	$carousel.slick("unslick");

			              	if(typeof slides !== 'undefined' && slides > 1){
				              	slickOptions['slidesToShow'] = slides;
				              	slickOptions['centerMode'] = true;
				              	slickOptions['variableWidth'] = true;
				                //console.log(slickOptions);
				            }

			            	$carousel.slick(slickOptions);

			        	} else {
			              //console.log('enter fullscreen');

			              $carousel.slick("unslick");

			              slickOptions['slidesToShow'] = 1;
			              slickOptions['centerMode'] = false;
			              slickOptions['variableWidth'] = false;

			              $carousel.slick(slickOptions);
			          	}
			            //if (document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement !== null)
			        }


			        if(typeof info !== 'undefined' && info != 0){
			        	var $status = $('#'+info);

			        	$carousel.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
			                //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
			                var i = (currentSlide ? currentSlide : 0) + 1;
			                $status.text(i + ' / ' + slick.slideCount);
			            });

			        }

		        	$carousels.push($carousel);

		    	});

			}
		},



		/* Scroll */
		scrollEffects: function() {

			var prevScrollpos = window.pageYOffset;

			//if there is an anchor on the URL
			if(window.location.hash) {
				scroll(0,0);
				// smooth scroll to the anchor id
				setTimeout( function() {
					$('html, body').animate({
						scrollTop: $(window.location.hash).offset().top + 'px'
					}, 1000, 'swing');
				}, 100);

			}

			//If a internal anchor link has been clicked (the 'a' element class must be "scroll")
			$(document).on('click', 'a.scroll[href^="#"]', function (event) {
				event.preventDefault();
				var cust_off = 0;
				if (typeof $(this).data('offset') !== 'undefined') {cust_off = $(this).data('offset');}
				//console.log('scroll to ' + $.attr(this, 'href'));
				//console.log('scrolling ' + $($.attr(this, 'href')).offset().top );
				$('html, body').animate({
					scrollTop: $($.attr(this, 'href')).offset().top - cust_off + 'px'
				}, 1000, 'swing');
			});

			//If a menu iten with an internal anchor link has been clicked (the 'li' element class must be "scroll")
			$(document).on('click', 'li.menu-item.scroll > a', function (event) {
				event.preventDefault();

				linked_url=$.attr(this, 'href');
				current_path=window.location.pathname;
				page_hash=this.hash;

				//console.log('scroll down to '+linked_url); //http://localhost/danny/tavaziva/about-us/#collaborators
				//console.log(page_hash.substr(1));//collaborators
				//console.log(current_path); // /danny/tavaziva/about-us

				if(linked_url.indexOf(current_path) !== -1) {//We are on the same page as the hash
					$('#navbarNavDropdown').collapse('hide'); //Close the menu
					$(".navbar-toggler__animated").toggleClass("active");
					$('html, body').animate({
						scrollTop: $(page_hash).offset().top + 'px'
					}, 1000, 'swing');

				} else {
					location.href = linked_url;
				}
			});


			$(window).scroll(function (event) {
				var vscroll = $(document).scrollTop();
				var displayWidth = $(window).width();
				//console.log(vscroll);

				if (vscroll >= 1) {
					$("#wrapper-navbar .navbar").addClass("scrolled");
					$(".dropdown-toggle").attr("aria-expanded", "false");
					$(".dropdown-menu").removeClass("show");
				} else {
					$("#wrapper-navbar .navbar").removeClass("scrolled");
				}

				var scrollLimit = 100;
				if( displayWidth > 767 ) {
					scrollLimit = 150;
				}

				var currentScrollPos = window.pageYOffset;
				if (vscroll >= scrollLimit) {
				  	if (prevScrollpos > currentScrollPos) {
				    	//document.getElementById("navbar").style.top = "0";
				    	$("#wrapper-navbar").addClass("scroll-up");
				    	$("#wrapper-navbar").removeClass("scroll-down");
				  	} else {
				  		$("#wrapper-navbar").removeClass("scroll-up");
				    	$("#wrapper-navbar").addClass("scroll-down");
				  	}
				  	prevScrollpos = currentScrollPos;
				} else {
					$("#wrapper-navbar").removeClass("scroll-up");
					$("#wrapper-navbar").removeClass("scroll-down");
				}


				$('.left_content').each(function(){
					$vtitlewrap=$(this);
					$vtitle=$('.left_content__title', this);

					var docHeight = $(document).height();
					var titleHeight = $vtitle.height();
					var positionTitle = $vtitlewrap.offset();


					minimum=positionTitle.top-130;
					maximum=docHeight-540-titleHeight;
					//console.log('scroll '+vscroll+' minimum '+minimum+' maximum '+maximum);
					if (vscroll >= minimum) {
						$vtitle.addClass("scrolled");
					} else {
						$vtitle.removeClass("scrolled");
					}
				});

				if ($('.submenu_trigger').length) {
					if( displayWidth > 767 ) {
						var submenu_pos = $(".history").position();
						if(vscroll > submenu_pos.top) {
							$('.submenu_trigger').css({"position": "fixed", "top": "110px"});
						} else {
							$('.submenu_trigger').css('position', 'static');
						}
					} else {
						$('.submenu_trigger').css('position', 'static');
					}
				}
			});
		},

		scrollWayPoint: function() {
		    var waypoint = new Waypoint({
			  element: document.getElementById('waypoint_anchor'),
			  handler: function() {
			    console.log('Basic waypoint triggered')
			  }
			});
		},



		/* Parallax */
        initParallax: function(card) {
			$(card).addClass('jarallax-enabled')
            setTimeout(function() {
                $(card).find('.wn-parallax-background')
                    .jarallax({
                        speed: 0.6
                    })
                    .css('position', 'relative');
            }, 0);
        },
        destroyParallax: function(card) {
            $(card).jarallax('destroy').css('position', '');
        },

        

		burgerEffects: function() {
			//Burger menu effect
			 $(".navbar-toggler__animated").on("click", function () {
					$(this).toggleClass("active");
					$navbar=$(this).closest('.navbar');
					if($navbar.hasClass("back-white")) {
						//console.log('removing class');
						setTimeout(function () {
						  //console.log('now');
						  $navbar.removeClass("back-white");
						}, 500);
					}
					else {
						$navbar.addClass("back-white");
					}

			  });
		},
		styleSelects: function() {

			$(".select-styled").each(function(){
				var $this = $(this), numberOfOptions = $(this).children('option').length, selectWidth='';

				if(typeof $(this).data('width') !== "undefined") {
					selectWidth='style="width:'+$(this).data('width')+'"';
				}

				$this.addClass('select-hidden');
				wrap='<div class="select" '+selectWidth+'></div>';
				divstyled='<div class="select-div-styled"></div>';
				if($this.hasClass('select-styled__before')) {
					before=$this.data('before');
					wrap='<div class="select select__before"></div>';
					divstyled='<div class="select-div-styled" data-before="'+before+'"></div>';
				}

				$this.wrap(wrap);

				var selec = '<span class="placeholder">'+$(this).children( "option:selected" ).text()+'</span>';
				//console.log('selec '+selec);

				$this.after(divstyled);

				var $styledSelect = $this.next('div.select-div-styled');
				if(selec) {
					$styledSelect.html(selec);
				} else {
					$styledSelect.html('<span class="placeholder">'+$this.children('option').eq(0).text()+'</span>');
				}
				var $list = $('<ul />', {
					'class': 'select-options'
				}).insertAfter($styledSelect);
				for (var i = 0; i < numberOfOptions; i++) {
					$('<li />', {
						text: $this.children('option').eq(i).text(),
						rel: $this.children('option').eq(i).val()
					}).appendTo($list);
				}
				var $listItems = $list.children('li');
				$styledSelect.click(function(e) {
					e.stopPropagation();
					$('div.select-div-styled.active').not(this).each(function(){
						$(this).removeClass('active').next('ul.select-options').hide();
					});
					$(this).toggleClass('active').next('ul.select-options').toggle();
				});
				$listItems.click(function(e) {
					e.stopPropagation();
					$styledSelect.text($(this).text()).removeClass('active');
					$this.val($(this).attr('rel'));
					$list.hide();
					//console.log('val '+$this.val());
					$(".select-styled").trigger("change");
					$form=$(this).closest('form');
					formClass=$form.attr('class');
					if(formClass.indexOf("categories__form") !== -1) {
						//change_categories($(this));
					}
					if(formClass.indexOf("solution__form") !== -1) {
						//$( "#"+$this.val()).trigger( "click" );
					}
				});
				/**/
				$(document).click(function(event) {
					classClicked=$(event.target).attr('class');

					if (typeof classClicked !== 'undefined') {
						if (classClicked.indexOf("select-styled") == -1) {
							$styledSelect.removeClass('active');
							$list.hide();
						}
					}
					else {
						$styledSelect.removeClass('active');
						$list.hide();
					}
				});
				/**/
			});
		},
		cssElements: function() {
			function setDocHeight() {
				document.documentElement.style.setProperty('--vh', `${window.innerHeight/100}px`);
			};
			window.addEventListener('resize', setDocHeight());
			window.addEventListener('orientationchange', setDocHeight());

			setDocHeight()
		},

		/* AJAX */
		ajaxPagination: function() {
          //console.log('loading AJAX pagination ...');

		  $( document ).on( 'click', '.load-more', function(event) {
				event.preventDefault();
				//console.log('Button clicked');

				if(typeof $(this).data('ajax-container') !== 'undefined') {
					var container_id = $(this).data('ajax-container');
					$container = $('#'+container_id);
				} else {
					return;
				}

				$custom_query ={};
				if(typeof $(this).data('post-type') !== 'undefined') {
					$custom_query['post_type'] = $(this).data('post-type');
				}
				if(typeof $(this).data('paged') !== 'undefined') {
					$custom_query['paged'] = $(this).data('paged');
				}
				//console.log('custom_query');
				//console.log(JSON.stringify($custom_query));

				$taxonomy ={};
				if(typeof $(this).data('taxonomy-name') !== 'undefined') {

					$taxonomy['name'] = $(this).data('taxonomy-name');
					$taxonomy['field'] = $(this).data('taxonomy-field');
					$taxonomy['terms'] = $(this).data('taxonomy-terms');
				}

				launch_ajax_form($container,$custom_query,$taxonomy);
			})

        },
		ajaxSelection: function() {
          //console.log('loading AJAX selection ...');

		  $( document ).on( 'click', '.ajax-select', function(event) {
				event.preventDefault();
				//console.log('Select button clicked');

				$('.ajax-select').removeClass('selected');
				$(this).addClass('selected');

				if(typeof $(this).data('ajax-container') !== 'undefined') {
					var container_id = $(this).data('ajax-container');
					$container = $('#'+container_id);
				} else {
					return;
				}

				$custom_query ={};
				if(typeof $(this).data('blint') !== 'undefined') {
					$custom_query['blint'] = $(this).data('blint');
				}
				if(typeof $(this).data('post-id') !== 'undefined') {
					$custom_query['post-id'] = $(this).data('post-id');
				}
				if(typeof $(this).data('medium') !== 'undefined') {
					$custom_query['medium'] = $(this).data('medium');
				}
				$taxonomy ={};

				launch_ajax_form($container,$custom_query,$taxonomy);
			})

        },
		ajaxFiltering: function() {
            //console.log('Filtering Ready');

			$( document ).on( 'click', '.ajax-filter', function(event) {
				event.preventDefault();
				//console.log('Filter clicked');

				/*
				* CSS, Titles and Collapse functions (not related with AJAX)
				*/
				$(this).siblings().removeClass('focused');
				$(this).addClass('focused');

				if(typeof $(this).data('toggle') !== 'undefined') {
					var toggle=$(this).data('toggle');//collapse, hide
					if(toggle=='collapse') {
						toggle='toggle';
					}
					if(typeof $(this).data('target') !== 'undefined') {
						$target=$(this).data('target');
						$($target).each(function(){
							//console.log(toggle + ' ' +$(this).attr('aria-labelledby'));
							$(this).collapse(toggle);
						});
					}
				}

				if(typeof $(this).data('main-title') !== 'undefined') {
					var main_title = $(this).data('main-title');
					var main_title_container = $(this).data('main-title-container');
					$(main_title_container).html(main_title);
				}
				if(typeof $(this).data('main-subtitle') !== 'undefined') {
					var main_subtitle = '<br/><span class="page-title__query">'+$(this).data('main-subtitle')+'</span>';
					var main_title_container = $(this).data('main-title-container');
					$(main_title_container).append(main_subtitle);
				}

				if($(this).hasClass("load-more")) {
					$(this).removeClass('load-more');
					$(this).addClass('d-none');
				}
				/* --- */



				if(typeof $(this).data('ajax-container') !== 'undefined') {
					var container_id = $(this).data('ajax-container');
					$container = $('#'+container_id);
				} else {
					return;
				}

				$custom_query ={};
				if(typeof $(this).data('post-type') !== 'undefined') {
					$custom_query['post_type'] = $(this).data('post-type');
				}
				if(typeof $(this).data('paged') !== 'undefined') {
					$custom_query['paged'] = $(this).data('paged');
				}
				//console.log('custom_query');
				//console.log(JSON.stringify($custom_query));

				$taxonomy ={};
				if(typeof $(this).data('taxonomy-name') !== 'undefined') {

					$taxonomy['name'] = $(this).data('taxonomy-name');
					$taxonomy['field'] = $(this).data('taxonomy-field');
					$taxonomy['terms'] = $(this).data('taxonomy-terms');
				}

				launch_ajax_form($container,$custom_query,$taxonomy);
			})
        },
		ajaxScroll: function() {
          //console.log('loading AJAX scroll ...');
		  var displayWidth = $(window).width();
		  var displayHeight = $(window).height();

		  if ( $( ".load-more" ).length ) {
			var load_more_triggered=false;

		  	$(window).scroll(function() {
				var footerp=$('#wrapper-footer').position();

				//console.log('Scroll '+$(window).scrollTop() + ' Footer '+footerp.top+ ' window.height '+$(window).height());
				//console.log('Scroll '+$(window).scrollTop());
				//console.log(footerp.top-$(window).height());

				//$(window).scrollTop() == $(document).height() - $(window).height() -> Scroll reachs page bottom
				//$(window).scrollTop() == footerp.top - $(window).height() -> Scroll reachs footer
			  	if($(window).scrollTop() >= footerp.top - $(window).height()) {
					if(!load_more_triggered) {
						load_more_triggered=true;
						//console.log('- - - - - - -');
						//console.log('Scroll');

						$('.load-more').trigger('click');
					}
			  	}
				else {
					load_more_triggered=false;
				}
		  	});
		  }

		  return false;

		},

		/*
		* Form functions
		*/
		validateSubmit: function(){
			CDG.validate();
			$('input').on('keyup', CDG.validate);
			$("textarea").on('keyup', CDG.validate);
			$("input[type='file']").on('change', CDG.validate);
			$('.select-styled').on('change', CDG.validate);
		},

		validate: function(){
			var inputsWithValues = 0, myRequiredInputs = 0, myRequiredValues = 0;
			var selectedMonth = false, selectedDay = false;

			// get all input fields
			$('.wpcf7-form').find("input:not([type='submit'], [type='hidden']), select, textarea").each(function(){
			    if ($(this).val()) {
					inputsWithValues++;
				}
			    if( (typeof $(this).attr('aria-required') !== 'undefined') && ( $(this).attr('aria-required') == "true") ) { //is required
					myRequiredInputs++;
					// if it has a value, increment the counter
					if ($(this).val()) {
						myRequiredValues++;
					} else {
						//console.log($(this).attr('placeholder') + " empty");
					}
			    }


				//Months and days
				if(typeof $(this).attr("name") !== 'undefined'){
					var fieldName = $(this).attr("name");
					if(fieldName == 'assigned-month') {
						var $listDay = $('.assigned-day ul.select-options');
						var thirtyMonths = ["September","April","June","November"];

						selectedMonth = $(this).children("option:selected").val();
						$listDay.removeClass("twenty-eight");
						$listDay.removeClass("thirty");
						if(thirtyMonths.includes(selectedMonth)){
							$listDay.addClass("thirty");
						}
						if(selectedMonth == "February"){
							$listDay.addClass("twenty-eight");
						}
					}
					if(fieldName == 'assigned-day') {
						selectedDay = $(this).children("option:selected").val();
					}
				}
			});

			// console.log('inputsWithValues ' + inputsWithValues);
			// console.log('myRequiredInputs ' + myRequiredInputs);
			// console.log('myRequiredValues ' + myRequiredValues);


			$("input[type=submit]").prop("disabled", true);
			$(".right > div").addClass("overlay-submit");

			if ( inputsWithValues == 0 && myRequiredInputs == 0 ) {
				//No required values in form, all fields can be empty
				$("input[type=submit]").prop("disabled", false);
				$(".right > div").removeClass("overlay-submit");
				$('.left').removeClass('pink-text');
			}

			if ( inputsWithValues > 0 && myRequiredInputs == myRequiredValues ) {
				//All required values are filled
				$("input[type=submit]").prop("disabled", false);
				$(".right > div").removeClass("overlay-submit");
				$('.left').removeClass('pink-text');
			}

			if($('.form-body').hasClass('contact-details')){
				$("input[type=submit]").prop("disabled", false);
				$(".right > div").removeClass("overlay-submit");
				$('.left').removeClass('pink-text');
			}

			//console.log(' selectedMonth: '+selectedMonth);
			if(selectedDay && selectedMonth) {
				var qDate = selectedDay+"_"+selectedMonth.substring(0, 3);
				var fDates = $('.wpcf7-form input[name="forbidden-dates"]').val();
				var $datesMessage = $('.wpcf7-form .forbidden_date_col');

				if(fDates.indexOf(qDate) !== -1) {
					$("input[type=submit]").prop("disabled", true);
					$(".right > div").addClass("overlay-submit");
					$datesMessage.addClass("show");
				} else {
					$datesMessage.removeClass("show");
				}
			}

			//Count words on texareas
			$("textarea").on('keyup', function() {
				//console.log(this.value);

				//Remove double quotation marks
				var quoted = $(this).val().replace(/"/g, "'");
				//console.log(quoted);
				$(this).val(quoted);

				if(this.value.trim() != '') {
					var max_words = 35;
				    var words = this.value.trim().match(/\S+/g).length;

				    if (words > max_words) {
				      // Split the string on first 200 words and rejoin on spaces
				      var trimmed = $(this).val().split(/\s+/, max_words).join(" ");
				      // Add a space at the end to make sure more typing creates new words
				      $(this).val(trimmed + " ");
				    }
				    else {
				      //Display number of words left
				      //$('#word_left').text(max_words-words);
				    }
				}
			  });
		},
		contactForm7Events: function(){
			var first_name = false,redirect_url = false;

			document.addEventListener( 'wpcf7mailsent', function( event ) {
				//console.log(JSON.stringify(event.detail.inputs));
				jQuery.each(event.detail.inputs, function(i, val) {
				  //console.log(JSON.stringify(val));
				  if(val.name == "first-name"){
				  	first_name = val.value;
				  }
				  if(val.name == "redirect-url"){
				  	redirect_url = val.value;
				  }
				});
				if(redirect_url && first_name) {
					//console.log('New location: '+redirect_url +'?form_user_name='+first_name);
					location = redirect_url +'?form_user_name='+first_name;
				}
				/*
				{"id":"wpcf7-f32-p33-o1",
				"status":"mail_sent",
				"inputs":[
						{"name":"redirect-url","value":"/register/register-final/"},
						{"name":"email","value":"test@test.com"},
						{"name":"telephone","value":"123456"},
						{"name":"address1","value":"t"},
						{"name":"address2","value":"t"},
						{"name":"city","value":"t"},
						{"name":"postcode","value":"t"},
						{"name":"twitter","value":"t"},
						{"name":"instagram","value":"t"}],
				"formData":{},
				"contactFormId":"32",
				"pluginVersion":"5.1.3",
				"contactFormLocale":"en_US",
				"unitTag":"wpcf7-f32-p33-o1",
				"containerPostId":"33",
				"apiResponse":{"into":"#wpcf7-f32-p33-o1","status":"mail_sent","message":"Thank you [first-name]!"}}
				*/

			}, false );

			document.addEventListener( 'wpcf7submit', function( event ) {
			    $('body').addClass('fade-out');
			}, false );
		},


		dropdownNoPropagation: function(){
			$('body').on("click", ".dropdown-menu > li", function (e) {
			    e.stopPropagation();
			});
			$('.back-to-main').on('click', function(){
				$(this).closest('.dropdown-toggle').dropdown('toggle');
    		});

		},


		


        onreadyFunctions: function() {
			//CDG.addMSGonReady();

			CDG.handleHTMLVideo();
			CDG.cssElements();
			
			CDG.slickslider();

			//if ($.fn.jarallax && !$.isMobile()) {
			if (!$.isMobile()) {
				$(window).on('update.parallax', function(event) {
					setTimeout(function() {
						var $jarallax = $('.wn-parallax-background');
	
						$jarallax.jarallax('coverImage');
						$jarallax.jarallax('clipContainer');
						$jarallax.jarallax('onScroll');
					}, 0);
				});
	
				CDG.initParallax(document.body);
	
				// for Tabs
				//$(window).on('shown.bs.tab', function(e) {
				//	$(window).trigger('update.parallax');
				//});
			}


			//CDG.scrollEffects();
			//CDG.scrollWayPoint();
			
			//CDG.burgerEffects();
			//CDG.styleSelects();

			//CDG.dropdownNoPropagation();

			$(window).on('resize',function() {
				CDG.cssElements();
			});
		},


		//OnLoad functions

		addMSGonLoad: function() {
			console.log('Codigo Loaded');
        },

		onloadFunctions: function() {
            //CDG.addMSGonLoad();
        }
    };

    //On Ready
	CDG.onreadyFunctions();

	$(window).on('load', function() {
		CDG.onloadFunctions();
	});


})(jQuery);
