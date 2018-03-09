$(function () {

    /**
     * @return {boolean}
     */
    $('#delete-system').click(function () {
        var _confirm = confirm("Etes-vous sur de vouloir supprimer le système " + $('#select-update').val() + " ?");

        if (_confirm == true) {
            $.ajax({
                method: 'POST',
                url: '/system/delete',
                data: {
                    nmsys: $('#select-update').val()
                },
                dataType: 'html',
                success: function (data) {
                    if (data === 'false') {
                        document.location.href = "/system/update";
                    } else {
                        document.location.href = "/index";
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert('error');
                    //alert(xhr.status);
                    //alert(xhr.responseText);
                    //alert(thrownError);
                }
            });
        }
    });
});
