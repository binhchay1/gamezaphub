<div class="wrap">
    <h1>Lasso URL Refresh</h1>
    <p>Click the button below to refresh all Lasso URLs with updated data from rawg.io API.</p>
    <button id="lasso-refresh-all" class="button button-primary">Refresh All Lasso URLs</button>
    <div id="lasso-refresh-status" style="margin-top: 15px;"></div>

    <h2 style="margin-top: 30px;">Realtime Logs</h2>
    <div id="lasso-refresh-logs" style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fefefe;">
        <table class="widefat fixed striped" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 150px;">Time</th>
                    <th style="width: 200px;">Game Name</th>
                    <th style="width: 100px;">Status</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody id="lasso-log-body">
                <tr>
                    <td colspan="4">Waiting for refresh...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        let isRefreshing = false;
        let lastLogId = 0;

        function colorize(status) {
            switch (status) {
                case 'success':
                    return 'green';
                case 'error':
                    return 'red';
                case 'processing':
                    return 'orange';
                case 'queued':
                    return 'blue';
                default:
                    return 'gray';
            }
        }

        function pollLogs() {
            $.post(ajaxurl, {
                action: 'lasso_get_refresh_logs',
                last_log_id: lastLogId
            }, function(response) {
                if (response.success && response.data.logs.length) {
                    response.data.logs.forEach(log => {
                        const html = `
                        <tr>
                            <td>${log.timestamp}</td>
                            <td>${log.game_name}</td>
                            <td><span style="color:${colorize(log.status)}">${log.status}</span></td>
                            <td>${log.message}</td>
                        </tr>
                    `;
                        $('#lasso-log-body').prepend(html);
                        lastLogId = Math.max(lastLogId, parseInt(log.id));
                    });
                }

                if (response.data.is_complete) {
                    isRefreshing = false;
                    $('#lasso-refresh-all').prop('disabled', false).text('Refresh All Lasso URLs');
                    $('#lasso-refresh-status').append('<p><strong>✅ Refresh completed!</strong></p>');
                } else {
                    setTimeout(pollLogs, 2000);
                }
            }).fail(() => setTimeout(pollLogs, 2000));
        }

        $('#lasso-refresh-all').on('click', function() {
            const $btn = $(this);
            if (isRefreshing) return;

            isRefreshing = true;
            $btn.prop('disabled', true).text('Refreshing...');
            $('#lasso-refresh-status').html('<p>Starting refresh...</p>');
            $('#lasso-log-body').html('<tr><td colspan="4">Waiting for logs...</td></tr>');

            $.post(ajaxurl, {
                action: 'lasso_refresh_all_urls'
            }, function(response) {
                $('#lasso-refresh-status').html('<p>' + response.data.message + '</p>');
                $('#lasso-log-body').empty();
                pollLogs();
            }).fail(() => {
                $('#lasso-refresh-status').html('<p style=\"color:red;\">❌ Error occurred while queuing jobs.</p>');
                $btn.prop('disabled', false).text('Refresh All Lasso URLs');
                isRefreshing = false;
            });
        });
    });
</script>