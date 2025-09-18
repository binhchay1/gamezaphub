/*
Plugin Name: WP Tab Widget Pro
Plugin URI: http://mythemeshop.com/plugins/wp-tab-widget-pro/
Description: WP Tab Widget is the AJAXified plugin which loads content by demand, and thus it makes the plugin incredibly lightweight.
Author: MyThemeShop
Author URI: http://mythemeshop.com/
*/
(function( $ ) {
	$(function() {

		$sortFields = $('.wpt_options_form').find('.wpt-accordion');
		$sortFields.accordion({
			header: '.item-wrap h3',
			collapsible: true,
			heightStyle: 'content',
			active: false
		}).sortable({
			items: '.item-wrap',
			cursor: 'move',
			axis: 'y'
		});

		wptIconSelect();

		$(document).on('click', '.wpt-add-item', function(e){
			e.preventDefault();
			var $this = $(this),
				$accordionfield = $this.parent().prev(),
				dropdownValue = $this.parent().find('.wpt-dropdown-tabs'),
				dropDownFieldValue = dropdownValue.val(),
				number = $this.closest('form').find('input.widget_number').val(),
				multi_number = $this.closest('form').find('input.multi_number').val(),
				placeholder = '<div class="item-wrap loading"><h3 class="ui-accordion-header ui-state-default ui-corner-all">Loading...</h3></div>',
				num = Math.floor((Math.random() * 10) + 1);

			$.ajax({
				method: "POST",
				url: ajaxurl,
				data: {
					action : 'wpt_get_tab_options',
					wpt_tab : dropDownFieldValue+"-"+num,
					wpt_number : number,
					wpt_multi_number : multi_number,
				},
				beforeSend: function() {
					$this.prop( 'disabled', true );
					$(placeholder).appendTo($accordionfield);
				},
				success: function(data) {
					$accordionfield.find('.item-wrap.loading').replaceWith(data);
					$this.prop( 'disabled', false );
				}
			}).done(function() {

				$accordionfield.accordion('destroy').accordion({
					active: false,
					header: '.item-wrap h3',
					collapsible: true,
					heightStyle: 'content'
				}).sortable({
					items: '.item-wrap',
					cursor: 'move',
					axis: 'y'
				});

				wptIconSelect();

				$('select.wptp-has-child-opt').each(function() {
					wptpShowHodeChildOptions( $(this) );
				});
			});
		});

		$(document).on('click', '.wpt-remove-item', function(e){
			e.preventDefault();
			$(this).parent().remove();
		});

		$(document).on('widget-added widget-updated', function(e) {
			$sortFields = $('.wpt_options_form').find('.wpt-accordion');
			$sortFields.accordion({
				header: '.item-wrap h3',
				collapsible: true,
				heightStyle: 'content',
				active: false
			}).sortable({
				items: '.item-wrap',
				cursor: 'move',
				axis: 'y',
			});

			wptIconSelect();

			$('select.wptp-has-child-opt').each(function() {
				wptpShowHodeChildOptions( $(this) );
			});
		});

		$(document).on('click', function(e) {
			var $this = $(e.target);
			var $form = $this.closest('.item-wrap');

			if ($this.is('.wpt_show_thumbnails')) {
				var $related = $form.find('.wpt_thumbnail_size');
				var val = $this.is(':checked');
				if (val) {
					$related.slideDown();
				} else {
					$related.slideUp();
				}
			} else if ($this.is('.wpt_show_excerpt')) {
				var $related = $form.find('.wpt_excerpt_length');
				var val = $this.is(':checked');
				if (val) {
					$related.slideDown();
				} else {
					$related.slideUp();
				}
			}
		});

		function wptIconSelect() {
			$('#widgets-right select.nhpopts-iconselect').each(function(){
				$(this).select2({
					templateResult: function(state) {
						if (!state.id) return state.text; // optgroup
						return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
					},
					templateSelection: function(state) {
						if (!state.id) return state.text; // optgroup
						return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
					},
					escapeMarkup: function(m) { return m; }
				});
			});
		}
		// Select which shows/hides options based on its value
		function wptpShowHodeChildOptions( el ) {
			var $this = $(el),
				tempValue = $this.val(),
				targetSelector = '[data-parent-select-id="'+$this.attr('id')+'"]',
				activeSelector = '[data-parent-select-value*="'+tempValue+'"]';

			$( targetSelector ).removeClass('active');

			if ( tempValue && activeSelector ) {

				$( targetSelector+activeSelector ).addClass('active');
			}
		}

		$('select.wptp-has-child-opt').each(function() {
			wptpShowHodeChildOptions( $(this) );
		});

		$(document).on('change', 'select.wptp-has-child-opt', function(e) {
			wptpShowHodeChildOptions( $(this) );
		});
	});
})( jQuery );
