/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	var myCustomizer = window.parent.window.wp.customize;

	console.log(php_obj.current_theme);
	
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	
	// Header text hide and show and text color.
	wp.customize( 'header_textcolor', function( value ) {
		if(value() == 'blank'){
			myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.hide();
		}else{
			myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.show();
		}
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
				$( '.site-branding-text ' ).addClass('d-none');
				myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.hide();
			} else {
				$('.site-title').css('position', 'unset');
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-branding-text ' ).removeClass('d-none');
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
				myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.show();
			}
		} );
	} );
	
	// Site Title Font Size.
	wp.customize( `${php_obj.current_theme}_title_font_size`, function( value ) {
		value.bind( function( newVal ) {
			$( '.site-title a' ).css( {
				'font-size': newVal+'px',
			} );
		} );
	} );
	
	// Sidebar Width.
	wp.customize( 'blogarise_theme_sidebar_width', function( value ) {
		value.bind( function( newVal ) {
			var contentRightElements = document.querySelectorAll('.content-right');
			var Rightsidebar = document.querySelectorAll('.sidebar-right');
			var Leftsidebar = document.querySelectorAll('.sidebar-left');
			contentRightElements.forEach(function(element) {
				element.style.setProperty('width', `calc((1130px - ${newVal}px))`, 'important');
			});
			Rightsidebar.forEach(function(element) {
				element.style.setProperty('width', `${newVal}px`, 'important');
			});
			Leftsidebar.forEach(function(element) {
				element.style.setProperty('width', `${newVal}px`, 'important');
			});
		} );
	} );
	
	// Footer logo width.
	wp.customize( 'blogarise_footer_logo_width', function( value ) {
		value.bind( function( newVal ) {
			$( 'footer .footer-logo img' ).css( {
				'width': newVal+'px',
			} );
		} );
	} );
	
	// Footer logo Height.
	wp.customize( 'blogarise_footer_logo_height', function( value ) {
		value.bind( function( newVal ) {
			$( 'footer .footer-logo img' ).css( {
				'height': newVal+'px',
			} );
		} );
	} );

	// Header Banner, Site Title and Site Tagline Center Alignment.
	wp.customize( 'blogarise_center_logo_title', function( value ) {
		value.bind( function( newVal ) {
			var firstChild = $('.bs-header-main .row.align-items-center').children(':nth-child(1)');
			var secondChild = $('.bs-header-main .row.align-items-center').children(':nth-child(2)');	
			if(newVal == true){
				if(firstChild.hasClass('text-md-start d-lg-block col-md-4')){
					firstChild.removeClass('text-md-start d-lg-block col-md-4');
				} 
				firstChild.addClass('d-lg-block col-md-12 text-center mx-auto');

				if(secondChild.hasClass('col-lg-8')){
					secondChild.removeClass('col-lg-8');
				} 
				secondChild.addClass('col text-center mx-auto');
				
				if(secondChild.children(':nth-child(1)').hasClass('text-md-end')){
					secondChild.children(':nth-child(1)').removeClass('text-md-end');
				} 
				secondChild.children(':nth-child(1)').addClass('text-center');
			}else{
				if(firstChild.hasClass('d-lg-block col-md-12 text-center mx-auto')){
					firstChild.removeClass('d-lg-block col-md-12 text-center mx-auto');
				} 
				firstChild.addClass('text-md-start d-lg-block col-md-4');

				if(secondChild.hasClass('col text-center mx-auto')){
					secondChild.removeClass('col text-center mx-auto');
				} 
				secondChild.addClass('col-lg-8');
				
				if(secondChild.children(':nth-child(1)').hasClass('text-center')){
					secondChild.children(':nth-child(1)').removeClass('text-center');
				} 
				secondChild.children(':nth-child(1)').addClass('text-md-end');
			}
			console.log(newVal);
		} );
	} );

	// Footer Widget Area color.
	wp.customize( 'blogarise_footer_column_layout', function( value ) {
		var colum = 12 / value();
		var wclass = $('.animated.bs-widget');
		if(wclass.hasClass('col-md-12')){
			wclass.removeClass('col-md-12');
		}else if(wclass.hasClass('col-md-6')){
			wclass.removeClass('col-md-6');
		}else if(wclass.hasClass('col-md-4')){
			wclass.removeClass('col-md-4');
		}else if(wclass.hasClass('col-md-3')){
			wclass.removeClass('col-md-3');
		}
		wclass.addClass(`col-md-${colum}`);

		value.bind( function( newVal ) {
			colum = 12 / newVal;
			wclass = $('.animated.bs-widget');
			if(wclass.hasClass('col-md-12')){
				wclass.removeClass('col-md-12');
			}else if(wclass.hasClass('col-md-6')){
				wclass.removeClass('col-md-6');
			}else if(wclass.hasClass('col-md-4')){
				wclass.removeClass('col-md-4');
			}else if(wclass.hasClass('col-md-3')){
				wclass.removeClass('col-md-3');
			}
			wclass.addClass(`col-md-${colum}`);
			console.log(wclass);
		} );
	} );
	
	// Featured Links Background overlay Image.
	wp.customize( 'fatured_post_image_one', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('.promoss .one .bs-widget.promo').css('background-image', 'url(' + newVal + ')');
			}else{
				$('.promoss .one .bs-widget.promo').removeAttr('style');
			}
		} );
	} );
	wp.customize( 'fatured_post_image_two', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('.promoss .two .bs-widget.promo').css('background-image', 'url(' + newVal + ')');
			}else{
				$('.promoss .two .bs-widget.promo').removeAttr('style');
			}
		} );
	} );
	wp.customize( 'fatured_post_image_three', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('.promoss .three .bs-widget.promo').css('background-image', 'url(' + newVal + ')');
			}else{
				$('.promoss .three .bs-widget.promo').removeAttr('style');
			}
		} );
	} );

	// Top Bar Background Color.
	wp.customize( 'top_bar_header_background_color', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('.bs-head-detail').css('background', newVal);
				$('.mg-latest-news .bn_title').css('background', newVal);
			}else{
				$('.bs-head-detail').css('background', '');
				$('.mg-latest-news .bn_title').css('background', '');
			}
		} );
	} );
	// Footer Background Image
	wp.customize( 'blogarise_footer_widget_background', function( value ) {
		value.bind( function( newVal ) {
			if(newVal !== ''){
				$('footer.footer').css('background-image', 'url(' + newVal + ')');
				$('footer.footer').addClass('back-img');
			}else{
				$('footer.footer').removeAttr('style');
				$('footer.footer').removeClass('back-img');
			}
		});
	});
	wp.customize( 'scrollup_layout', function( value ) {
		value.bind( function( newVal ) {
			$('.bs_upscr i').removeClass();
			$('.bs_upscr i').addClass(newVal);
		});
	});
	function customizePreviewStyle(settingId, selector, property, unit) {
		wp.customize(settingId, function(value) {
			value.bind(function(newVal) {
				let cssProperties = {};
				cssProperties[property] = newVal + unit;
				$(selector).css(cssProperties);
			});
		});
	}

	customizePreviewStyle('blogarise_footer_text_color', 'footer .bs-widget p, .site-title-footer a, .site-title-footer a:hover, .site-description-footer, .site-description-footer:hover, footer .bs-widget h6, footer .mg_contact_widget .bs-widget h6, footer .bs-widget ul li a', 'color','');
	customizePreviewStyle('blogarise_footer_overlay_color', 'footer.footer .overlay', 'background-color','');
	customizePreviewStyle('blogarise_footer_copy_text', 'footer .bs-footer-copyright p, footer .bs-footer-copyright a', 'color','');
	customizePreviewStyle('blogarise_footer_copy_bg', 'footer .bs-footer-copyright', 'background-color','');
} )( jQuery );
