/*
Plugin Name: WP Tab Widget Pro
Plugin URI: http://mythemeshop.com/plugins/wp-tab-widget-pro/
Description: WP Tab Widget is the AJAXified plugin which loads content by demand, and thus it makes the plugin incredibly lightweight.
Author: MyThemeShop
Author URI: http://mythemeshop.com/
*/
var wptAdminDialog;

(function( $ ){

	"use strict";

	var settingsButton, adPreviewWrap, adPresetMainWrap, adPresetPaginationWrap, adPresetLoaderWrap, adPresetMainWrapSly, adPresetPaginationWrapSly,
		adPresetMainWrapSlyLoaded = false, adPresetPaginationWrapSlyLoaded = false,
		adInputs = {};

	wptAdminDialog = {

		init: function() {

			// Our dialog.
			adInputs.dialog = $('#wpt-modal');

			// Dialog action buttons.
			adInputs.submit = $('#wpt-modal-update');
			adInputs.cancel = $('#wpt-admin-cancel');

			adPresetMainWrap       = $('#wpbs-modal-style-presets');
			adPresetPaginationWrap = $('#wpbs-modal-nav-presets');
			adPresetLoaderWrap     = $('#wpbs-modal-loader-presets');
			adPreviewWrap          = $('#wpbs-modal-preview');

			adInputs.preset = {};

			adInputs.preset.main   = $('#modal-option-preset-main');
			adInputs.preset.nav    = $('#modal-option-preset-nav');
			adInputs.preset.loader = $('#modal-option-preset-loader');

			// Add color picker plugin on color inputs.
			$('.wpt-modal-color-picker').wpColorPicker();

			$(document).on( 'click', '.wpt-default-color', function(e) {

				e.preventDefault();

				$(this).closest('.wpt-modal-option').find('.wpt-modal-color-picker').wpColorPicker('color', $(this).data('color') );
			});

			$(document).on( 'click', '.wpt-reload-preview', function(e) {

				e.preventDefault();

				wptAdminDialog.loadPreview( true );
			});

			adPresetMainWrapSly = new Sly(
				adPresetMainWrap,
				{
					horizontal: 1,
					itemNav: 'forceCentered',
					smart: 1,
					activateMiddle: 1,
					activateOn: 'click',
					mouseDragging: 1,
					touchDragging: 1,
					releaseSwing: 1,
					startAt: 0,
					scrollBar: adPresetMainWrap.parent().find('.wpt-scrollbar'),
					scrollBy: 1,
					speed: 300,
					elasticBounds: 1,
					dragHandle: 1,
					dynamicHandle: 1,
					clickBar: 1,
					prev: adPresetMainWrap.parent().find('.wpt-prev-page'),
					next: adPresetMainWrap.parent().find('.wpt-next-page')
				}
			);

			adPresetMainWrapSly.on('load', [
			    function () {
			    	wptAdminDialog.scrollToImage('style');
				}
			]);

			adPresetPaginationWrapSly = new Sly(
				adPresetPaginationWrap,
				{
					horizontal: 1,
					itemNav: 'forceCentered',
					smart: 1,
					activateMiddle: 1,
					activateOn: 'click',
					mouseDragging: 1,
					touchDragging: 1,
					releaseSwing: 1,
					startAt: 0,
					scrollBar: adPresetPaginationWrap.parent().find('.wpt-scrollbar'),
					scrollBy: 1,
					speed: 300,
					elasticBounds: 1,
					dragHandle: 1,
					dynamicHandle: 1,
					clickBar: 1,
					prev: adPresetPaginationWrap.parent().find('.wpt-prev-page'),
					next: adPresetPaginationWrap.parent().find('.wpt-next-page')
				}
			);

			adPresetPaginationWrapSly.on('load', [
			    function () {
			    	wptAdminDialog.scrollToImage('pagination-style');
			    }
			]);

			$(window).resize(function(e) {
				if ( adPresetMainWrapSlyLoaded ) adPresetMainWrapSly.reload();
				if ( adPresetPaginationWrapSlyLoaded ) adPresetPaginationWrapSly.reload();
			});

			// Handle selecting of images.
			$('#wpt-modal .presets-list-preset').click( function(e) {

				e.preventDefault();

				if ( ! adPreviewWrap.hasClass('loading') ) {

					var $this = $(this),
						preset = $this.data('preset');

					if ( ! $this.hasClass('selected-image') ) {

						$this.closest('ul').find('.presets-list-preset.selected-image').removeClass('selected-image');
						$this.addClass('selected-image');

						
						if ( $this.hasClass('preset-loader') ) {

							adInputs.preset.loader.val( preset );

						} else if ( $this.hasClass('preset-main') ) {

							$('#modal-style-colors').show();
							adInputs.preset.main.val( preset );

							if ( '' === preset ) {
								$('.wptp-disabled-notice').show();
								wptAdminDialog.disableTabs();
							} else {
								$('.wptp-disabled-notice').hide();
								wptAdminDialog.enableTabs();
							}

						} else {

							$('#modal-pagination-colors').show();
							adInputs.preset.nav.val( preset );
						}
					}

					if ( !$this.hasClass('preset-loader') ) {

						wptAdminDialog.updateModalColors( $this.data('colors') );
					}

					wptAdminDialog.loadPreview( true );
				}
			});

			// Modal tabs.
			$(document).on(
				'click',
				'.wpbs-modal-tab a',
				function(e) {

					e.preventDefault();

					var $this = $(this),
						target = $this.attr('href');

					if ( !$this.parent().hasClass('wpt-active') ) {

						$this.parent().siblings().removeClass('wpt-active');
						$this.parent().addClass('wpt-active');

						$('.wpbs-modal-tab-content.wpt-active').removeClass('wpt-active');
						$(target).addClass('wpt-active');
					}

					if ( '#wpbs-modal-nav-presets-tab' === target ) {
						if ( !adPresetPaginationWrapSlyLoaded ) {
							adPresetPaginationWrapSly.init();
							adPresetPaginationWrapSlyLoaded = true;
						} else {
							//wptAdminDialog.scrollToImage('pagination-style');
							adPresetPaginationWrapSly.reload();
						}
					}
					if ( '#wpbs-modal-style-presets-tab' === target ) {
						//wptAdminDialog.scrollToImage('style');
						adPresetMainWrapSly.reload();
					}
				}
			);

			// Handle action buttons.
			adInputs.submit.click( function(e) {

				e.preventDefault();

				wptAdminDialog.update();
			});

			adInputs.cancel.click( function(e) {

				e.preventDefault();

				wptAdminDialog.remove();
			});

			// When settings button is clicked, take all needed parameters and show the dialog.
			$(document).on(
				'click',
				'.wpt-style-popup',
				function(e) {

					e.preventDefault();

					settingsButton = $(this);

					var style  = settingsButton.siblings('.wpt-input-style').val();
					var pstyle = settingsButton.siblings('.wpt-input-pagination_style').val();
					var lstyle = settingsButton.siblings('.wpt-input-loader').val();
					adInputs.preset.main.val( style );
					adInputs.preset.nav.val( pstyle );
					adInputs.preset.loader.val( lstyle );
					var savedColors = wptAdminDialog.getWidgetColors();
					wptAdminDialog.updateModalColors( savedColors );

					if ( '' !== style ) {

						wptAdminDialog.showModalColors('style');
						adPresetMainWrap.find('.presets-list-preset[data-preset="'+style+'"]').addClass('selected-image');

					} else {

						adPresetMainWrap.find('.presets-list-preset[data-preset=""]').addClass('selected-image');
						wptAdminDialog.hideModalColors('style');
						$('.wptp-disabled-notice').show();
						wptAdminDialog.disableTabs();
					}

					if ( '' !== pstyle ) {

						wptAdminDialog.showModalColors('pagination-style');
						adPresetPaginationWrap.find('.presets-list-preset[data-preset="'+pstyle+'"]').addClass('selected-image');

					} else {

						wptAdminDialog.hideModalColors('pagination-style');
					}

					if ( '' !== lstyle ) {

						adPresetLoaderWrap.find('.presets-list-preset[data-preset="'+lstyle+'"]').addClass('selected-image');
					}

					wptAdminDialog.show();
				}
			);


			// When modal dialog is shown, scroll to selected presets and load preview.
			adInputs.dialog.on( 'shown.wpbs.modal', function() {

				if ( !adPresetMainWrapSlyLoaded ) {
					adPresetMainWrapSly.init();
					adPresetMainWrapSlyLoaded = true;
				} else {
					//wptAdminDialog.scrollToImage('style');
					adPresetMainWrapSly.reload();
				}

				wptAdminDialog.loadPreview( true );
			});

			// When modal dialog is closed focus preview button.
			adInputs.dialog.on( 'hidden.wpbs.modal', wptAdminDialog.closed );
		},
		
		show: function() {

			adInputs.dialog.wpbsmodal('show');
		},

		updateModalColors: function( data ) {

			if ( data !== undefined ) {

				$.each( data, function( index, val ) {

					if( '' !== val ) {

						$('.modal-option-color-'+index).show().addClass('wpt-active-color');
						$('#modal-option-color-'+index).wpColorPicker( 'color', val );
						$('#modal-option-color-'+index).closest('.wpt-modal-option').find('.wpt-default-color').data('color', val );

					} else {

						$('.modal-option-color-'+index).hide().removeClass('wpt-active-color');;
					}
				});
			}
		},

		clearModalColors: function( type ) {

			if ( 'style' === type ) {

				$('#modal-style-colors .wpt-modal-color-picker').wpColorPicker( 'color', '' );
				$('#modal-style-colors .wp-color-result').removeAttr('style');

			} else {

				$('#modal-pagination-colors .wpt-modal-color-picker').wpColorPicker( 'color', '' );
				$('#modal-pagination-colors .wp-color-result').removeAttr('style');
			}
		},

		showModalColors: function( type ) {

			if ( 'style' === type ) {

				$('#modal-style-colors').show();

			} else {

				$('#modal-pagination-colors').show();
			}
		},

		hideModalColors: function( type ) {

			if ( 'style' === type ) {

				$('#modal-style-colors').hide();

			} else {

				$('#modal-pagination-colors').hide();
			}
		},

		scrollToImage: function( type ) {

			if ( 'style' === type ) {
				adPresetMainWrapSly.activate( adPresetMainWrap.find('.selected-image').closest('.presets-list-item').index() );
			} else {
				adPresetPaginationWrapSly.activate( adPresetPaginationWrap.find('.selected-image').closest('.presets-list-item').index() );
			}
		},

		loadPreview: function( preview ) {

			if ( ! adPreviewWrap.hasClass('loading') ) {

				adPreviewWrap.empty();

				var previewing = preview || false,
					form_data,
					template_data = '';

				if ( settingsButton.closest('.widget-inside').find('form').length ) {
					form_data = settingsButton.closest('.widget-inside').find('form').serialize();
				} else {
					form_data = settingsButton.closest('.widget-inside').find(':input').serialize();
				}

				if ( true === previewing ) template_data = adInputs.dialog.serialize();

				var data = {
					action: 'wpt_preview_widget',
					form_data: form_data,
					template_data: template_data,
					//dataType: 'html'
				};

				adPreviewWrap.addClass('loading');

				$.post( ajaxurl, data, function(response) {

					if ( response ) {

						adPreviewWrap.html( response );
					}

				}).done(function(result){

					adPreviewWrap.removeClass('loading');
					$( document ).trigger('wptPreviewLoaded');
				});
			}
		},

		update: function() {

			// Clear all existing values
			settingsButton.parent().find('input').each( function() {

				$(this).val('');
			});

			settingsButton.parent().find('.colorset-colors').removeClass('colorset-none');
			settingsButton.parent().find('.colorset-color').css('background', '' );

			// Assign new values
			$('#wpbs-modal-settings').find('input[type="hidden"], input[type="text"]').each( function() {

				var $this = $(this),
					newVal = $this.val(),
					thisID = $this.attr('id');

				if ( '' !==  newVal ) {

					if ( thisID.indexOf('color') >= 0 ) {

						if ( $this.closest('.wpt-modal-option').hasClass('wpt-active-color') ) {

							var color = thisID.replace('modal-option-color-', '');
							settingsButton.parent().find( 'input.wpt-input-'+ color ).val( newVal );

							if ( thisID.indexOf('option-color-tab_bg') >= 0 ) {
								settingsButton.parent().find('.colorset-colors-1').css('background', newVal );
							}
							if ( thisID.indexOf('option-color-tab_active_bg') >= 0 ) {
								settingsButton.parent().find('.colorset-colors-2').css('background', newVal );
							}
							if ( thisID.indexOf('option-color-bg') >= 0 ) {
								settingsButton.parent().find('.colorset-colors-3').css('background', newVal );
							}
							if ( thisID.indexOf('option-color-color') >= 0 ) {
								settingsButton.parent().find('.colorset-colors-4').css('background', newVal );
							}
						}

					} else {

						if ( thisID.indexOf('preset-nav') >= 0 ) {

							settingsButton.parent().find( 'input.wpt-input-pagination_style' ).val( newVal );

						} else if ( thisID.indexOf('preset-loader') >= 0 ) {

							settingsButton.parent().find( 'input.wpt-input-loader' ).val( newVal );

						} else {

							settingsButton.parent().find( 'input.wpt-input-style' ).val( newVal );
						}
					}
				} else if ( 'modal-option-preset-main' === thisID ) {

					settingsButton.parent().find('.colorset-colors').addClass('colorset-none');
				}
			});

			wptAdminDialog.close();
		},

		close: function() {

			adInputs.dialog.wpbsmodal('hide');

			// Force soft widget update inside customizer UI by clicking on hidden widget update button
			if ( $('body').hasClass('wp-customizer') ) {

				var updateWidgetButton = settingsButton.parent().parent().parent().parent().find('input.widget-control-save');
				updateWidgetButton.click();
			}
		},

		closed: function() {

			$('.presets-list-preset.selected-image').removeClass('selected-image');

			$('.wpbs-modal-tab a').first().click();

			adPreviewWrap.empty();

			$('.wptp-disabled-notice').hide();
			wptAdminDialog.enableTabs();

			settingsButton.focus();
		},

		getWidgetColors: function() {
			var colors = {};
			settingsButton.parent().find('.wpt-colors input').each( function() {
				var $this = $(this),
					newVal = $this.val(),
					thisClass = $this.attr('class'),
					color = thisClass.replace('wpt-input-', '');

				colors[color] = newVal;
			});
			return colors;
		},

		enableTabs: function() {
			$('.wpbs-modal-tab').eq(1).find('a').removeClass('wpt-disabled');
			$('.wpbs-modal-tab').eq(3).find('a').removeClass('wpt-disabled');
		},

		disableTabs: function() {
			$('.wpbs-modal-tab').eq(1).find('a').addClass('wpt-disabled');
			$('.wpbs-modal-tab').eq(3).find('a').addClass('wpt-disabled');
		}
	};

	$(document).ready( wptAdminDialog.init );

})( jQuery );