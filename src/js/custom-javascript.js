const Rellax = require('Rellax');
import slick from '../../slick/slick.min';
import waypoint from '../../waypoints/jquery.waypoints.min';

let CDG;

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
		 $action='cdgajax_results'; //loads 'wp_ajax_nopriv_cdgajax_results' and 'wp_ajax_cdgajax_results' hooks defined in inc/custom_ajax.php
		if( typeof $custom_query['medium']!== 'undefined')  $action='cdgajax_mediums'; //loads 'wp_ajax_nopriv_cdgajax_mediums' and 'wp_ajax_cdgajax_mediums' hooks defined in inc/custom_ajax.php

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

	let rellax;
	let reachedBottom = false;
	let automaticScroll = false;


	CDG = {

		//OnReady functions

        addMSGonReady: function() {
            console.log('Codigo Ready');
		},
		
		handleHTMLVideo: function() {
			if ($('.component_video.controlled').length) {
				
				$('.component_video.controlled').find('video').each(function(){

					var htmlVideo     = $(this).get(0); 
					var displayScreen = 'mobile'; 
					if($(this).attr('class').indexOf('desktop_video') !== -1 ) {
						displayScreen = 'desktop'; 
					}
					var classButton = 'button.video_control.'+displayScreen+'_button';
					//console.log($(this).attr('class'));


					var $playButton = null, $wrapperVideo = null;
					if($(this).parent().find('button.video_control').length) {
						$playButton = $(this).parent().find(classButton); 
					} else {
						if($(this).closest('.section').find('button.video_control').length) {
							$playButton   = $(this).closest('.section').find(classButton); 
							if($(this).closest('.fp-bg--video-headline').length) {
								$wrapperVideo = $(this).closest('.fp-bg--video-headline');
							} 
						}
					}
					//console.log($playButton);
					

					
					htmlVideo.onplaying = function() {
						$playButton.removeClass("paused");
						if($wrapperVideo.length) {
							$wrapperVideo.addClass("front");
						}
					};
					
					htmlVideo.addEventListener("pause", function() {
						$playButton.addClass("paused");
						if($wrapperVideo.length) {
							$wrapperVideo.removeClass("front");
						}
					}, true);
					
					$(this).on('click',function(){
						//console.log('video clicked');
						CDG.playPauseHTMLVideo($playButton,htmlVideo);
					});
					$playButton.on('click',function(){
						//console.log('button clicked');
						CDG.playPauseHTMLVideo($playButton,htmlVideo);
					});
				});
			}
		},
		playPauseHTMLVideo: function($playButton,htmlVideo) {
			if ($playButton.hasClass("paused")){
				//console.log('play');
				CDG.playHTMLVideo($playButton,htmlVideo);
			} else {
				//console.log('pause');
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
		
		slickslider: function($carousel){

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

			//console.log('carousel dots '+dots);


			var slickOptions = {
				lazyLoad: 'ondemand',
				infinite: true,
				autoplay: autoplay,
				pauseOnHover : false,
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


			if($carousel.find('.nextbutton').length) {
				$carousel.find('.nextbutton').on('click',function(){
					$carousel.slick('slickNext');
				})
			}
			if($carousel.find('.prevbutton').length) {
				$carousel.find('.prevbutton').on('click',function(){
					$carousel.slick('slickPrev');
				})
			}

			
		},
		slicksAllSliders: function(){
			//console.log('launching slick');
			if ($(document.body).find('.slick-carousel').length) {
				//console.log('loading carousels');
				var $carousels = [];
				$(document.body).find('.slick-carousel').each(function(){

					var $carousel = $(this);
					CDG.slickslider($carousel);
		        	$carousels.push($carousel);

		    	});

			}
		},



		/* Scroll */
		scrollEffects: function() {

			var prevScrollpos = window.pageYOffset;
			var displayWidth = $(window).width();
			var displayHeight = $(window).height();

			//if there is an anchor on the URL
			if(window.location.hash) {
				scroll(0,0);
				// smooth scroll to the anchor id
				setTimeout( function() {
					$('html, body').animate({
						scrollTop: $(window.location.hash).offset().top + 'px'
					}, 1000, 'swing');
				}, 100);
				automaticScroll = true;

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


			$(window).on('scroll',function (event) {
				var vscroll = $(document).scrollTop();
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


				if($(window).scrollTop() + $(window).height() > $(document).height() - 300) {
					//console.log("reachedBottom "+reachedBottom);
					if(!reachedBottom && !automaticScroll) {
						//console.log("resize!");
						$(window).trigger('resize');
						reachedBottom = true;
					} 
				}
			});


			//Vertical Scroll
			const scrollStep = 5;
			let scrollHandle = [], i = 0;

			$('body').find('.horizontal_scroll').each(function(){
				
				//console.log('i '+i);
				let element = $(this);
				scrollHandle[i] = 0;
			
				//Start the scrolling process
				$(".h_scroll-arrow").on("mouseenter", function () {
					let data = $(this).data('scrollModifier'),
						direction = parseInt(data, 10);        
					startScrolling(element, direction, i);
				});
			
				//Kill the scrolling
				$(".h_scroll-arrow").on("mouseleave", function () {
					stopScrolling(i);
				});

				i++;
			});
		
			//Actual handling of the horizontal scrolling
			function startScrolling(element,modifier,i) {
				//console.log(scrollHandle[i]);
				if (scrollHandle[i] === 0 || typeof scrollHandle[i] === 'undefined') {
					scrollHandle[i] = setInterval(function () {
						var newOffset = element.scrollLeft() + (scrollStep * modifier);
						element.scrollLeft(newOffset);
					}, 20);
				}
			}
		
			function stopScrolling(i) {
				clearInterval(scrollHandle[i]);
				scrollHandle[i] = 0;
			}
			
			
		},

		scrollWayPoint: function() {
			/*
		    var waypoint = new Waypoint({
			  element: document.getElementById('waypoint_anchor'),
			  handler: function() {
			    console.log('Basic waypoint triggered')
			  }
			});
			*/

			//console.log('scrollWayPoint');

			var $headerNav = $("header.header .navbar");

			function changeMenuColor(dcolor,navscroll) {
				
				$headerNav.removeClass('bg-white');
				$headerNav.removeClass('bg-green');
				$headerNav.removeClass('bg-transparent');
				$headerNav.removeClass('scroll-disabled');

				$headerNav.addClass(dcolor).addClass(navscroll);
				//console.log('menu color '+dcolor);
			}


			//console.log('Starting Waypoint');

			$('body').find('.section:not(".d-none")').each(function(){

				//console.log('Section found');
				var $section = $(this); 

				/* Change Menu Color
				$section.waypoint(function(direction) {
					if (direction === 'down') {
						//console.log('Section ('+$section.attr("id")+') top touched screen top');
						
						var dcolor = $section.data('menucolor'), navscroll = null;
						if($section.attr('class').indexOf('navscroll-disabled') !== -1){
							navscroll = 'scroll-disabled';
						}
						changeMenuColor(dcolor,navscroll);
					}
				}, {
					offset: '10%'
				})
				*/

				$section.waypoint(function(direction) {
					if (direction === 'down') {
						//console.log('Section ('+$section.attr("id")+') top bottom screen top');
						$section.addClass('active'); //Triggers section animation

						if($section.hasClass('section__carousel')) { //For some reason the slick carousel is breaking waypoint(). Resizing the window will solve it
							if(!automaticScroll) {
								//console.log("resize!");
								$(window).trigger('resize');
								reachedBottom = true;
							} 
						}
						
					}
				}, {
					offset: '90%'
				})

				/* Change Menu Color
				$section.waypoint(function(direction) {
					if (direction === 'up') {
						//console.log('Section ('+$section.attr("id")+') bottom touched screen top');
						var dcolor = $section.data('menucolor'), navscroll = null;
						if($section.attr('class').indexOf('navscroll-disabled') !== -1){
							navscroll = 'scroll-disabled';
						}
						changeMenuColor(dcolor,navscroll);
					}
				}, {
					offset: function() {
						return '-'+(this.element.offsetHeight - 50);
					}
				})
				*/


			});

			//Launch pop-up
			$('body').find('.modal-trigger').each(function(){

				var $modal = $(this); 
				$modal.waypoint(function(direction) {
					if (direction === 'down') {
						//console.log('modal-trigger touched screen top');

						var modalID = $modal.data('target'), modalUpdated = $modal.data('updated');
						var popCookie = CDG.getCookie('popupshowed');
						var createCookie = false;
						

						if( popCookie === null ) {
							createCookie = true;
						} else {
							//console.log('modal updated on  '+modalUpdated);  // Mon, 22 Mar 2021 20:10:09 GMT
							//console.log('popCookie created on  '+popCookie);  // Mon, 22 Mar 2021 20:03:59 GMT

							var t2 = Date.parse(modalUpdated);
							var t1 = Date.parse(popCookie);

							if(t2 > t1) {
								createCookie = true;
							}

							//var diffMinutes = parseInt((t2-t1)/(60*1000));
							//console.log('diffMinutes  '+diffMinutes);
						}

						if( createCookie ) {
							var now = new Date();
							var created = now.toUTCString();
							var time = now.getTime();
							var expireTime = time + 1000*3600*24*30; //One month
							now.setTime(expireTime);
							document.cookie = 'popupshowed='+created+';expires='+now.toUTCString()+';path=/;SameSite=Strict';
							//console.log(document.cookie);  // 'Wed, 21 Apr 2021 17:42:22 GMT'

							//console.log('show modal '+modalID);
							setTimeout(
								function() {
									CDG.modal(modalID, 'show');	
								}, 
							1500);	

						} else {
							//console.log('No show pop up'); 
						}
					}
				}, {
					offset: '75%'
				})
			});

				
			//Launch number counter
			$('body').find('.counter-trigger').each(function(){

				var $counter = $(this); 
				$counter.waypoint(function(direction) {
					if (direction === 'down') {
						//console.log('modal-trigger touched screen top');
						
						const countTo = $counter.data('end');
						$({
							countNum: $counter.text()
						}).animate({
							countNum: countTo
						},
						{
							duration: 3000,
							easing: 'swing',
							step: function() {
								$counter.text(Math.floor(this.countNum));
							},
							complete: function() {
								$counter.text(this.countNum);
								//alert('finished');
							}

						});


					}
				}, {
					offset: '90%'
				})
			});


			//Play video
			$('body').find('.video-trigger').each(function(){

				var $video = $(this); 
				
				$video.waypoint(function(direction) {
					if (direction === 'down') {
						//console.log('modal-trigger touched screen top');


						$video.find('.component_video.controlled video').each(function(){

							var htmlVideo = $(this).get(0); 
							var $playButton = $(this).parent().find('button.video_control'); 

							CDG.playHTMLVideo($playButton,htmlVideo);
		
						});
					}
				}, {
					offset: '75%'
				})

				
			});

		},

		/* Modal */
		modal: function(modalID, action = 'show'){
			if($(modalID).length) {
				$(modalID).modal(action)
			}
		},

		getCookie: function(cname) {
			var name = cname + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			//console.log('decodedCookie ' + decodedCookie)
			var ca = decodedCookie.split(';');
			for(var i = 0; i <ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return null;
		},



		/* Parallax */
		initRellax: function() {
			// https://dixonandmoe.com/rellax/
			//console.log('Starting Rellax');
			rellax = new Rellax('.rellax', {
				speed: -3,
				center: true
			});
		},
		destroyRellax: function() {
			// https://github.com/dixonandmoe/rellax#destroy
			//console.log('Destroying Rellax');
			rellax.destroy();
		},



		

		burgerEffects: function() {
			
			//Burger menu effect
			$(".navbar-toggler").on("click", function () {
				//$(this).toggleClass("active");
				const $navbar = $(this).closest('#navbarHeader');
				if($navbar.hasClass("back-dark")) {
					console.log('removing class');
					setTimeout(function () {
						console.log('now');
						$navbar.removeClass("back-dark");
					}, 500);
				}
				else {
					$navbar.addClass("back-dark");
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
				$styledSelect.on('click',function(e) {
					e.stopPropagation();
					$('div.select-div-styled.active').not(this).each(function(){
						$(this).removeClass('active').next('ul.select-options').hide();
					});
					$(this).toggleClass('active').next('ul.select-options').toggle();
				});
				$listItems.on('click',function(e) {
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
				$(document).on('click',function(event) {
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
		searchEffects: function() {
			//console.log('searchEffects loaded');
			//Display search form
			$(".search-link").on("click", function (event) {
				event.preventDefault();
				//console.log('search-link clicked');
				$('form#desktopSearch').toggleClass("active");
			});
			$(".search-close").on("click", function (event) {
				event.preventDefault();
				$('form#desktopSearch').removeClass("active");
			});

			$('.search-input').on("keyup",function () {
				if ($(this).val() == '') {
					$('button.search-submit').prop('disabled', true);
				} else {
					$('button.search-submit').prop('disabled', false);
				}
			});
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

		/* REST API */
		restApiFilters: function() {
			$('body').on("click", ".filters a", function (e) {
				e.preventDefault();
			});
			$('body').on("click", ".vue_lodaded", function (e) {
				e.preventDefault();
				if (!$.isMobile()) {
					//Rellax
					CDG.destroyRellax()
					CDG.initRellax();
				}
			});
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
			
			//CDG.slicksAllSliders(); //Loaded on Load

			CDG.scrollEffects();
			//CDG.scrollWayPoint(); //Loaded on Load
			
			
			//CDG.styleSelects();
			//CDG.searchEffects(); //Loaded on Load

			CDG.restApiFilters();

			//CDG.dropdownNoPropagation();

			$(window).on('resize',function() {
				//console.log('resizing')
				if(!automaticScroll) {
					CDG.scrollEffects();
				}
				CDG.cssElements();
			});
		},


		//OnLoad functions

		addMSGonLoad: function() {
			console.log('Codigo Loaded');
        },

		onloadFunctions: function() {
            //CDG.addMSGonLoad();
			CDG.scrollWayPoint();
			CDG.slicksAllSliders();
			//CDG.searchEffects();
			//CDG.burgerEffects();
			if (!$.isMobile()) {
				//Rellax
				CDG.initRellax();
			}
        }
    };

    //On Ready
	CDG.onreadyFunctions();

	$(window).on('load', function() {
		CDG.onloadFunctions();
	});


})(jQuery);

export {CDG}