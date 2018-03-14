$(function() {

    var _select = $('#select-update');

    var loadSystemInfo = function(value) {
        $.ajax({
            method: 'POST',
            url: '/wisi/system/loadInfos',
            data: {
                select: value
            },
            dataType: 'json',
            success: function (data) {
                $('#system-alias').val(data.SYSNAME);
                $('#system-type').val(data.SYSTEMTYP);
                $('#system-priority').val(data.SYSPTY);
                $('#system-color').val(data.COLOR);
                $('div#color').css('background-color', '#' + $('#system-color').val());
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert('error');
                //alert(xhr.status);
                //alert(xhr.responseText);
                //alert(thrownError);
            }
        });
    };

    _select.change(function () {
        loadSystemInfo($(this).val());
    });

    loadSystemInfo(_select.val());
});