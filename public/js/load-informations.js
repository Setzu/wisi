$(function () {

    $('a#link-tab3').on('click', function () {
        $('.ajax-loader').show();

        $.ajax({
            method: 'POST',
            url: '/wisi/accueil/informations',
            data: {
                action: 'informations'
            },
            dataType: 'html',
            success: function (data) {
                $('div#tabs-3').html(data);
                $('.ajax-loader').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                // alert('error');
                // alert(xhr.status);
                // alert(xhr.responseText);
                // alert(thrownError);
                $('.ajax-loader').hide();
            }
        });
    });
});