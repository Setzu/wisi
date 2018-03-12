$(function () {

    var _connectSystemInputs = $('#system-bdd, #system-host, #system-user, #system-password');

    $('#test-connection').on('click', function () {
        $('#connect-error').html();

        $.ajax({
            method: 'POST',
            data: {
                ajax: true,
                bdd: $('#system-bdd').val(),
                host: $('#system-host').val(),
                user: $('#system-user').val(),
                password: $('#system-password').val()
            },
            url: '/system/testConnection',
            dataType: 'html',
            success: function (data) {
                if (data === 'success') {
                    $('#system-con').removeClass('glyphicon-refresh').addClass('glyphicon-ok');
                    $('#connect-error').html("<h4 class='alert-success' style='border: 2px solid #d6e9c6;'>Connexion réussie.</h4>");
                    $('#test-connection').prop('disabled', true).removeClass('btn-default').addClass('btn-success');
                    $('button#add-system').prop('disabled', false);
                    _connectSystemInputs.prop('readonly', true);
                    _connectSystemInputs.focus(function () {
                        $(this).blur();
                    })
                } else {
                    $('#system-con').removeClass('glyphicon-refresh').addClass('glyphicon-remove');
                    $('#connect-error').html("<h4 class='alert-danger' style='border: 2px solid #ebccd1;'>La connexion au système a échouée. Vérifiez les informations de connexion.</h4>");
                    $('#test-connection').prop('disabled', true).removeClass('btn-default').addClass('btn-danger');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                // alert('error');
                // alert(xhr.status);
                // alert(xhr.responseText);
                // alert(thrownError);
            }
        });
    });

    _connectSystemInputs.keyup(function() {
        $('#system-con').removeClass('glyphicon-ok glyphicon-remove').addClass('glyphicon-refresh');
        $('#test-connection').prop('disabled', false).removeClass('btn-danger').addClass('btn-default');
        $('#connect-error').html("");
        $('button#add-system').prop('disabled', 'disabled');
    });

});