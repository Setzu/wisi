$(function () {

    function _ajax() {
        $('.ajax-loader').show();

        $.ajax({
            async: true,
            method: 'POST',
            url: '/apps/wisi/public/index/messages',
            data: {
                action: 'messages'
            },
            dataType: "html",
            success: function (data) {
                $('div#tabs-1').html(data);
                $('.ajax-loader').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('error');
                //alert(xhr.status);
                //alert(xhr.responseText);
                //alert(thrownError);
                $('#ajax-loader').hide();
            }
        });
    }

    _ajax();

    $('a#link-tab1').on('click', function () {
        _ajax();
    });
});