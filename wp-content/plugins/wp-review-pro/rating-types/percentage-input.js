( function( $ ) {
	"use strict";

	$.fn.wprUserPercentageRating = function( options ) {
		var $wrapper, defaults;
		$wrapper = this;
		defaults = {
			rate: null
		};
		options = $.extend( {}, defaults, options );

		// Desktop script.
		$wrapper.find('.review-result').each(function() {
			var $this = $(this);
			$this.closest('.review-result-wrapper').data('originalwidth', $this[0].style.width);
		});

		$wrapper.find('.review-result-wrapper').click(function(e) {
			var $this = $(this);
			var offset = $this.offset().left;
			var width = ( ( ( e.pageX - offset ) / $this.width() ) * 100 ).toFixed();
			if ( $('body').hasClass('rtl') ) {
				width = ( 100 - ( ( ( e.pageX - offset ) / $this.width() ) * 100 ) ).toFixed();
			}


			// snap to nearest 5
			// width = Math.round(width / 5) * 5;

			// no 0-star ratings allowed
			if ( width <= 0 ) {
  				width = 1;
			}
			if ( width > 100 ) {
				width = 100;
			}

			$this.find('.review-result').width(width + '%');
			$this.data('originalrating', width );
			$this.data('originalwidth', $this.find('.review-result')[0].style.width);

			$wrapper.addClass('wp-review-input-set');

			// set input value
			$wrapper.find('.wp-review-user-rating-val').val( width );

			if ( typeof options.rate == 'function' ) {
				options.rate.call( $wrapper, parseFloat( width ) );
			}
		}).on('mouseenter mousemove', function(e) {
			var $this = $(this);
			var offset = $this.offset().left;
			var width = ( ( ( e.pageX - offset ) / $this.width() ) * 100 ).toFixed();
			if ( $('body').hasClass('rtl') ) {
				width = ( 100 - ( ( ( e.pageX - offset ) / $this.width() ) * 100 ) ).toFixed();
			}

			// snap to nearest 5
			// width = Math.round(width / 5) * 5;

			// no 0-star ratings allowed
			if ( width <= 0 ) {
  				width = 1;
			}
			if ( width > 100 ) {
				width = 100;
			}

			$this.find('.review-result').width(width + '%');

			if ( $('body').hasClass('rtl') ) {
				$wrapper.find('.wp-review-your-rating').css('right', width + '%').find('.wp-review-your-rating-value').text(''+width+'%');
			} else {
				$wrapper.find('.wp-review-your-rating').css('left', width + '%').find('.wp-review-your-rating-value').text(''+width+'%');
			}

		}).on('mouseleave', function(e){
			var $this = $(this);
			$this.find('.review-result').width($this.data('originalwidth'));
			$wrapper.find('.wp-review-your-rating-value').text($this.data('originalrating')+'%');
		});

		// Mobile script.
		$wrapper.find( '.review-result-mobile' ).each( function( index, el ) {
			$( el ).slider({
				range: 'min',
				min: 0,
				max: 100,
				step: 1,
				value: el.dataset.value,
				create: function( event, ui ) {
					$( el ).find( '.ui-slider-range' ).css( 'backgroundColor', el.dataset.color ).text( ( el.dataset.value || '0' ) + '%' );
					// $( el ).find( '.ui-slider-handle' ).css( 'backgroundColor', el.dataset.color );
				},
				slide: function( event, ui ) {
					$( el ).find( '.ui-slider-range' ).text( ( ui.value || '0' ) + '%' );
				},
				stop: function( event, ui ) {
					$wrapper.addClass( 'wp-review-input-set' );

					// Set input value.
					$wrapper.find( '.wp-review-user-rating-val' ).val( ui.value );

					if ( typeof options.rate == 'function' ) {
						options.rate.call( $wrapper, parseFloat( ui.value ) );
					}
				}
			});
		});
	};

	$( document ).ready( function() {
		$('.wp-review-user-rating-percentage, .wp-review-comment-rating-percentage').each( function() {
			$( this ).wprUserPercentageRating({
				rate: function( value ) {
					// console.log( this, value );
					wp_review_rate( this );
				}
			})
		});

		$( '.wpr-user-features-rating[data-type="percentage"]' ).each( function() {
			var $wrapper = $( this ),
				rating = {},
				$accept = $wrapper.find( '.wpr-rating-accept-btn' );

			$wrapper.find( '.wp-review-user-feature-rating-percentage' ).each( function() {
				var featureId = $( this ).attr( 'data-feature-id' );
				$( this ).wprUserPercentageRating({
					rate: function( value ) {
						$accept.show(); // Show accept button.
						this.attr( 'data-rated', '' );
						rating[ featureId ] = value;

						// If all features are rated.
						if ( ! $wrapper.find( '.wp-review-user-feature-rating-percentage:not([data-rated])' ).length ) {
							$accept.prop( 'disabled', false ); // Enable accept button.
							$wrapper.data( 'rating', rating );
						}
					}
				});
			});

			$accept.on( 'click', function() {
				wpreview.featuresRating( $wrapper );
			});
		});

		$( '.wpr-comment-features-rating[data-type="percentage"]' ).each( function() {
			var $wrapper = $( this ),
				rating = {},
				$accept = $wrapper.find( '.wpr-rating-accept-btn' );

			$wrapper.find( 'input[name="wp-review-user-rating-val"]' ).remove();
			$wrapper.find( 'input[name="wp-review-comment-feature-rating"]' ).remove();

			function submit() {
				var rating = $wrapper.data( 'rating' ),
					total = 0,
					count = 0;
				for ( var i in rating ) {
					total += parseFloat( rating[ i ] );
					count++;
				}
				$wrapper.find( 'input[name="wp-review-user-rating-val"]' ).remove();
				$wrapper.find( 'input[name="wp-review-comment-feature-rating"]' ).remove();
				$( '<input type="hidden" name="wp-review-user-rating-val">' ).val( total / count ).appendTo( $wrapper );
				$( '<input type="hidden" name="wp-review-comment-feature-rating">' ).val( JSON.stringify( rating ) ).appendTo( $wrapper );
			}

			$wrapper.find( '.wp-review-user-feature-rating-percentage' ).each( function() {
				var featureId = $( this ).attr( 'data-feature-id' );
				$( this ).wprUserPercentageRating({
					rate: function( value ) {
						var commentForm = this.closest( 'form' );
						commentForm.addClass( 'wpr-uncompleted-rating' );
						this.attr( 'data-rated', '' );
						rating[ featureId ] = value;
						$( '#commentform :submit' ).prop( 'disabled', true );

						// If all features are rated.
						if ( ! $wrapper.find( '.wp-review-user-feature-rating-percentage:not([data-rated])' ).length ) {
							$wrapper.data( 'rating', rating );
							$( '#commentform :submit' ).prop( 'disabled', false );
							submit();
							commentForm.removeClass( 'wpr-uncompleted-rating' );
						}
					}
				});
			});

			/*$accept.on( 'click', function() {
				var rating = $wrapper.data( 'rating' ),
					total = 0,
					count = 0;
				for ( var i in rating ) {
					total += parseFloat( rating[ i ] );
					count++;
				}
				$wrapper.find( 'input[name="wp-review-user-rating-val"]' ).remove();
				$wrapper.find( 'input[name="wp-review-comment-feature-rating"]' ).remove();
				$( '<input type="hidden" name="wp-review-user-rating-val">' ).val( total / count ).appendTo( $wrapper );
				$( '<input type="hidden" name="wp-review-comment-feature-rating">' ).val( JSON.stringify( rating ) ).appendTo( $wrapper );
			});*/
		});
	});
})( jQuery );
