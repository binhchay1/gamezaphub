/*
 * Plugin Name: MyThemeShop Theme & Plugin Updater
 * Plugin URI: http://www.mythemeshop.com
 * Description: Update MyThemeShop themes & plugins, get news & exclusive offers right from your WordPress dashboard
 * Author: MyThemeShop
 * Author URI: http://www.mythemeshop.com
 */
jQuery(document).ready(function($) {

	// Tabs
	$('.mtsc-nav-tab-wrapper a').click(function(event) {
		event.preventDefault();
		window.location.hash = this.href.substring(this.href.indexOf('#') + 1);
	});
	$(window).on('hashchange', function() {
		var tab = window.location.hash.substr(1);
		if (tab == '') {
			tab = 'mtsc-connect';
		}
		$('#mtsc-tabs').children().hide().filter('#' + tab).show();
		$('#mtsc-nav-tab-wrapper').children().removeClass('nav-tab-active').filter('[href="#' + tab + '"]').addClass('nav-tab-active');
	}).trigger('hashchange');

	// Settings form
	$('#mtsc-ui-access-role').focus(function(event) {
		$(this).parent().find('input[type="radio"]').prop('checked', true);
	});
	$('#mtsc-ui-access-user').focus(function(event) {
		$(this).parent().find('input[type="radio"]').prop('checked', true);
	});
	$('#mts_connect_settings_form').submit(function(e) {
		e.preventDefault();
		var $this = $(this);
		var hideNotices = ! $('#mtsc-label-updatenotices input').is(':checked');
		$.ajax({
			url: ajaxurl,
			method: 'post',
			data: $this.serialize(),
			beforeSend: function(xhr) {
				$this.addClass('loading');
			},
			success: function(data) {
				$this.removeClass('loading');
				if ( hideNotices ) {
					$('.mts-connect-notice').hide();
				}
			}
		});
	});
	$('#mtsc-clear-notices').click(function(event) {
		event.preventDefault();
		$('.mts-connect-notice').hide();
		$.ajax({
			url: ajaxurl,
			type: 'GET',
			data: {
				action: 'mts_connect_reset_notices'
			},
		});

		$('#mtsc-clear-notices-success').show();
		setTimeout(function() {
			$('#mtsc-clear-notices-success').hide();
		}, 2000);
	});
	$('#mtsc-clear-notices-success').hide();

	if ( $('#mts_connected_data').length && $('#mts_connect_status').val() == 'success' ) {
		var status = $('#mts_connect_status').val();
		var $this = $('#mts_connected_data');
		if ( status === 'success' ) {
			// check_themes
			$.get(ajaxurl, 'action=mts_connect_check_themes').done(function() {
				$this.append(mtsconnect.l10n_ajax_theme_check_done);
				setTimeout(function() {
					// check_plugins
					$.get(ajaxurl, 'action=mts_connect_check_plugins').done(function() {
						$this.append(mtsconnect.l10n_ajax_plugin_check_done);
						if ($('#mts-connect-modal').length) {
							if (typeof mts_connect_refresh != 'undefined' && mts_connect_refresh === true) {
								window.location.reload(true);
								return;
							}
							$('#mts-connect-modal').remove();
						} else {
							$this.append(mtsconnect.l10n_ajax_refreshing);
							setTimeout(function() {
								window.location.href = mtsconnect.pluginurl + '&updated=1';
							}, 100);
						}
					});
				}, 1000);
			});
		}
	}
});
