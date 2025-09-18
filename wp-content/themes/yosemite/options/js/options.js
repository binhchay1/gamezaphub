var changeWarn = false;
jQuery(document).ready(function(){
	if(jQuery('#last_tab').val() == ''){
        jQuery('.nhp-opts-group-tab:first').slideDown('fast');
        jQuery('#nhp-opts-group-menu li:first').addClass('active');
    }else{
        tabid = jQuery('#last_tab').val();
        jQuery('#'+tabid+'_section_group').slideDown('fast');
        jQuery('#'+tabid+'_section_group_li').addClass('active');
        if (jQuery('#'+tabid+'_section_group_li').closest('#nhp-opts-homepage-accordion').length)
            jQuery('#nhp-opts-homepage-accordion').show();
        if (jQuery('#'+tabid+'_section_group_li').closest('#nhp-opts-blog-accordion').length)
            jQuery('#nhp-opts-blog-accordion').show();
    }        
    
    jQuery('.nhp-opts-group-tab-link-a').click(function(){
        if (this.id == 'accordion_section_group_li_a') {
           jQuery('#nhp-opts-homepage-accordion').slideToggle();
           return false;
        }
        if (this.id == 'accordion_section_group_li_a_2') {
           jQuery('#nhp-opts-blog-accordion').slideToggle();
           return false;
        }
        var $this = jQuery(this);
        if ($this.parent().hasClass('active'))
            return false;
        var relid = jQuery(this).attr('data-rel');
        
        jQuery('#last_tab').val(relid);
        
        jQuery('.nhp-opts-group-tab').hide();
        jQuery('#'+relid+'_section_group').fadeIn(400);
		
        jQuery('.nhp-opts-group-tab-link-li').removeClass('active');
        $this.parent().addClass('active');

        jQuery("html, body").animate({ scrollTop: jQuery('#nhp-opts-header').offset().top - 48 }, 500);
        return false;        
	});
	
	if(jQuery('#nhp-opts-save').is(':visible')){
		jQuery('#nhp-opts-save').delay(4000).slideUp('slow');
	}
    function addTypographyData(container, form) {
        var i = 0;
		container.find(".collections .collection").each(function() {
			form.addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][preview_text]', jQuery(this).find(".preview_text").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][preview_color]', jQuery(this).find(".preview_color li.current a").attr("class"))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_family]', jQuery(this).find(".font_family").select2('val'))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_variant]', jQuery(this).find(".font_variant").select2('val'))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_size]', jQuery(this).find(".font_size").select2('val'))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][font_color]', jQuery(this).find(".font_color").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][css_selectors]', jQuery(this).find(".css_selectors").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][additional_css]', jQuery(this).find(".additional_css").val())
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][backup_font]', jQuery(this).find(".backup_font").select2('val'))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][default]', jQuery(this).attr("data-default"))
                .addHiddenField(nhpopts.opt_name+'[google_typography_collections]['+i+'][collection_title]', jQuery(this).find(".collection_title").val());
            
            i++;
		});
    }
	
	if(jQuery('#nhp-opts-imported').is(':visible')){
		jQuery('#nhp-opts-imported').delay(4000).slideUp('slow');
	}
    
    jQuery('#nhp-opts-footer').find('#savechanges').click(function(e) {
        // add typography data via hidden fields before submitting
        if (typography_isloaded) {
            addTypographyData(jQuery('#google_typography'),jQuery('#nhp-opts-form-wrapper'));
        }
        // AJAX save
        /*
        jQuery('#savechanges').prop('disabled', true).after('<div class="spinner" id="ajax-saving"></div>');
        changeWarn = false;
        var $form = jQuery('#nhp-opts-form-wrapper');
        jQuery.post( $form.attr('action'), $form.serialize() ).done(function() {
            jQuery('#ajax-saving').remove();
            jQuery('#savechanges').prop('disabled', false).after('<div id="ajax-saved">Settings saved!</div>');
            setTimeout(function() { jQuery('#ajax-saved').fadeOut('slow', function() { jQuery('#ajax-saved').remove(); }); }, 2000);
        });
        return false;
        */
        // AJAX save end
    });

	jQuery('input, textarea, select').not('.mts_translate_textarea, #translate_search').change(function(){
        if (!changeWarn) {
          //jQuery('#nhp-opts-save-warn').slideDown('slow');
          changeWarn = true;
        }
	});

    jQuery('#nhp-opts-form-wrapper').submit(function() {
        changeWarn = false;
    });

    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (changeWarn) {
            return nhpopts.leave_page_confirm;
        }
    }
	
	jQuery('#nhp-opts-import-code-button').click(function(e){
        e.preventDefault();
		jQuery('#nhp-opts-import-code-wrapper').toggle().find('#import-code-value').val('');
	});

	jQuery('#nhp-opts-export-code-copy').click(function(e){
        e.preventDefault();
		jQuery('#nhp-opts-export-code').toggle().select();
	});
    
    // Presets
    function scrollImportLogToBottom(){
        var element = document.getElementById("importing-modal-content");
        element.scrollTop = element.scrollHeight;
    }
    jQuery('#presets .preset .import-demo-button, #presets .preset .import-demo-widgets-button, #presets .preset .import-demo-options-button').on('click', function(e) {
        e.preventDefault();

        var $this = jQuery(this);
        var $parent = $this.closest('.preset');
        var confirmText = nhpopts.import_opt_confirm;
        if ( $this.hasClass('import-demo-button') ) {
            confirmText = nhpopts.import_all_confirm;
        }
        if ( $this.hasClass('import-demo-widgets-button') ) {
            confirmText = nhpopts.import_widget_confirm;
        }

        var result = confirm( confirmText );
        if ( result ) {

            var data = {};
            data.action = "mts_install_demo";
            data.demo_import_id = $parent.attr("data-demo-id");
            data.nonce = $parent.attr("data-nonce");
            data.demo_import_options = '1';
            data.demo_import_content = '0';
            data.demo_import_widgets = '0';

            if ( $this.hasClass('import-demo-button') ) {
                data.demo_import_content = '1';
                data.demo_import_widgets = '1';
            }

            if ( $this.hasClass('import-demo-widgets-button') ) {
                data.demo_import_widgets = '1';
            }

            $this.magnificPopup({
                items: {
                    src: '#importing-overlay',
                    type: 'inline'
                },
                modal: true
            }).magnificPopup('open');

            var last_response_len = false;
            jQuery.ajax( ajaxurl, {
                data: data,
                xhrFields: {
                    onprogress: function(e) {

                        var this_response, response = e.currentTarget.response;
                        if(last_response_len === false) {

                            this_response = response;
                            last_response_len = response.length;

                        } else {

                            this_response = response.substring(last_response_len);
                            last_response_len = response.length;
                        }

                        jQuery('#importing-modal-content').append(this_response);
                        scrollImportLogToBottom();
                    }
                }
            })
            .done(function(data) {
                jQuery('#importing-modal-header h2').text(nhpopts.import_done);
                jQuery('#importing-modal-footer-info').text(nhpopts.import_done);
                jQuery('#importing-modal-footer-button').show();
            })
            .fail(function(data) {
                jQuery('#importing-modal-header h2').text(nhpopts.import_fail);
                jQuery('#importing-modal-footer-info').text(nhpopts.import_fail);
                jQuery('#importing-modal-footer-button').show();
            });
        }

        return false;
    });

    jQuery('.remove-demo-button').on('click', function(e) {
        e.preventDefault();

        var result = confirm( nhpopts.remove_all_confirm );
        if ( result ) {

            var $this = jQuery(this);
            var data = {};
            data.action = "mts_install_demo";
            data.mts_remove_demos = "1";
            data.nonce = $this.attr("data-nonce");

            $this.magnificPopup({
                items: {
                    src: '#importing-overlay',
                    type: 'inline'
                },
                modal: true
            }).magnificPopup('open');

            var last_response_len = false;
            jQuery.ajax( ajaxurl, {
                data: data,
                xhrFields: {
                    onprogress: function(e) {

                        var this_response, response = e.currentTarget.response;
                        if(last_response_len === false) {

                            this_response = response;
                            last_response_len = response.length;

                        } else {

                            this_response = response.substring(last_response_len);
                            last_response_len = response.length;
                        }

                        jQuery('#importing-modal-content').append(this_response);
                        scrollImportLogToBottom();
                    }
                }
            })
            .done(function(data) {
                jQuery('#importing-modal-header h2').text(nhpopts.remove_done);
                jQuery('#importing-modal-footer-info').text(nhpopts.remove_done);
                jQuery('#importing-modal-footer-button').show();
            })
            .fail(function(data) {
                jQuery('#importing-modal-header h2').text(nhpopts.remove_fail);
                jQuery('#importing-modal-footer-info').text(nhpopts.remove_fail);
                jQuery('#importing-modal-footer-button').show();
            });
        }

        return false;
    });

    function mtsRemoveURLParameter(url, parameter) {

        //prefer to use l.search if you have a location/link object
        var urlparts= url.split('?');
        if ( urlparts.length >= 2 ) {

            var prefix= encodeURIComponent(parameter)+'=';
            var pars= urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i= pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url= urlparts[0]+'?'+pars.join('&');
            return url;

        } else {

            return url;
        }
    }
    
    jQuery('#importing-modal-footer-button').on('click', function(e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true ).text(nhpopts.reloading_page);
        var a = mtsRemoveURLParameter( window.location.href , 'tab' );
        window.location.href = a;
    });
    
    // Confirm import
	jQuery('#nhp-opts-import').click(function() {
        return confirm(nhpopts.import_confirm);
	});
    
    // Confirm reset
    jQuery('input[name="'+nhpopts.opt_name+'[defaults]"]').click(function() {
        return confirm(nhpopts.reset_confirm);
    });
    
    // Confirm reset translations
    jQuery('#nhp-opts-reset-translations').click(function() {
        return confirm(nhpopts.reset_translations_confirm);
    });
    
    // Disallow submission by enter key
    jQuery('#nhp-opts-form-wrapper').find('input').keydown(function(event){
        if ( event.keyCode == 13 ){
            event.preventDefault();
        }
    });
    
    // Floating footer
    var $footer = jQuery('#nhp-opts-footer');
    var $bottom = jQuery('#nhp-opts-bottom');
    
    
    $footer.addClass('floating');
    jQuery(document).on('scroll', function(){
        if ($bottom.isOnScreen()) {
            $footer.removeClass('floating');
        } else {
            $footer.addClass('floating');
        }
    });
    if ($bottom.isOnScreen()) {
        $footer.removeClass('floating');
    }
    
    // Needs JS sizing when position:fixed
    var footer_padding = $footer.innerWidth() - $footer.width();
    function resizeFloatingElements() {
        var w = jQuery('#nhp-opts-form-wrapper').width();
        $footer.width(w - footer_padding);
    }
    resizeFloatingElements();
    
    var resizeTimer;
    jQuery(window).resize(function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(resizeFloatingElements, 100);
    });
    
    // theme translation
    var $translations_container = jQuery('.translate-strings');
    var $translations_toggle = jQuery('#nhp-opts-translate');
    function loadTranslations(page, search) {
        var page = page || 1; // default 1
        $translations_container.empty();
        jQuery.ajax({
			url: ajaxurl, 
            method: 'post',
			data: {  'action' : 'mts_translation_panel', 'page' : page, 'search' : search },
			beforeSend: function() {
				//container.find(".loading").show();
                $translations_container.addClass('loading');
			},
			success: function(data) {
                translation_isloaded = true
				$translations_container.removeClass('loading').html(data);
			}
		});
        jQuery('#translate_search_wrapper').show();
    }
    function saveTranslation(original_string, new_string, elem) {
        var wrapper = elem.closest('.translate-string-wrapper');
        jQuery.ajax({
			url: ajaxurl, 
            method: 'post',
			data: {  'action' : 'mts_save_translation', 'id' : original_string, 'val' : new_string },
			beforeSend: function() {
				elem.prop('disabled', true);
                wrapper.addClass('loading');
			},
			success: function(data) {
                elem.prop('disabled', false);
                wrapper.removeClass('loading');
                if (data == '1') {
                    wrapper.addClass('success');
                    setTimeout(function() {
                        wrapper.addClass('animate').removeClass('success');
                    }, 10);
                    setTimeout(function() {
                        wrapper.removeClass('animate');
                    }, 3000);
                    
                    // update number and %
                    var $info = jQuery('.translation_info');
                    var total = parseInt($info.find('.total').text()),
                        translated = parseInt($info.find('.translated').text());
                    
                    if (new_string == '' && elem.data('translated')) {
                        translated--;
                        $info.find('.translated').text(translated);
                    } else if (new_string != '' && ! elem.data('translated')) {
                        translated++;
                        $info.find('.translated').text(translated);
                    }
                    
                    var percent = translated / total * 100;
                    $info.find('.percent').text('('+percent.toFixed(2)+'%)');
                } else {
                    wrapper.addClass('fail');
                    setTimeout(function() {
                        wrapper.addClass('animate').removeClass('fail');
                    }, 10);
                    setTimeout(function() {
                        wrapper.removeClass('animate');
                    }, 3000);
                }
			}
		});
    }
    var translation_isloaded = false;
    // load on document.ready if needed
    if (! $translations_toggle.is(':checked')) {
        jQuery('#nhp-opts-reset-translations').prop('disabled', true);
    }
    if (jQuery('#last_tab').val() == 'translation_default' && $translations_toggle.is(':checked')) {
		loadTranslations();
    }
    jQuery('#translation_default_section_group_li a').click(function() {
        // we clicked on translations tab
        // load if it's enabled
        if (!translation_isloaded && $translations_toggle.is(':checked')) {
            loadTranslations();
        }
    });
    $translations_toggle.change(function() {
        if (jQuery(this).is(':checked')) {
            if (!translation_isloaded) {
                loadTranslations();
            } else {
                $translations_container.show();
                jQuery('#translate_search_wrapper').show();
            }
            jQuery('#nhp-opts-reset-translations').prop('disabled', false);
        } else {
            $translations_container.hide();
            jQuery('#translate_search_wrapper').hide();
            jQuery('#nhp-opts-reset-translations').prop('disabled', true);
        }
    });
    
    // translate panel v2: instant saving & pagination
    $translations_container.on('change', '.mts_translate_textarea', function(e) {
        $this = jQuery(e.target);
        saveTranslation($this.data('id'), $this.val(), $this);
    }).on('focus', '.mts_translate_textarea', function(e) {
        $this = jQuery(e.target);
        if ($this.val() == '') {
            $this.data('translated', 0);
        } else {
            $this.data('translated', 1);
        }
    }).on('click', '.mts_translation_pagination a', function(e) {
        e.preventDefault();
        $this = jQuery(e.target);
        if (!$this.hasClass('current')) {
            loadTranslations($this.text(), jQuery('#translate_search').val());
        }
    });
    
    jQuery('#translate_search').on('input propertychange', function() {
        var query = jQuery(this).val();
        //if (query.length > 2) { 
            fnDelay(function() {
                loadTranslations(1, query);
            }, 600);
        //}
    });
    
});

jQuery.fn.isOnScreen = function(){

    var win = jQuery(window);

    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};

jQuery.fn.addHiddenField = function(name, value) {
    this.each(function () {
        var elem_id = name.replace(/\W/g, '-').replace(/--+/g, '-').replace(/(^-|-$)/, '');
        if (jQuery('#'+elem_id).length) {
            // elem exists, change value
            jQuery('#'+elem_id).val(value);
        } else {
            // elem doesn't exist, create
            var input = jQuery("<input>").attr("type", "hidden").attr("id", elem_id).attr("name", name).val(value);
            jQuery(this).append(jQuery(input));
        }
        
    });
    return this;
};

var fnDelay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();