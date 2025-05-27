<div class="wrap">
    <h1>Lasso Debug Logs</h1>
    <p>Log realtime cho từng bước trong quá trình refresh từng game.</p>
    <table class="widefat fixed striped" id="debug-log-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Context</th>
                <th>Log</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    jQuery(document).ready(function($) {
        function loadLogs() {
            $.post(ajaxurl, {
                action: 'lasso_get_debug_logs'
            }, function(response) {
                if (!response.success) return;

                const logs = response.data.logs;
                const $tbody = $('#debug-log-table tbody').empty();

                logs.forEach(log => {
                    $tbody.append(`
                    <tr>
                        <td>${log.timestamp}</td>
                        <td><code>${log.context}</code></td>
                        <td style="white-space: pre-wrap;">${log.log}</td>
                    </tr>
                `);
                });
            });
        }

        loadLogs();
        setInterval(loadLogs, 5000);
    });
</script>