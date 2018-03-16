$(function() {
    $('#system-alias, #system-user, #system-password, #system-type').keyup(function() {
        this.value = this.value.toUpperCase();
    });
});