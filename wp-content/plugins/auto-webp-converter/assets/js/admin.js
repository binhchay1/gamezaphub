/**
 * Auto WebP Converter Admin JavaScript
 */

(function($) {
    'use strict';
    
    var AWCAdmin = {
        init: function() {
            this.bindEvents();
            this.checkSystemStatus();
        },
        
        bindEvents: function() {
            $('#awc-start-batch').on('click', this.startBatchProcessing);
            $('#awc-reset-batch').on('click', this.resetBatchProcessing);
            $('#awc-refresh-status').on('click', this.refreshStatus);
            
            this.startProgressRefresh();
        },
        
        startBatchProcessing: function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to start batch processing? This may take a while.')) {
                return;
            }
            
            var $button = $(this);
            var $container = $('.awc-admin-container');
            
            $button.prop('disabled', true).text('Starting...');
            $container.addClass('awc-loading');
            
            AWCAdmin.processBatch();
        },
        
        resetBatchProcessing: function(e) {
            e.preventDefault();
            
            if (!confirm(awc_ajax.strings.confirm_reset)) {
                return;
            }
            
            var $button = $(this);
            
            $button.prop('disabled', true).text('Resetting...');
            
            $.ajax({
                url: awc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'awc_reset_batch',
                    nonce: awc_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error resetting batch processing: ' + response.data);
                    }
                },
                error: function() {
                    alert('Error resetting batch processing. Please try again.');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Reset');
                }
            });
        },
        
        refreshStatus: function(e) {
            e.preventDefault();
            
            var $button = $(this);
            $button.prop('disabled', true).text('Refreshing...');
            
            AWCAdmin.refreshProgress();
            AWCAdmin.refreshLog();
            
            setTimeout(function() {
                $button.prop('disabled', false).text('Refresh Status');
            }, 1000);
        },
        
        processBatch: function() {
            $.ajax({
                url: awc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'awc_batch_convert',
                    nonce: awc_ajax.nonce
                },
                success: function(response) {
                    
                    if (response.success && response.data) {
                        AWCAdmin.updateProgress(response.data);
                        
                        var status = response.data.status || 'processing';
                        var progress = response.data.progress || response.data;
                        
                        if (status === 'processing' && progress && progress.processed_files < progress.total_files) {
                            setTimeout(function() {
                                AWCAdmin.processBatch();
                            }, 2000);
                        } else if (status === 'completed' || (progress && progress.processed_files >= progress.total_files)) {
                            AWCAdmin.completeBatchProcessing();
                        } else {
                            AWCAdmin.showNotice('Batch processing stopped with unknown status: ' + status, 'warning');
                        }
                    } else {
                        var errorMsg = response.data || 'Unknown error occurred';
                        AWCAdmin.handleError('Error processing batch: ' + errorMsg);
                    }
                },
                error: function(xhr, status, error) {
                    AWCAdmin.handleError('Error processing batch. Please try again.');
                }
            });
        },
        
        updateProgress: function(data) {
            if (!data) {
                return;
            }
            
            var progress = data.progress || data;
            
            if (!progress) {
                return;
            }
            
            var $progressFill = $('.awc-progress-fill');
            var $progressText = $('.awc-progress-text');
            
            if ($progressFill.length > 0) {
                $progressFill.css('width', (progress.percentage || 0) + '%');
            }
            
            if ($progressText.length > 0) {
                $progressText.text(
                    (progress.processed_files || 0) + ' / ' + (progress.total_files || 0) + 
                    ' files processed (' + (progress.percentage || 0) + '%)'
                );
            }
            
            $('.awc-stat-item').each(function() {
                var $item = $(this);
                var label = $item.find('.awc-stat-label').text().toLowerCase();
                var $value = $item.find('.awc-stat-value');
                
                if ($value.length > 0) {
                    if (label.includes('total')) {
                        $value.text(progress.total_files || 0);
                    } else if (label.includes('converted')) {
                        $value.text(progress.converted_files || 0);
                    } else if (label.includes('skipped')) {
                        $value.text(progress.skipped_files || 0);
                    } else if (label.includes('errors')) {
                        $value.text(progress.error_files || 0);
                    }
                }
            });
            
            if (progress.current_file) {
                var $currentFile = $('.awc-current-file');
                if ($currentFile.length > 0) {
                    $currentFile.html(
                        '<strong>Currently processing:</strong> ' + 
                        progress.current_file.split('/').pop()
                    ).show();
                }
            }
            
            if (progress.processed_files > 0 && progress.total_files > progress.processed_files && progress.start_time) {
                var remaining = progress.total_files - progress.processed_files;
                var rate = progress.processed_files / ((Date.now() - (progress.start_time * 1000)) / 1000);
                var eta = Math.round(remaining / rate);
                
                if (eta > 0) {
                    var $currentFile = $('.awc-current-file');
                    if ($currentFile.length > 0) {
                        $currentFile.append('<br><strong>Estimated time remaining:</strong> ' + AWCAdmin.formatDuration(eta));
                    }
                }
            }
            
            if (data.results && data.results.details && Array.isArray(data.results.details)) {
                AWCAdmin.updateLogFromDetails(data.results.details);
            }
        },
        
        updateLogFromDetails: function(details) {
            if (!details || !Array.isArray(details)) {
                return;
            }
            
            var $logContainer = $('.awc-log-entries');
            if ($logContainer.length === 0) {
                return;
            }
            
            details.forEach(function(detail) {
                if (!detail) return;
                
                var timestamp = new Date().toLocaleTimeString();
                var status = detail.status || 'unknown';
                var message = detail.message || 'No message';
                var filename = detail.file ? detail.file.split('/').pop() : 'Unknown';
                
                var logEntry = '[' + timestamp + '] ' + filename + ': ' + status + ' - ' + message;
                
                $logContainer.append('<div class="awc-log-entry">' + logEntry + '</div>');
            });
            
            var $entries = $logContainer.find('.awc-log-entry');
            if ($entries.length > 50) {
                $entries.slice(0, $entries.length - 50).remove();
            }
            
            if ($logContainer.length > 0 && $logContainer[0]) {
                $logContainer.scrollTop($logContainer[0].scrollHeight);
            }
        },
        
        completeBatchProcessing: function() {
            var $button = $('#awc-start-batch');
            var $container = $('.awc-admin-container');
            
            $button.prop('disabled', false).text('Start Batch Conversion');
            $container.removeClass('awc-loading');
            
            AWCAdmin.showNotice('Batch processing completed successfully!', 'success');
            
            $('#awc-reset-batch').prop('disabled', false);
            
            AWCAdmin.refreshProgress();
            AWCAdmin.refreshLog();
        },
        
        handleError: function(message) {
            var $button = $('#awc-start-batch');
            var $container = $('.awc-admin-container');
            
            $button.prop('disabled', false).text('Start Batch Conversion');
            $container.removeClass('awc-loading');
            
            AWCAdmin.showNotice(message, 'error');
            
        },
        
        showNotice: function(message, type) {
            type = type || 'info';
            
            var $notice = $('<div class="awc-notice awc-notice-' + type + '">' + message + '</div>');
            
            $('.awc-section').first().before($notice);
            
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },
        
        startProgressRefresh: function() {
            var $button = $('#awc-start-batch');
            if ($button.prop('disabled')) {
                setInterval(function() {
                    AWCAdmin.refreshProgress();
                    AWCAdmin.refreshLog();
                }, 2000);
            }
            
            AWCAdmin.checkAndResumeProcessing();
            
            setInterval(function() {
                AWCAdmin.checkAndResumeProcessing();
            }, 10000);
        },
        
        checkAndResumeProcessing: function() {
            $.ajax({
                url: awc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'awc_get_progress',
                    nonce: awc_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        var progress = response.data;
                        var $button = $('#awc-start-batch');
                        
                        if (progress.status === 'processing' && !$button.prop('disabled') && progress.processed_files < progress.total_files) {
                            AWCAdmin.showNotice('Batch processing was interrupted. Resuming...', 'warning');
                            $button.prop('disabled', true).text('Resuming...');
                            AWCAdmin.processBatch();
                        }
                    }
                }
            });
        },
        
        refreshProgress: function() {
            $.ajax({
                url: awc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'awc_get_progress',
                    nonce: awc_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        AWCAdmin.updateProgress(response.data);
                        
                        if (response.data.status === 'completed') {
                            AWCAdmin.completeBatchProcessing();
                        }
                    }
                }
            });
        },
        
        refreshLog: function() {
            $.ajax({
                url: awc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'awc_get_log',
                    nonce: awc_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data.log) {
                        AWCAdmin.updateLog(response.data.log);
                    }
                }
            });
        },
        
        updateLog: function(logEntries) {
            if (!logEntries || !Array.isArray(logEntries)) {
                return;
            }
            
            var $logContainer = $('.awc-log-entries');
            if ($logContainer.length === 0) {
                return;
            }
            
            if (logEntries.length === 0) {
                $logContainer.html('<p>No log entries yet.</p>');
                return;
            }
            
            var logHtml = '';
            logEntries.forEach(function(entry) {
                if (entry) {
                    logHtml += '<div class="awc-log-entry">' + entry + '</div>';
                }
            });
            
            $logContainer.html(logHtml);
            
            var $logWrapper = $('.awc-log-container');
            if ($logWrapper.length > 0 && $logWrapper[0]) {
                $logWrapper.scrollTop($logWrapper[0].scrollHeight);
            }
        },
        
        checkSystemStatus: function() {
            if (!this.supportsWebP()) {
                AWCAdmin.showNotice(
                    'WebP is not supported by your server. Please install the GD extension with WebP support.',
                    'error'
                );
            }
            
            $.ajax({
                url: awc_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'awc_check_conflicts',
                    nonce: awc_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data.conflicts.length > 0) {
                        response.data.conflicts.forEach(function(conflict) {
                            AWCAdmin.showNotice(conflict, 'warning');
                        });
                    }
                }
            });
        },
        
        supportsWebP: function() {
            var canvas = document.createElement('canvas');
            canvas.width = 1;
            canvas.height = 1;
            
            return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
        },
        
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            var k = 1024;
            var sizes = ['Bytes', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        formatDuration: function(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var secs = seconds % 60;
            
            if (hours > 0) {
                return hours + 'h ' + minutes + 'm ' + secs + 's';
            } else if (minutes > 0) {
                return minutes + 'm ' + secs + 's';
            } else {
                return secs + 's';
            }
        }
    };
    
    $(document).ready(function() {
        AWCAdmin.init();
    });
    
    window.AWCAdmin = AWCAdmin;
    
})(jQuery);
