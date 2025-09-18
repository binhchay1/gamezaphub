jQuery.fn.exists = function(callback) {
	var args = [].slice.call(arguments, 1);
	if (this.length) {
		callback.call(this, args);
	}
	return this;
};

/*----------------------------------------------------
/* Show/hide Scroll to top
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
	//move-to-top arrow
	jQuery("body").prepend("<a id='move-to-top' class='animate ' href='#blog'><i class='fa fa-angle-double-up'></i></a>");

	var scrollDes = 'html,body';
	/*Opera does a strange thing if we use 'html' and 'body' together so my solution is to do the UA sniffing thing*/
	if(navigator.userAgent.match(/opera/i)){
		scrollDes = 'html';
	}
	//show ,hide
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() > 160) {
			jQuery('#move-to-top').addClass('filling').removeClass('hiding');
		} else {
			jQuery('#move-to-top').removeClass('filling').addClass('hiding');
		}
	});
});


/*----------------------------------------------------
/* Make all anchor links smooth scrolling
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
 // scroll handler
	var scrollToAnchor = function( id, event ) {
		// grab the element to scroll to based on the name
		var elem = $("a[name='"+ id +"']");
		// if that didn't work, look for an element with our ID
		if ( typeof( elem.offset() ) === "undefined" ) {
			elem = $("#"+id);
		}
		// if the destination element exists
		if ( typeof( elem.offset() ) !== "undefined" ) {
			// cancel default event propagation
			event.preventDefault();

			// do the scroll
			// also hide mobile menu
			var headerHeight = $("#header.sticky-navigation").height() + 15; // Get fixed header height
			if( headerHeight > 0 ){
				var scroll_to = elem.offset().top-headerHeight;
			} else {
				var scroll_to = elem.offset().top;
			}
			$('html, body').removeClass('mobile-menu-active').animate({
							scrollTop: scroll_to
			}, 600, 'swing', function() { if (scroll_to > 46) window.location.hash = id; } );
		}
	};
	// bind to click event
	$("a").click(function( event ) {
		// only do this if it's an anchor link
		var href = $(this).attr("href");
		var exclude = ['#tab-description', '#tab-additional_information', '#tab-reviews'];
		if (exclude.includes(href)) {
			return;
		}
		if ( href && href.match("#") && href !== '#' ) {
			// scroll to the location
			var parts = href.split('#'),
				url = parts[0],
				target = parts[1];
			if ((!url || url == window.location.href.split('#')[0]) && target)
				scrollToAnchor( target, event );
		}
	});
});


/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive && mts_customscript.nav_menu != 'none') {
		jQuery(document).ready(function($){
				$('#secondary-navigation').append('<div id="mobile-menu-overlay" />');
				// merge if two menus exist
				if (mts_customscript.nav_menu == 'both' && !$('.navigation.mobile-only').length) {
						$('.navigation').not('.mobile-menu-wrapper').find('.menu').clone().appendTo('.mobile-menu-wrapper').hide();
				}
				$('nav').find('.header-social-icons').clone().appendTo('.mobile-menu-wrapper').hide();
				$('nav').find('.mts-cart').clone().appendTo('.mobile-menu-wrapper').hide();

				$('.toggle-mobile-menu').click(function(e) {
						e.preventDefault();
						e.stopPropagation();
						$('body').toggleClass('mobile-menu-active');

						if ( $('body').hasClass('mobile-menu-active') ) {
								if ( $(document).height() > $(window).height() ) {
										var scrollTop = ( $('html').scrollTop() ) ? $('html').scrollTop() : $('body').scrollTop();
										$('html').addClass('noscroll').css( 'top', -scrollTop );
								}
								$('#mobile-menu-overlay').fadeIn();
						} else {
								var scrollTop = parseInt( $('html').css('top') );
								$('html').removeClass('noscroll');
								$('html,body').scrollTop( -scrollTop );
								$('#mobile-menu-overlay').fadeOut();
						}
				});
		}).on('click', function(event) {

				var $target = jQuery(event.target);
				if ( ( $target.hasClass("fa") && $target.parent().hasClass("toggle-caret") ) ||  $target.hasClass("toggle-caret") || ( $target.attr('id') === 's' ) ) {// allow clicking on menu toggles
						return;
				}
				jQuery('body').removeClass('mobile-menu-active');
				jQuery('html').removeClass('noscroll');
				jQuery('#mobile-menu-overlay').fadeOut();
		});
}

/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) {
		let $pullToggle = $('#pull');
		function mtsDropdownMenu() {
				if(!$pullToggle.is(':visible')) {
						let timer;
						let delay = 100;
						$('.navigation li').hover(function (e) {
							if (e.type === 'mouseenter') {
								e.preventDefault();
								let $this = $(this);
								timer = setTimeout(function () {
									$this.children('ul.sub-menu, ul.children').slideDown('fast');
								}, delay);
							}
							else if (e.type === 'mouseleave') {
								e.preventDefault();
								$(this).children('ul.sub-menu, ul.children').hide(1);
								clearTimeout(timer);
							}
						});
						
						} else {
							$('.navigation li').unbind('mouseenter mouseleave');
							//$('.navigation li.active > ul.sub-menu, .navigation li.active > ul.children').show();
						}
		}

		mtsDropdownMenu();

		$(window).resize(function() {
				mtsDropdownMenu();
		});
});

/*---------------------------------------------------
/*  Vertical menus toggles
/* -------------------------------------------------*/
jQuery(document).ready(function($) {

		$('.widget_nav_menu, .navigation .menu').addClass('toggle-menu');
		$('.toggle-menu ul.sub-menu, .toggle-menu ul.children').addClass('toggle-submenu');
		$('.toggle-menu ul.sub-menu').parent().addClass('toggle-menu-item-parent');

		$('.toggle-menu .toggle-menu-item-parent').append('<span class="toggle-caret"><i class="fa fa-plus"></i></span>');

		$('.toggle-caret').click(function(e) {
				e.preventDefault();
				$(this).parent().toggleClass('active').children('.toggle-submenu').slideToggle('fast');
		});
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function($){
	(function(d, s) {
		var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
		};
	jQuery('span.facebookbtn, span.facebooksharebtn, .facebook_like').exists(function() {
		load('//connect.facebook.net/en_US/all.js#xfbml=1&version=v2.8', 'fbjssdk');
	});
	jQuery('span.twitterbtn').exists(function() {
		load('//platform.twitter.com/widgets.js', 'tweetjs');
	});
	jQuery('span.linkedinbtn').exists(function() {
		load('//platform.linkedin.com/in.js', 'linkedinjs');
	});
	jQuery('span.pinbtn').exists(function() {
		load('//assets.pinterest.com/js/pinit.js', 'pinterestjs');
	});
	jQuery('span.stumblebtn').exists(function() {
		load('//platform.stumbleupon.com/1/widgets.js', 'stumbleuponjs');
	});
	}(document, 'script'));
});

/*----------------------------------------------------
/* Lazy load avatars
/*---------------------------------------------------*/
if ( mts_customscript['lazy_load_comments'] ) {
		jQuery(document).ready(function($){
				var lazyloadAvatar = function(){
						$('.comment-author .avatar').each(function(){
								var distanceToTop = $(this).offset().top;
								var scroll = $(window).scrollTop();
								var windowHeight = $(window).height();
								var isVisible = distanceToTop - scroll < windowHeight;
								if( isVisible ){
										var hashedUrl = $(this).attr('data-src');
										if ( hashedUrl ) {
												$(this).attr('src',hashedUrl).removeClass('loading');
										}
								}
						});
				};
				if ($('.comment-author .avatar').length > 0) {
					$('.comment-author .avatar').each(function (i, el) {
						$(el).attr('data-src', el.src).removeAttr('src').addClass('loading');
					});
					$(function () {
						$(window).scroll(function () {
							lazyloadAvatar();
						});
					});
					//lazyloadAvatar();
				}
		});
}

/*
* Detect browser width for sticky navigation
*/
jQuery(document).ready(function ($) {
	// check mobile width
	function checkmobilesize() {
		var wi = $(window).width();
		if (wi <= 865) {
			$header = $('#header');
			if ($header.hasClass('sticky-navigation')) {
				$header.removeClass('sticky-navigation');
			}
			if ($header.hasClass('sticky-navigation-active')) {
				$header.removeClass('sticky-navigation-active');
			}
			$('#catcher').remove();
		}
	}
	// check desktop width
	function checkdesktopsize() {
		var wi = $(window).width();
		if (wi > 865) {
			$header = $('#header');
			if (!$header.hasClass('sticky-navigation')) {
				$header.addClass('sticky-navigation');
			}
		}
	}
	if ('0' == mts_customscript.mobile_sticky) {
		checkmobilesize();
	}
	$(window).resize(function ($) {
		if ('0' == mts_customscript.mobile_sticky) {
			checkmobilesize();
			if ('1' == mts_customscript.desktop_sticky) {
				checkdesktopsize();
			}
		}
	});
});
