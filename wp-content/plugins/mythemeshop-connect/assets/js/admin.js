/*
 * Plugin: MyThemeShop Theme & Plugin Updater
 * Plugin URI: http://www.mythemeshop.com
 * Description: Update MyThemeShop themes & plugins, get news & exclusive offers right from your WordPress dashboard
 * Author: MyThemeShop
 * Author URI: http://www.mythemeshop.com
 */
jQuery(document).ready(function($) {
	var closed_notices = 0;

	function dismiss_notices(ids) {
		$.each(ids, function(index, id) {
			var $notice = $('#notice_' + id);
			$notice.fadeOut('slow', function() {
				if (closed_notices >= 2) {
					$('.mts-notice-dismiss-all-icon').fadeIn();
				}
			});
		});

		$.ajax({
			url: ajaxurl,
			method: 'post',
			data: {
				'action': 'mts_connect_dismiss_notice',
				'ids': ids
			}
		});

		closed_notices++;
	}

	$('.mts-notice-dismiss-icon', this).click(function(e) {
		e.preventDefault();
		var id = $(this).closest('.mts-connect-notice').prop('id').replace('notice_', '');
		dismiss_notices([id]);
	});

	var notices = [];
	$('.mts-connect-notice').each(function() {
		notices.push(this.id.replace('notice_', ''));
	});

	$('.mts-notice-dismiss-all-icon', this).click(function(e) {
		e.preventDefault();
		dismiss_notices(notices);
	});

	// Admin menu
	jQuery('#adminmenu .toplevel_page_mts-connect .dashicons-update').addClass(mtsconnect.icon_class_attr);

	// Extra buttons
	if (jQuery('body').hasClass('themes-php')) {
		jQuery('.page-title-action').after(' <a href="' + mtsconnect.check_themes_url + '" id="mts-connect-check-theme-updates" class="page-title-action">' + mtsconnect.l10n_check_themes_button + '</a>');
	} else if (jQuery('body').hasClass('plugins-php')) {
		jQuery('.page-title-action').after(' <a href="' + mtsconnect.check_plugins_url + '" id="mts-connect-check-theme-updates" class="page-title-action">' + mtsconnect.l10n_check_plugins_button + '</a>');
	}
	if (jQuery('#mts-connect-modal').length) {
		jQuery('#mts-connect-modal').show().find('p a.button:last-child').click(function(event) {
			event.preventDefault();
			jQuery('#mts-connect-modal').hide();
		});
	}

	// Connect form
	$('#mts_connect_form').submit(function(e) {
		e.preventDefault();
		var $this = $(this);
		$this.find('.mtsc-error').remove();
		// get_key
		$.ajax({
			url: ajaxurl,
			method: 'post',
			data: $this.serialize(),
			dataType: 'json',
			beforeSend: function(xhr) {
				$this.addClass('loading');
			},
			success: function(data) {
				$this.removeClass('loading');
				if (data !== null && typeof data.login !== 'undefined') {
					$this.html(mtsconnect.l10n_ajax_login_success);
					jQuery('#mts-connect-modal').find('p').first().hide();
					jQuery('#adminmenu .toplevel_page_mts-connect .dashicons-update').removeClass('disconnected').addClass('connected');
					if (jQuery('#mts-connect-modal').length) {
						jQuery('#mts-connect-modal').find('.button-secondary').last().hide();
					}
					window.location = data.auth_url;
				} else { // status = fail
					var errors = '';
					var error_msg = '';
					if (typeof data.message !== 'undefined') {
						error_msg = data.message;
					} else if (typeof data.errors !== 'undefined') {
						error_msg = data.errors[0];
					} else {
						error_msg = mtsconnect.l10n_ajax_unknown_error + '<pre style="white-space: pre-wrap;">Response: ' + JSON.stringify(data) + '</pre>';
					}

					errors = '<p class="mtsc-error">' + error_msg + '</p>';
					$this.find('.mtsc-error').remove();
					$this.append(errors);
				}
			}
		});
	});
});
