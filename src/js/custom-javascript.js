/*
scroll(0,0);
// If URL has anchor, go to top right away
if ( window.location.hash ) {
	scroll(0,0);
}
// Remove some browsers issue
setTimeout( function() { scroll(0,0); }, 1);
*/

console.log('custom');




(function($) {

	/*'use strict';*/

	var windowResized = false;
	var expanded = false;


	/* Youtube API */
		var tag = document.createElement('script');

		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		var YouPlayer=[];
		if ( $('[id^=youplayer]').length) {
			//Youtube functions
			//var youtubeID=$('[id^=youplayer]').data('video-url'); // youtube video id
			//console.log('youtubeID '+youtubeID);

			var onYouTubeIframeAPIReady = function () {
				$("[id^=youplayer]").each(function(){
						var youtubeID=$(this).data('video-url'); // youtube video id
						var playerID=$(this).attr('id'); // player id
						//console.log('youtubeID '+youtubeID);
						YouPlayer[youtubeID] = new YT.Player(playerID, {
							videoId: youtubeID,
							playerVars: {
								'autoplay': 0,
								'rel': 0,
								'showinfo': 0,
								'controls':0
							},
							events: {
								'onStateChange': onPlayerStateChange
							}
						});
					//console.log(JSON.stringify(player))
					//console.log('player.videoId '+player.b.c.videoId);
				});

			}
			/*
			var onYouTubeIframeAPIReady = function () {

				YouPlayer = new YT.Player('youplayer', {
					videoId: youtubeID,
					playerVars: {
						'autoplay': 0,
						'rel': 0,
						'showinfo': 0,
						'controls':0
					},
					events: {
						'onStateChange': onPlayerStateChange
					}
				});
				//console.log(JSON.stringify(player))
				//console.log('player.videoId '+player.b.c.videoId);
			}
			*/

			var onPlayerStateChange = function (event) {
				if (event.data == YT.PlayerState.ENDED) {
					$('.start-video').fadeIn('normal');
				}
			}


		}

		else if ($("#rsYouVideo").length) {
			var onYouTubeIframeAPIReady = function () {

				YouPlayer = new YT.Player('rsYouVideo', {
					playerVars: {
						'rel': 0,
						'showinfo': 0,
						'controls':0
					}
				});
				//console.log(JSON.stringify(player))
				//console.log('player.videoId '+player.b.c.videoId);
			}
		}


		function onPlayerReady() {
			//alert ('im ready');
			//YouPlayer.playVideo();
			// Mute!
			//YouPlayer.mute();
		}
		function youPlayerPlay() {
			//console.log('Checking if YouPlayer is defined');
			if(typeof YouPlayer !== 'undefined') {
				YouPlayer.playVideo();
				//console.log('Playing Youtube in carousel muted');
				YouPlayer.mute();
			} else {
				//console.log('YouPlayer is not defined. Playing Youtube in carousel after 1 seconds');
				setTimeout(function() {
					youPlayerPlay();
				}, 1000);
			}
		}
		function youPlayerPause() {
			YouPlayer.pauseVideo();
		}

	/* !Youtube API */


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

		handleVideoYoutube: function() {

			//It will be loaded if there is a Youtube player
			if ($('[id^=youplayer]').length) {
				$('[id^=youplayer]').each(function(i, obj) {

					var videoID = $(this).attr('id');
					videoID = videoID.replace("youplayer_", "");

					$(document).on('click', '.start-video', function () {

						$(this).attr('id', 'currentPlayButton');
						$(this).hide();
						$(this).closest('.box_video').addClass('bfHidden').attr('id', 'currentYouPlay');

						var youtubeID, playerID="youplayer";

						console.log(playerID);

						if(typeof $(this).data('video-id') !== 'undefined') {
							youtubeID=$(this).data('video-id'); // youtube video id
							playerID="youplayer_"+youtubeID;

						} else {
							youtubeID=$(this).closest('.box_video').find('#youplayer').data('video-url');
						}
						console.log(youtubeID);
						YouPlayer[youtubeID].playVideo();
						$('#'+playerID).show();

						//player[youtubeID].playVideo();

						if(typeof $(this).data('totalwidth') !== 'undefined') {
							$(this).closest('.box_video').parent().addClass('totalwidth');
						}
						if(typeof $(this).data('sm-full') !== 'undefined') {
							var displayWidth = $(window).width();
							if(displayWidth<768) {
								fullScreen('currentYouPlay');
							}
						}
					});

					$(document).on('click', '#stop-youtube_'+videoID, function () {

						var youtubeID, playerID="youplayer";

						if(typeof $(this).data('video-id') !== 'undefined') {
							youtubeID=$(this).data('video-id'); // youtube video id
							playerID="youplayer_"+youtubeID;
						} else {
							youtubeID=$(this).closest('.box_video').find('#youplayer').data('video-url');
						}
						YouPlayer[youtubeID].stopVideo();
						$('#'+playerID).hide();

						$(this).closest('.box_video').attr('id', '');
						$(this).closest('.box_video').parent().removeClass('totalwidth');

						$('#currentPlayButton').fadeIn('normal');
						$('#currentPlayButton').attr('id', 'start-youtube_'+videoID);

					});

					$('#youplayer_'+videoID).hide();
				});
			}
		},

		youtubeModal: function(){

			/* Assign empty url value to the iframe src attribute when
			modal hide, which stop the video playing */
			$('.video-modal').on('hidden.bs.modal', function(){
					$('#' + this.id + " iframe").attr('src', '');
			});

				/* Assign the initially stored url back to the iframe src
				attribute when modal is displayed again */
				//var trigger = $("body").find('.modal-link');
			$('.modal-link').click( function(){
					var theModal = $(this).attr("data-target");
					var url = $(theModal + ' iframe' ).attr('data-url');

					$(theModal + " iframe").attr('src', url);
			});
		},

		handleVideoVimeo: function() {
			//It will be loaded if there is a Vimeo player
          	if ($(".vimeoplayer").length) {
				var iframe=[];
				var player=[];

				var url = "https://f.vimeocdn.com/js/froogaloop2.min.js";
				$('.vimeoplayer').each(function(i, obj) {
					var videoID = $(this).attr('id');
						videoID = videoID.replace("vimeoplayer_", "");
						//console.log('videoID '+videoID);

					$.getScript( url, function() {
						iframe[i] = $('#vimeoplayer_'+videoID)[0];
						player[i] = $f(iframe[i]);
						// When the player is ready, add listeners for pause, finish, and playProgress
						player[i].addEvent('ready', function() {
							//status.text('ready');

							player[i].addEvent('pause', onPause);
							player[i].addEvent('finish', onFinish);
							player[i].addEvent('playProgress', onPlayProgress);
						});

						// Call the API when a button is pressed
						$('#start-vimeo_'+videoID).bind('click', function() {
							$(this).hide();
							$(this).closest('.box_video').addClass('bfHidden');
							$('#vimeoplayer_'+videoID).show();
							player[i].api('play');

							if(typeof $(this).data('totalwidth') !== 'undefined') {
								$(this).closest('.box_video').parent().addClass('totalwidth');
							}
							/*
							if(typeof $(this).data('sm-full') !== 'undefined') {
								var displayWidth = $(window).width();
								if(displayWidth<768) {
									fullScreen('currentYouPlay');
								}
							}
							*/
						});
						$('#stop-vimeo_'+videoID).bind('click', function() {

							player[i].api('pause');

							$('#start-vimeo_'+videoID).show();
							$(this).closest('.box_video').removeClass('bfHidden');
							$(this).closest('.box_video').parent().removeClass('totalwidth');

							$('#vimeoplayer_'+videoID).hide();
							//alert('stop');

						});

						$('#vimeoplayer_'+videoID).hide();

						//Show status (debuggin)
						//$('#vimeoplayer').after('<div><p>Status: <span class="status">&hellip;</span></p></div>');
						//var status = $('.status');

						//status.text('starting ...');

						function onPause() {
							//status.text('paused');
						}

						function onFinish() {
							//status.text('finished');
						}

						function onPlayProgress(data) {
							//status.text(data.seconds + 's played');
						}

					});

					//var tag = document.createElement('script');
					//tag.src = "https://f.vimeocdn.com/js/froogaloop2.min.js";
					//var firstScriptTag = document.getElementsByTagName('script')[0];
					//firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

					//var tag = document.createElement('script');
					//tag.src = "https://f.vimeocdn.com/js/froogaloop2.min.js";
					//var numScriptTag = document.getElementsByTagName('script').length -1;
					//var lastScriptTag = document.getElementsByTagName('script')[numScriptTag];
					//lastScriptTag.parentNode.insertBefore(tag, lastScriptTag);



					/*
					$(document).on('click', '.start-video', function () {
						$(this).hide();
						$("#youplayer").show();
						player.playVideo();
					});

					$("#youplayer").hide();
					*/
				});
			}
        },


		royalSliderCarousels: function() {

			if ($('#royal-slider-carousel').length) {
				var vautoplay=$('#royal-slider-carousel').data('autoplay');
				var vloop=$('#royal-slider-carousel').data('loop');
				var vdelay=$('#royal-slider-carousel').data('delay');

				var slider = $('#royal-slider-carousel').royalSlider({
					addActiveClass: true,
					arrowsNav: false,
					controlNavigation: 'none',
					autoScaleSlider: false,
					// autoScaleSliderWidth: 960,
					// autoScaleSliderHeight: 328,
					loop: vloop,
					fadeinLoadedSlide: false,
					globalCaption: false,
					keyboardNavEnabled: true,
					globalCaptionInside: false,
					autoPlay: {
						// autoplay options go gere
						enabled: vautoplay,
						pauseOnHover: true,
						delay: vdelay
					}
				}).data('royalSlider');


				// previous button
				$('.owl-prev').on('click', function(){
				   //$('.owl-next').removeClass('disabled');
				   //console.log('prev');
				   //console.log(slider.currSlideId + ' of ' + slider.numSlides);
				   slider.prev();
				});
				$('.owl-next').on('click', function(){
				   //console.log('next');
				   //console.log(slider.currSlideId + ' of ' + slider.numSlides);
				   //$('.owl-prev').removeClass('disabled');
				   slider.next();
				});



				if(!vloop){
					// previous button
					$('.owl-prev').addClass('disabled'); //At first is disabled
					slider.ev.on('rsAfterSlideChange', function(event) {
						// triggers after slide change
						$('.owl-prev').removeClass('disabled');
						$('.owl-next').removeClass('disabled');
						if(slider.currSlideId == 0) {
						   $('.owl-prev').addClass('disabled');
					   }
					   if(slider.currSlideId >= (slider.numSlides-1)) {
						   $('.owl-next').addClass('disabled');
					   }
					});
				}
			}

			/* VIDEO Functions */
			if ($('.royal-video-carousel').length) {

				var prevSlideHTMLVideo, prevSlideYoutubeVideo=false;

				var playSlideVideos = function() {

					//console.log('Triggering playSlideVideos...');

					//Pausing videos on change slide
					if(prevSlideHTMLVideo) {
					  prevSlideHTMLVideo.pause();
					}
					if(prevSlideYoutubeVideo) {
					  youPlayerPause();
					  //NOTE: This is launching this warning on console:
					  //AbortError: The fetching process for the media resource was aborted by the user agent at the user's request.
					}

					//Autoplay HTML videos
					// (REMEMBER VIDEO MUTED https://webkit.org/blog/6784/new-video-policies-for-ios/)

					if(typeof slider.currSlide.content.find('video') !== 'undefined') {
						var video = slider.currSlide.content.find('video');

						if(video.length) {
						  prevSlideHTMLVideo = video[0];
						  prevSlideHTMLVideo.play();
						  //prevSlideHTMLVideo.prop('muted', true); //mute
						  prevSlideHTMLVideo.controls = false;
						} else {
						  prevSlideHTMLVideo = null;
						}
					}


					//Autoplay Youtube videos
					if(typeof slider.currSlide.content.find('iframe') !== 'undefined') {
						var iframe = slider.currSlide.content.find('iframe');

						//console.log('Youtube '+iframe.length);
						//console.log(JSON.stringify(iframe));

						if(iframe.length) {
							//console.log(iframe);
							//console.log(jQuery(iframe).attr('id'));
							if(jQuery(iframe).attr('id')=='rsYouVideo') {
								//console.log('Loading youtube functions...');
								prevSlideYoutubeVideo=true;
								youPlayerPlay();
							}
							else {
								//YouPlayer.pauseVideo();
							}
						}
					}


				};

				slider.ev.on('rsAfterSlideChange', playSlideVideos);
				playSlideVideos();
			}
		},
		slickslider: function(){
			if ($('.slick-carousel').length) {
				var $carousel = $('.slick-carousel');
				var autoplay = $carousel.data('autoplay'), dots = $carousel.data('dots');
				$carousel.slick({
					lazyLoad: 'ondemand',
					autoplay: autoplay,
					dots: dots
				});
			}

			if ($('.slick-images_slider').length) {
				var $slider = $('.slick-images_slider');
				var autoplay = $slider.data('autoplay'), arrows = $slider.data('arrows');
				$slider.slick({
					lazyLoad: 'ondemand',
					autoplay: autoplay,
					dots: false,
					arrows: arrows,
					prevArrow: '<button class="slick-prev slick-arrow">Prev</button>',
					nextArrow: '<button class="slick-next slick-arrow">Next</button>'
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

		elementsMatchHeight: function() {
			//https://github.com/liabru/jquery-match-height
			//Same heights boxes
			$('.same-height').matchHeight({
				byRow: false,
				property: 'height',
				target: null,
				remove: false
			});

			$('.same-height-row').matchHeight({
				byRow: true,
				property: 'height',
				target: null,
				remove: false
			});
        },
		keepProportions: function() {

			var displayWidth = $(window).width();

			if(displayWidth<768 && !windowResized) {
				//console.log('Resized');
				windowResized=true;
			}

			//keep_prop to have always custom fixed dimensions
			$(".keep_prop").each(function(){

				var displayWidth = $(window).width(),
				img_w = jQuery(this).width(),
				ratiow = jQuery(this).data('ratiow'),
				ratioh = jQuery(this).data('ratioh');

				var img_h=Math.floor(ratioh*img_w/ratiow);

				if(typeof jQuery(this).data('ratiomw') !== 'undefined' && displayWidth < 768){
					ratiomw = jQuery(this).data('ratiomw'),
					ratiomh = jQuery(this).data('ratiomh');

					img_h=Math.floor(ratiomh*img_w/ratiomw);

				}


				jQuery(this).css('height',img_h+'px');
			});

			//Images on mosaics
			$(".mosaic__img").each(function(){
				$img12=jQuery(this).find('.mosaic__img_v12');
				$img12_height = $img12.height();

				jQuery(this).find('.mosaic__img_v6').each(function(){
					var img_h=Math.floor($img12_height/2) - 15;
					jQuery(this).css('height',img_h+'px');
				});
			});

			//Space for floating titles
			$('.left_content').each(function(){
				var displayWidth = $(window).width(),
				$vtitlewrap=$(this);
				$vtitle=$('.left_content__title', this);

				var titleWidth = $vtitle.width();

				if(displayWidth>767) {
					$vtitlewrap.closest('.container').css('min-height', titleWidth+'px');
				}

			});

			//Search Input CSS
			CDG.searchInputCSS();

			//Launch MatchHeight
			//CDG.elementsMatchHeight();
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
            CDG.addMSGonReady();

			//CDG.handleVideoYoutube();
			//CDG.youtubeModal();
			//CDG.handleVideoVimeo();

			//CDG.scrollEffects();
			//CDG.scrollWayPoint();
			
			//CDG.burgerEffects();
			//CDG.keepProportions();
			//CDG.styleSelects();
			//CDG.slickslider();

			//CDG.dropdownNoPropagation();

      		$(window).resize(function() {
				//CDG.keepProportions();
			});
		},


		//OnLoad functions

		addMSGonLoad: function() {
			console.log('Codigo Loaded');
        },

		playYoutubeOnLoad: function() {
			//Play Youtube videos on load
			if ($('.box__autoplay').length) {
				$('.box__autoplay').find('.start-video').each(function(){
					$(this).trigger( "click" );
					$(this).addClass( "loop" );
					$(this).siblings( ".mute-video" ).trigger( "click" );
				});
			}
        },

		onloadFunctions: function() {
            CDG.addMSGonLoad();
			//CDG.playYoutubeOnLoad();

        }
    };

    $(window).ready(function() {
        CDG.onreadyFunctions();
    });

	$(window).on('load', function() {
		CDG.onloadFunctions();
	});


})(jQuery);
