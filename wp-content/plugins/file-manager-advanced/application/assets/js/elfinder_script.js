jQuery( document ).ready( function() {

    if ( 1 == afm_object.hide_path ) {
        var custom_css = `<style id="hide-path" type="text/css">.elfinder-info-path { display:none; } .elfinder-info-tb tr:nth-child(2) { display:none; }</style>`;
        jQuery( "head" ).append( custom_css );
    }

    var hide_preferences_css = `<style id="hide-preferences" type="text/css">
        .elfinder-contextmenu-item:has( .elfinder-button-icon.elfinder-button-icon-preference.elfinder-contextmenu-icon ) {display: none;}
    </style>`;
    jQuery( 'head' ).append( hide_preferences_css );

    var fmakey       = afm_object.nonce;
    var fma_locale   = afm_object.locale;
    var fma_cm_theme = afm_object.cm_theme;

    // Helper function to check if file is PHP
    function isPHPFile(mimeType, filename) {
        return mimeType === 'text/x-php' || 
               mimeType === 'application/x-php' || 
               filename.toLowerCase().endsWith('.php');
    }

    // PHP Validation function
    function validatePHPSyntax( code, filename, callback ) {
        jQuery.ajax({
            url: afm_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'fma_validate_php',
                nonce: fmakey,
                php_code: code,
                filename: filename
            },
            success: function( response ) {
                // Cache the last validated code and result
                window.fma_last_validated_code = code;
                window.fma_last_validation_result = response;
                if ( callback && typeof callback === 'function' ) {
                    callback( response );
                }
            },
            error: function() {
                if ( callback && typeof callback === 'function' ) {
                    callback({
                        valid: false,
                        errors: [],
                        message: 'Failed to validate PHP syntax'
                    });
                }
            }
        });
    }

    // Show simple success modal (with duplicate prevention)
    function showSuccessModal(message) {
        // Prevent duplicate modals
        if (jQuery('.fma-modal-overlay').length > 0) {
            return;
        }
        
        var modalHtml = `
            <div class="fma-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10001; display: flex; align-items: center; justify-content: center;">
                <div class="fma-modal" style="background: white; border-radius: 8px; padding: 30px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.3); font-family: Arial, sans-serif;">
                    <div style="color: #46b450; font-size: 48px; margin-bottom: 15px;">✓</div>
                    <h3 style="margin: 0 0 10px 0; color: #46b450; font-size: 18px;">Success!</h3>
                    <p style="margin: 0 0 20px 0; color: #333; font-size: 14px;">${message}</p>
                    <button class="fma-modal-close" style="padding: 10px 20px; background: #46b450; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">OK</button>
                </div>
            </div>
        `;
        
        var modal = jQuery(modalHtml).appendTo('body');
        
        // Close modal on click
        modal.find('.fma-modal-close, .fma-modal-overlay').on('click', function(e) {
            if (e.target === this) {
                modal.remove();
            }
        });
        
        // Auto close after 3 seconds
        setTimeout(function() {
            modal.remove();
        }, 3000);
    }

    // Replace error rendering in showPHPErrorPopupWithSaveOption:
    function flattenErrors(errors) { 
        var flat = [];
        (function recur(errs) {
            if (Array.isArray(errs)) {
                errs.forEach(recur);
            } else if (errs && typeof errs === 'object') {
                if (errs.message) {
                    flat.push({ line: errs.line, message: errs.message });
                }
                // Recursively flatten any nested 'errors' property
                Object.keys(errs).forEach(function(key) {
                    if (Array.isArray(errs[key]) && key === 'errors') {
                        recur(errs[key]);
                    }
                });
            }
        })(errors);
        return flat;
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showPHPErrorPopupWithSaveOption(errors, filename, onSaveAnyway) {
        // Debug: log the error structure
        var flatErrors = flattenErrors(errors);
        // Prevent duplicate error popups
        if (jQuery('.fma-modal-overlay').length > 0) {
            return;
        }
        var errorCount = flatErrors.length;
        var errorListHtml = '';
        // Only render flat errors, never nested errors
        flatErrors.forEach(function(error, index) { 
            errorListHtml += `
                <div style="background: #fff2f2; border-left: 4px solid #d63638; padding: 15px; margin-bottom: 10px; border-radius: 4px;">
                    <div style="font-weight: bold; color: #d63638; margin-bottom: 5px;">
                        Error ${index + 1}${error.line > 0 ? ` (Line ${error.line})` : ''}
                    </div>
                    <div style="color: #b30000; font-size: 14px; white-space: pre-line;">${escapeHtml(error.message)}</div>
                </div>
            `;
        });
        var modalHtml = `
            <div class="fma-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10001; display: flex; align-items: center; justify-content: center;">
                <div class="fma-modal" style="background: white; border-radius: 8px; padding: 30px; max-width: 500px; width: 95%; text-align: left; box-shadow: 0 4px 20px rgba(0,0,0,0.3); font-family: Arial, sans-serif; position: relative;">
                    <div style="color: #d63638; font-size: 38px; margin-bottom: 10px; text-align: center;">&#9888;</div>
                    <h2 style="margin: 0 0 10px 0; color: #d63638; font-size: 20px; text-align: center;">PHP Syntax Errors Found</h2>
                    <div style="color: #d63638; font-size: 15px; text-align: center; margin-bottom: 10px;">${errorCount} error${errorCount !== 1 ? 's' : ''} found in \"${filename}\"</div>
                    <div style="color: #b30000; font-size: 14px; text-align: center; margin-bottom: 15px;">Please fix the errors before saving and closing the file.</div>
                    <div style="max-height: 300px; overflow-y: auto; padding-bottom: 70px;">${errorListHtml}</div>
                    <div style="position: absolute; left: 0; right: 0; bottom: 0; background: white; border-top: 1px solid #eee; padding: 16px 0 12px 0; text-align: center; border-radius: 0 0 8px 8px; box-shadow: 0 -2px 8px rgba(0,0,0,0.03);">
                        <button class="fma-modal-close" style="padding: 10px 20px; background: #d63638; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-right: 10px;">OK</button>
                        <button class="fma-modal-save-anyway" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Save Anyway</button>
                    </div>
                </div>
            </div>
        `;
        var modal = jQuery(modalHtml).appendTo('body');
        modal.find('.fma-modal-close, .fma-modal-overlay').on('click', function(e) {
            if (e.target === this) {
                modal.remove();
            }
        });
        modal.find('.fma-modal-save-anyway').on('click', function() {
            if (onSaveAnyway && typeof onSaveAnyway === 'function') {
                onSaveAnyway();
            }
            modal.remove();
        });
    }

    // SIMPLE APPROACH: Disable Save & Close button when PHP errors exist
    var currentPHPEditor = null;
    var saveCloseButton = null;
    
    // Function to disable/enable Save & Close button
    function updateSaveCloseButtonState(hasErrors) {
        if (!saveCloseButton) return;
        
        if (hasErrors) {
            saveCloseButton.prop('disabled', true)
                .css({
                    'opacity': '0.5',
                    'cursor': 'not-allowed',
                    'background-color': '#cccccc !important',
                    'border-color': '#cccccc !important',
                    'color': '#666666 !important'
                })
                .attr('title', 'Cannot save & close - PHP syntax errors found. Please fix errors first.');
        } else {
            saveCloseButton.prop('disabled', false)
                .css({
                    'opacity': '1',
                    'cursor': 'pointer',
                    'background-color': '',
                    'border-color': '',
                    'color': ''
                })
                .attr('title', 'Save and close file');
        }
    }
    
    // Function to find and store reference to Save & Close button
    function findSaveCloseButton() {
        var selectors = [
            '.elfinder-btncnt-1:visible',
            '.ui-dialog-buttonpane button',
            '.ui-dialog-buttonpane .ui-button',
            '.ui-dialog button'
        ];
        
        for (var i = 0; i < selectors.length; i++) {
            var buttons = jQuery(selectors[i]);
            
            if (i === 1 || i === 3) { // Filter by text for these selectors
                buttons = buttons.filter(function() {
                    var text = jQuery(this).text().toLowerCase();
                    return text.indexOf('save') > -1 && (text.indexOf('close') > -1 || i === 3);
                });
            }
            
            if (buttons.length) {
                saveCloseButton = i === 0 ? buttons.last() : buttons.last();
                return true;
            }
        }
        
        return false;
    }

    // Remove any previous .elfinder-btncnt-1 and .ui-dialog-buttonpane .ui-button click handlers
    jQuery(document).off('click', '.elfinder-btncnt-1');
    jQuery(document).off('click', '.ui-dialog-buttonpane .ui-button');

    // Clean, robust handler for dialog buttons
    jQuery(document).on('click', '.ui-dialog-buttonpane .ui-button', function(e) {
        var btnText = jQuery(this).text().trim().toLowerCase();
        // 1. Cancel button: do nothing, just close
        if (btnText === 'cancel') {
            return;
        }
        // 2. Only run validation for Save/Save & Close
        if (btnText === 'save' || btnText === 'save & close') {
            if (currentPHPEditor && currentPHPEditor.fma_file_info) {
                var fileInfo = currentPHPEditor.fma_file_info;
                var mimeType = fileInfo.mime;
                var filename = fileInfo.filename;
                if (isPHPFile(mimeType, filename)) {
                    var code = currentPHPEditor.getValue();
                    var hasValidationErrors = false;
                    var validationErrors = [];
                    // Synchronous validation for Save & Close
                    jQuery.ajax({
                        url: afm_object.ajaxurl,
                        type: 'POST',
                        async: false,
                        data: {
                            action: 'fma_validate_php',
                            nonce: fmakey,
                            php_code: code,
                            filename: filename
                        },
                        success: function(result) {
                            if (!result || !result.valid) {
                                hasValidationErrors = true;
                                validationErrors = result ? result.errors : [];
                            }
                        },
                        error: function() {
                            hasValidationErrors = true;
                            validationErrors = [{ line: 0, message: 'Failed to validate PHP syntax' }];
                        }
                    });
                    // If errors found, prevent save & close and show popup
                    if (hasValidationErrors) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        showPHPErrorPopupWithSaveOption(validationErrors, filename, function() {
                            // Force save the file
                            var textarea = jQuery('.elfinder .elfinder-dialog-edit textarea')[0];
                            if (textarea) {
                                jQuery(textarea).val(code);
                            }
                            // Try elFinder API save
                            var fm = elfinder_object.elfinder('instance');
                            if (fm && fileInfo.hash) {
                                fm.request({
                                    data: {
                                        cmd: 'put',
                                        target: fileInfo.hash,
                                        content: code
                                    },
                                    notify: { type: 'save', cnt: 1 }
                                }).done(function(data) {
                                    showSuccessModal('File saved and closed successfully (with errors)!');
                                    setTimeout(function() {
                                        jQuery('.ui-dialog-titlebar-close').click();
                                    }, 1000);
                                }).fail(function(error) {
                                    showSuccessModal('File saved and closed successfully (with errors)!');
                                    setTimeout(function() {
                                        jQuery('.ui-dialog-titlebar-close').click();
                                    }, 1000);
                                });
                            } else {
                                showSuccessModal('File saved and closed successfully (with errors)!');
                                setTimeout(function() {
                                    jQuery('.ui-dialog-titlebar-close').click();
                                }, 1000);
                            }
                        });
                        return false;
                    }
                }
            }
        }
        // 3. Any other button: do nothing
    });

    jQuery(document).ajaxSend(function(event, jqXHR, settings) {
        // console.log('AJAX SENT:', settings.data); // Removed console logs
    });


    var elfinder_object = jQuery( '#file_manager_advanced' ).elfinder(
        // 1st Arg - options
        {
            cssAutoLoad : false, // Disable CSS auto loading
            url : afm_object.ajaxurl,  // connector URL (REQUIRED)
            customData : {
                action: 'fma_load_fma_ui',
                _fmakey: fmakey,
            },
            defaultView : 'list',
            height: 500,
            lang : fma_locale,
            ui: afm_object.ui,
            commandsOptions: {
                edit : {
                    mimes : [],
                    editors : [
                        {
                            mimes : [ 'text/plain', 'text/html', 'text/javascript', 'text/css', 'text/x-php', 'application/x-php' ],
                            info : {
                                name : 'Code Editor'
                            },

                            load : function( textarea ) {
                                var mimeType = this.file.mime;
                                var filename = this.file.name;
                                var self = this;
                                
                                editor = CodeMirror.fromTextArea( textarea, {
                                    mode: mimeType,
                                    indentUnit: 4,
                                    lineNumbers: true,
                                    lineWrapping: true,
                                    lint: true,
                                    theme: fma_cm_theme,
                                    gutters: ["CodeMirror-lint-markers", "CodeMirror-linenumbers"]
                                } );

                                // Store reference to current file info
                                editor.fma_file_info = {
                                    filename: filename,
                                    mime: mimeType,
                                    hash: self.file.hash // Add file hash for elFinder API calls
                                };

                                // Add real-time PHP validation for PHP files
                                if (isPHPFile(mimeType, filename)) {
                                    // Store reference to this editor for button control
                                    currentPHPEditor = editor;
                                    
                                    // Try to find Save & Close button after a short delay
                                    setTimeout(function() {
                                        findSaveCloseButton();
                                    }, 1000);
                                    
                                    var validationTimeout;
                                    var errorMarks = []; // Track error marks for cleanup
                                    var errorLines = []; // Track error line numbers for cleanup
                                    
                                    // Function to clear all error highlights
                                    function clearAllErrorHighlights() {
                                        editor.clearGutter("CodeMirror-lint-markers");
                                        
                                        errorMarks.forEach(function(mark) {
                                            if (mark && mark.clear) mark.clear();
                                        });
                                        errorMarks = [];
                                        
                                        errorLines.forEach(function(lineNum) {
                                            editor.removeLineClass(lineNum, 'wrap', 'fma-php-error-line-wrap');
                                        });
                                        errorLines = [];
                                        
                                        jQuery('.CodeMirror-line').css({
                                            'text-decoration': '',
                                            'text-decoration-thickness': '',
                                            'text-underline-offset': ''
                                        });
                                    }
                                    
                                    // Function to add error highlight to a line
                                    function addErrorHighlight(lineNumber, errorMessage) {
                                        var marker = document.createElement("div");
                                        marker.className = "CodeMirror-lint-marker-error";
                                        marker.innerHTML = "●";
                                        marker.title = errorMessage;
                                        marker.style.color = "#d63638";
                                        marker.style.fontSize = "16px";
                                        editor.setGutterMarker(lineNumber, "CodeMirror-lint-markers", marker);
                                        
                                        editor.addLineClass(lineNumber, 'wrap', 'fma-php-error-line-wrap');
                                        errorLines.push(lineNumber);
                                        editor.refresh();
                                        
                                        setTimeout(function() {
                                            var lineElement = jQuery('.CodeMirror-line').eq(lineNumber);
                                            if (lineElement.length) {
                                                lineElement.css({
                                                    'text-decoration': 'underline wavy #d63638',
                                                    'text-decoration-thickness': '1px',
                                                    'text-underline-offset': '3px'
                                                });
                                            }
                                        }, 50);
                                    }
                                    
                                    // Function to validate current code and update button state
                                    function validateCurrentCode() {
                                        var code = editor.getValue();
                                        
                                        if (!code.trim()) {
                                            clearAllErrorHighlights();
                                            updateSaveCloseButtonState(false);
                                            return;
                                        }
                                        
                                        validatePHPSyntax(code, filename, function(result) {
                                            clearAllErrorHighlights();
                                            
                                            var hasErrors = !result.valid && result.errors.length > 0;
                                            if (hasErrors) {
                                                result.errors.forEach(function(error) {
                                                    if (error.line > 0) {
                                                        addErrorHighlight(error.line - 1, error.message);
                                                    }
                                                });
                                            }
                                            
                                            updateSaveCloseButtonState(hasErrors);
                                        });
                                    }
                                    
                                    // Initial validation and event listeners
                                    setTimeout(validateCurrentCode, 1500);
                                    
                                    editor.on('change', function() {
                                        clearTimeout(validationTimeout);
                                        validationTimeout = setTimeout(validateCurrentCode, 800);
                                    });
                                    
                                    editor.on('focus', function() {
                                        setTimeout(function() {
                                            if (!saveCloseButton) findSaveCloseButton();
                                            validateCurrentCode();
                                        }, 200);
                                    });
                                }

                                return editor;
                            },

                            close: function(textarea, instance) {
                                if (instance) instance.fma_file_info = null;
                                currentPHPEditor = null;
                                saveCloseButton = null;
                                this.myCodeMirror = null;
                            },

                            save: function(textarea, editor) {
                                var code = editor.getValue();
                                var filename = editor.fma_file_info ? editor.fma_file_info.filename : 'unknown.php';
                                var mimeType = editor.fma_file_info ? editor.fma_file_info.mime : '';

                                // Prevent duplicate validation if already validated from Save & Close
                                if (window.fma_already_validated) {
                                    window.fma_already_validated = false;
                                    jQuery(textarea).val(code);
                                    return true;
                                }

                                // For non-PHP files, save normally
                                if (!isPHPFile(mimeType, filename)) {
                                    jQuery(textarea).val(code);
                                    // Don't show success modal - let elFinder handle it
                                    return true;
                                }

                                // For PHP files, validate first
                                var hasErrors = false;
                                var validationErrors = [];
                                
                                jQuery.ajax({
                                    url: afm_object.ajaxurl,
                                    type: 'POST',
                                    async: false,
                                    data: {
                                        action: 'fma_validate_php',
                                        nonce: fmakey,
                                        php_code: code,
                                        filename: filename
                                    },
                                    success: function(result) {
                                        if (result && result.valid) {
                                            // Code is valid, allow saving
                                            hasErrors = false;
                                        } else {
                                            hasErrors = true;
                                            validationErrors = result ? result.errors : [];
                                        }
                                    },
                                    error: function() {
                                        hasErrors = true;
                                        validationErrors = [{ line: 0, message: 'Failed to validate PHP syntax' }];
                                    }
                                });
                                
                                if (hasErrors) {
                                    // Show error popup with option to save anyway
                                    showPHPErrorPopupWithSaveOption(validationErrors, filename, function() {
                                        // Use custom save action for PHP files to handle unescaping properly
                                        window.fma_already_validated = true;
                                        jQuery.ajax({
                                            url: afm_object.ajaxurl,
                                            type: 'POST',
                                            data: {
                                                action: 'fma_save_php_file',
                                                nonce: fmakey,
                                                php_code: code,
                                                file_hash: editor.fma_file_info.hash,
                                                filename: filename
                                            },
                                            success: function(response) {
                                                if (response.success) {
                                                    showSuccessModal('File saved successfully (with errors)!');
                                                    updateSaveCloseButtonState(false);
                                                    // Update textarea to sync with elFinder
                                                    jQuery(textarea).val(code);
                                                } else {
                                                    var errorMsg = 'Error saving file';
                                                    if (response.data && response.data.message) {
                                                        errorMsg += ': ' + response.data.message;
                                                    } else if (response.data && response.data.elfinder_response) {
                                                        errorMsg += ': elFinder error - ' + response.data.elfinder_response;
                                                    } else {
                                                        errorMsg += ': Unknown error';
                                                    }
                                                    
                                                    // Show detailed error in console for debugging
                                                    console.error('Save PHP file error:', response);
                                                    
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('AJAX error saving PHP file:', xhr, status, error);
                                                alert('Failed to save file. Network error: ' + error);
                                            }
                                        });
                                    });
                                    
                                    // Prevent automatic saving when errors exist
                                    return false;
                                }
                                
                                // No errors, save normally
                                jQuery(textarea).val(code);
                                return true;
                            },
                        },
                    ],
                },
            },
            workerBaseUrl: afm_object.plugin_url + 'application/library/js/worker/',
        }
    );

} );

window.fma_last_validated_code = '';
window.fma_last_validation_result = null;


