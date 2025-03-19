jQuery(document).ready(function ($) {
    function fetchData(type) {
        $('#preloader').show();
        $('#result').html('');

        $.ajax({
            url: gdf_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'gdf_fetch_data',
                type: type,
                nonce: gdf_ajax.nonce
            },
            success: function (response) {
                $('#preloader').hide();
                if (response.success) {
                    $('#result').html('<p style="color:green;">' + response.data + '</p>');
                } else {
                    $('#result').html('<p style="color:red;">Lỗi: ' + response.data + '</p>');
                }
            },
            error: function () {
                $('#preloader').hide();
                $('#result').html('<p style="color:red;">Đã xảy ra lỗi khi kết nối.</p>');
            }
        });
    }

    $('#fetch-developers').click(function () {
        fetchData('developers');
    });

    $('#fetch-publishers').click(function () {
        fetchData('publishers');
    });
});