jQuery( document ).ready( function() {
    // Check if debug feature is enabled
    var debugEnabled = afm_object && afm_object.debug_enabled === '1';
    
    // Global variables for error tracking
    var hasErrors = false;
    var currentEditor = null;
    var currentErrors = [];
    var lastButtonState = null;
    var tooltipTimeout = null;
    var lastTooltipLine = null;
    var isSaveButtonClicked = false;
    
    // CSS styles for error highlighting
    var errorStyles = `
        <style>
        .fma-error-line {
            background-color: #fed7d7 !important;
            border-left: 3px solid #c53030 !important;
        }
        .fma-error-underline {
            text-decoration: underline wavy #c53030 !important;
            text-decoration-thickness: 2px !important;
        }
        .fma-error-marker {
            color: #c53030 !important;
            font-size: 14px !important;
            font-weight: bold !important;
            text-align: center !important;
            line-height: 1 !important;
        }
        .fma-error-gutter {
            background-color: #fed7d7 !important;
            border-right: 2px solid #c53030 !important;
        }
        .fma-save-close-disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }
        .fma-error-tooltip {
            position: absolute;
            background: #2d3748;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            z-index: 10000;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-width: 300px;
            word-wrap: break-word;
            line-height: 1.4;
        }
        .fma-error-tooltip::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 5px solid #2d3748;
        }
        .fma-error-tooltip .error-title {
            font-weight: bold;
            color: #feb2b2;
            margin-bottom: 4px;
        }
        .fma-error-tooltip .error-message {
            color: #e2e8f0;
        }
        .fma-error-tooltip .error-line {
            color: #a0aec0;
            font-size: 11px;
            margin-top: 4px;
        }
        </style>
    `;
    jQuery('head').append(errorStyles);

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

    // PHP Debug Analysis function
    function analyzePHPDebug( code, filename, callback ) {
        jQuery.ajax({
            url: afm_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'fma_debug_php',
                nonce: fmakey,
                php_code: code,
                filename: filename
            },
            success: function( response ) {
                if ( callback && typeof callback === 'function' ) {
                    callback( response );
                }
            },
            error: function() {
                if ( callback && typeof callback === 'function' ) {
                    callback({
                        valid: false,
                        debug_info: {},
                        message: 'Failed to analyze PHP code'
                    });
                }
            }
        });
    }


    // Highlight error lines in CodeMirror
    function highlightErrorLines(editor, errors) {
        if (!editor || !errors || errors.length === 0) {
            // No errors, clear highlights and enable save button
            clearErrorHighlights(editor);
            return;
        }

        currentErrors = errors;

        // Clear existing error highlights first
        for (var i = 0; i < editor.lineCount(); i++) {
            editor.removeLineClass(i, 'background', 'fma-error-line');
            editor.removeLineClass(i, 'text', 'fma-error-underline');
            editor.setGutterMarker(i, 'fma-error-gutter', null);
        }

        errors.forEach(function(error) {
            if (error.line && error.line > 0) {
                var lineNumber = error.line - 1;
                
                editor.addLineClass(lineNumber, 'background', 'fma-error-line');
                editor.addLineClass(lineNumber, 'text', 'fma-error-underline');
                
                var marker = document.createElement('div');
                marker.className = 'fma-error-marker';
                marker.innerHTML = '⚠️';
                marker.title = error.message;
                editor.setGutterMarker(lineNumber, 'fma-error-gutter', marker);
                setTimeout(function() {
                    try {
                        var lineElement = editor.getLineHandle(lineNumber);
                        var lineEl = null;
                        
                        if (lineElement && lineElement.element) {
                            lineEl = lineElement.element;
                        } else {
                            lineEl = jQuery('.CodeMirror-line:eq(' + lineNumber + ')')[0];
                            if (!lineEl) {
                                lineEl = jQuery('.CodeMirror-line').eq(lineNumber)[0];
                            }
                        }
                        
                        if (lineEl) {
                            jQuery(lineEl).off('mouseenter.fma-tooltip mouseleave.fma-tooltip');
                            jQuery(lineEl).on('mouseenter.fma-tooltip', function(e) {
                                showErrorTooltip(e, error);
                            });
                            jQuery(lineEl).on('mouseleave.fma-tooltip', function(e) {
                                hideErrorTooltip();
                            });
                        } else {
                            var cmContainer = editor.getWrapperElement();
                            if (cmContainer) {
                                jQuery(cmContainer).off('mouseenter.fma-tooltip-' + lineNumber + ' mouseleave.fma-tooltip-' + lineNumber);
                                jQuery(cmContainer).on('mouseenter.fma-tooltip-' + lineNumber, function(e) {
                                    var coords = editor.coordsChar({top: e.pageY, left: e.pageX}, 'page');
                                    if (coords.line === lineNumber) {
                                        showErrorTooltip(e, error);
                                    }
                                });
                                jQuery(cmContainer).on('mouseleave.fma-tooltip-' + lineNumber, function(e) {
                                    hideErrorTooltip();
                                });
                            }
                        }
                    } catch (err) {
                        // Ignore tooltip event errors
                    }
                }, 200);
            }
        });
        
        hasErrors = true;
        updateSaveCloseButton();
    }

    // Clear error highlights
    function clearErrorHighlights(editor) {
        if (!editor) return;
        
        for (var i = 0; i < editor.lineCount(); i++) {
            editor.removeLineClass(i, 'background', 'fma-error-line');
            editor.removeLineClass(i, 'text', 'fma-error-underline');
            editor.setGutterMarker(i, 'fma-error-gutter', null);
            
            try {
                var lineElement = editor.getLineHandle(i);
                if (lineElement && lineElement.element) {
                    var lineEl = lineElement.element;
                    jQuery(lineEl).off('mouseenter.fma-tooltip mouseleave.fma-tooltip');
                }
                
                var cmContainer = editor.getWrapperElement();
                if (cmContainer) {
                    jQuery(cmContainer).off('mouseenter.fma-tooltip-' + i + ' mouseleave.fma-tooltip-' + i);
                }
            } catch (err) {
                // Ignore tooltip event errors
            }
        }
        
        hideErrorTooltip();
        currentErrors = [];
        hasErrors = false;
        updateSaveCloseButton();
    }


    // Show error tooltip on hover
    function showErrorTooltip(event, error) {
        // Clear existing timeout
        if (tooltipTimeout) {
            clearTimeout(tooltipTimeout);
        }
        
        // Check if we're already showing tooltip for this line
        if (lastTooltipLine === error.line) {
            return;
        }
        
        // Debounce tooltip showing
        tooltipTimeout = setTimeout(function() {
            // Remove existing tooltip
            jQuery('.fma-error-tooltip').remove();
            
            var tooltipHtml = `
                <div class="fma-error-tooltip">
                    <div class="error-title">⚠️ PHP Error</div>
                    <div class="error-message">${error.message}</div>
                    <div class="error-line">Line ${error.line}</div>
                </div>
            `;
            
            var tooltip = jQuery(tooltipHtml).appendTo('body');
            
            // Position tooltip
            var x = event.pageX;
            var y = event.pageY - 10;
            
            // Adjust position if tooltip goes off screen
            var tooltipWidth = tooltip.outerWidth();
            var tooltipHeight = tooltip.outerHeight();
            var windowWidth = jQuery(window).width();
            var windowHeight = jQuery(window).height();
            
            if (x + tooltipWidth > windowWidth) {
                x = windowWidth - tooltipWidth - 10;
            }
            if (y - tooltipHeight < 0) {
                y = event.pageY + 20;
            }
            
            tooltip.css({
                left: x + 'px',
                top: y + 'px'
            });
            
            lastTooltipLine = error.line;
        }, 200); // 200ms debounce
    }

    // Hide error tooltip
    function hideErrorTooltip() {
        // Clear timeout
        if (tooltipTimeout) {
            clearTimeout(tooltipTimeout);
        }
        
        // Remove tooltip
        jQuery('.fma-error-tooltip').remove();
        
        // Reset tracking
        lastTooltipLine = null;
    }

    // Get error for specific line number
    function getErrorForLine(lineNumber) {
        for (var i = 0; i < currentErrors.length; i++) {
            if (currentErrors[i].line === lineNumber + 1) { // Convert to 1-based
                return currentErrors[i];
            }
        }
        return null;
    }

    // Update Save & Close button state based on error status
    function updateSaveCloseButton() {
        // Check if state has changed to avoid unnecessary updates
        var currentState = hasErrors ? 'disabled' : 'enabled';
        if (lastButtonState === currentState) {
            return; // No change needed
        }
        lastButtonState = currentState;
        
        // Find the Save & Close button with multiple selectors
        var selectors = [
            '.elfinder-button-save-close',
            '.elfinder-button-save', 
            '[title*="Save"]',
            '[title*="save"]',
            '.ui-button[title*="Save"]',
            '.ui-button[title*="save"]',
            'button[title*="Save"]',
            'button[title*="save"]',
            '.elfinder-toolbar button[title*="Save"]',
            '.elfinder-toolbar button[title*="save"]',
            '.elfinder-toolbar .ui-button[title*="Save"]',
            '.elfinder-toolbar .ui-button[title*="save"]',
            '.elfinder .ui-button[title*="Save"]',
            '.elfinder .ui-button[title*="save"]',
            '.elfinder button[title*="Save"]',
            '.elfinder button[title*="save"]'
        ];
        
        var saveCloseBtn = jQuery(selectors.join(', ')).filter(':visible');
        
        // Also try to find buttons by text content
        if (saveCloseBtn.length === 0) {
            var textSelectors = [
                'button:contains("Save")',
                'button:contains("save")',
                '.ui-button:contains("Save")',
                '.ui-button:contains("save")',
                '.elfinder button:contains("Save")',
                '.elfinder button:contains("save")',
                '.elfinder .ui-button:contains("Save")',
                '.elfinder .ui-button:contains("save")'
            ];
            saveCloseBtn = jQuery(textSelectors.join(', ')).filter(':visible');
        }
        
        if (saveCloseBtn.length > 0) {
            if (hasErrors) {
                // Disable button when errors exist
                saveCloseBtn.addClass('fma-save-close-disabled');
                saveCloseBtn.attr('disabled', 'disabled');
                saveCloseBtn.attr('title', 'Please fix PHP errors before saving');
                saveCloseBtn.css('opacity', '0.5');
                saveCloseBtn.css('cursor', 'not-allowed');
                saveCloseBtn.prop('disabled', true);
                saveCloseBtn.off('click.fma-disable'); // Remove existing handlers
                saveCloseBtn.on('click.fma-disable', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
                
                // Track when save button is actually clicked
                saveCloseBtn.on('click.fma-save-track', function(e) {
                    isSaveButtonClicked = true;
                    setTimeout(function() {
                        isSaveButtonClicked = false;
                    }, 1000);
                });
            } else {
                // Enable button when no errors
                saveCloseBtn.removeClass('fma-save-close-disabled');
                saveCloseBtn.removeAttr('disabled');
                saveCloseBtn.attr('title', 'Save & Close');
                saveCloseBtn.css('opacity', '1');
                saveCloseBtn.css('cursor', 'pointer');
                saveCloseBtn.prop('disabled', false);
                saveCloseBtn.off('click.fma-disable'); // Remove disable handlers
                
                // Track when save button is actually clicked
                saveCloseBtn.off('click.fma-save-track'); // Remove existing handlers
                saveCloseBtn.on('click.fma-save-track', function(e) {
                    isSaveButtonClicked = true;
                    setTimeout(function() {
                        isSaveButtonClicked = false;
                    }, 1000);
                });
            }
        }
    }

    // Periodic button check (since elFinder buttons load dynamically)
    setInterval(function() {
        if (currentEditor && hasErrors) {
            // Only check when there are errors to avoid unnecessary updates
            updateSaveCloseButton();
        }
    }, 10000); // Reduced frequency to every 10 seconds and only when errors exist

    // Show error popup on save attempt
    function showErrorSavePopup(errors, callback) {
        var errorList = errors.map(function(error) {
            return `Line ${error.line}: ${error.message}`;
        }).join('<br>');

        var popupHtml = `
            <div class="fma-modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; justify-content: center; align-items: center;">
                <div class="fma-error-popup" style="background: white; border-radius: 8px; padding: 20px; max-width: 500px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="font-size: 24px; margin-right: 10px;">⚠️</div>
                        <h3 style="margin: 0; color: #c53030; font-size: 18px;">PHP Syntax Errors Found</h3>
                    </div>
                    <div style="margin-bottom: 20px; color: #4a5568; line-height: 1.5;">
                        <p style="margin: 0 0 10px 0;">Please fix the following errors before saving:</p>
                        <div style="background: #fed7d7; padding: 10px; border-radius: 4px; border-left: 3px solid #c53030; font-family: monospace; font-size: 12px;">
                            ${errorList}
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button class="fma-error-okay" style="background: #4299e1; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 14px;">
                            Okay
                        </button>
                        <button class="fma-error-save-anyway" style="background: #c53030; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 14px;">
                            Save Anyway
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing popup
        jQuery('.fma-modal-overlay').remove();
        
        // Add new popup
        var popup = jQuery(popupHtml).appendTo('body');
        
        // Okay button - close popup and return to editor
        popup.find('.fma-error-okay').on('click', function() {
            popup.remove();
            if (callback) callback(false); // Don't save
        });
        
        // Save Anyway button - close popup and save file
        popup.find('.fma-error-save-anyway').on('click', function() {
            popup.remove();
            if (callback) callback(true); // Save anyway
        });
        
        // Close on overlay click
        popup.find('.fma-modal-overlay').on('click', function(e) {
            if (e.target === this) {
                popup.remove();
                if (callback) callback(false); // Don't save
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
                                    hash: self.file.hash
                                };

                                // Add debug feature for PHP files (only if enabled)
                                if (debugEnabled && (mimeType === 'text/x-php' || mimeType === 'application/x-php' || filename.toLowerCase().endsWith('.php'))) {
                                    var debugTimeout;
                                    
                                    // Function to analyze code for debug info
                                    function analyzeCode() {
                                        var code = editor.getValue();
                                        
                                        if (!code.trim()) {
                                            return;
                                        }
                                        
                                        analyzePHPDebug(code, filename, function(result) {
                                            if (!result.valid && result.errors) {
                                                // Only highlight error lines, no panels
                                                highlightErrorLines(editor, result.errors);
                                            }
                                        });
                                    }
                                    
                                    // Add keyboard shortcut for debug analysis (Ctrl+Shift+D)
                                    editor.on('keydown', function(cm, event) {
                                        if (event.ctrlKey && event.shiftKey && event.keyCode === 68) { // Ctrl+Shift+D
                                            event.preventDefault();
                                            analyzeCode();
                                        }
                                    });
                                    
                                    // Auto-analyze after 3 seconds of inactivity
                                    editor.on('change', function() {
                                        clearTimeout(debugTimeout);
                                        debugTimeout = setTimeout(analyzeCode, 3000);
                                        
                                        // Also do immediate analysis for real-time feedback
                                        setTimeout(function() {
                                            var code = editor.getValue();
                                            if (code.trim()) {
                                                analyzePHPDebug(code, filename, function(result) {
                                                    if (result.valid) {
                                                        // No errors, clear highlights and enable button
                                                        clearErrorHighlights(editor);
                                                        hasErrors = false;
                                                        updateSaveCloseButton();
                                                    } else if (!result.valid && result.errors) {
                                                        // Still has errors, update highlights
                                                        highlightErrorLines(editor, result.errors);
                                                    }
                                                });
                                            }
                                        }, 1000); // Quick analysis after 1 second
                                    });
                                    
                                    // Initial analysis
                                    setTimeout(analyzeCode, 2000);
                                }

                                // Store current editor reference
                                currentEditor = editor;
                                
                                // Add global mouse move listener for tooltip
                                jQuery(document).off('mousemove.fma-tooltip');
                                jQuery(document).on('mousemove.fma-tooltip', function(e) {
                                    if (hasErrors && currentEditor) {
                                        try {
                                            var coords = currentEditor.coordsChar({top: e.pageY, left: e.pageX}, 'page');
                                            var lineNumber = coords.line;
                                            
                                            // Check if this line has an error
                                            var error = getErrorForLine(lineNumber);
                                            if (error) {
                                                // Check if mouse is over CodeMirror
                                                var cmElement = currentEditor.getWrapperElement();
                                                if (cmElement && jQuery(cmElement).is(':hover')) {
                                                    showErrorTooltip(e, error);
                                                } else {
                                                    hideErrorTooltip();
                                                }
                                            } else {
                                                hideErrorTooltip();
                                            }
                                        } catch (err) {
                                            // Ignore errors in mouse move handler
                                        }
                                    }
                                });
                                
                                // Initial button state check
                                setTimeout(function() {
                                    updateSaveCloseButton();
                                }, 500);

                                return editor;
                            },

                            close: function(textarea, instance) {
                                // Clear error highlights before closing
                                if (instance) {
                                    clearErrorHighlights(instance);
                                    instance.fma_file_info = null;
                                }
                                // Clear current editor reference
                                currentEditor = null;
                                this.myCodeMirror = null;
                            },

                            save: function(textarea, editor) {
                                var code = editor.getValue();
                                var filename = editor.fma_file_info ? editor.fma_file_info.filename : 'unknown.php';
                                
                                // Check if it's a PHP file
                                if (filename.toLowerCase().endsWith('.php') || 
                                    editor.getMode().name === 'php' || 
                                    editor.getMode().name === 'application/x-httpd-php') {
                                    
                                    // Only check for errors if this is actually a save operation
                                    // Check if the save button was actually clicked
                                    var isSaveOperation = isSaveButtonClicked;
                                    
                                    // If errors exist and this is a save operation, prevent save and show popup
                                    if (hasErrors && isSaveOperation) {
                                        // Get current errors for popup
                                        analyzePHPDebug(code, filename, function(result) {
                                            if (!result.valid && result.errors) {
                                                showErrorSavePopup(result.errors, function(saveAnyway) {
                                                    if (saveAnyway) {
                                                        // User chose to save anyway
                                                        jQuery(textarea).val(code);
                                                        // Trigger the actual save
                                                        if (typeof editor.save === 'function') {
                                                            editor.save();
                                                        }
                                                    }
                                                    // If saveAnyway is false, do nothing (don't save)
                                                });
                                            }
                                        });
                                        return false; // Prevent save
                                    }
                                    
                                    // No errors or cancel operation, proceed with save
                                    jQuery(textarea).val(code);
                                    return true;
                                } else {
                                    // Not a PHP file, save normally
                                    jQuery(textarea).val(code);
                                    return true;
                                }
                            },
                        },
                    ],
                },
            },
            workerBaseUrl: afm_object.plugin_url + 'application/library/js/worker/',
        }
    );

} );



