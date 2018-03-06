$(function() {
    $('div#color').css('background-color', '#FFFFFF');

    $('#system-color').keyup(function() {
        this.value = this.value.toUpperCase();
        $('div#color').css('background-color', '#' + $('#system-color').val());
    });
    $('#system-alias, #system-user, #system-password, #system-type').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});