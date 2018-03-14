$(function () {

    $('a#link-tab2').on('click', function () {
        $('.ajax-loader').show();

        $.ajax({
            method: 'POST',
            url: '/wisi/accueil/jobs',
            data: {
                action: 'jobs'
            },
            dataType: 'html',
            success: function (data) {
                $('div#tabs-2').html(data);
                $('.ajax-loader').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('error');
                //alert(xhr.status);
                //alert(xhr.responseText);
                //alert(thrownError);
                $('.ajax-loader').hide();
            }
        });
    });
});