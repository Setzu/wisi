$(function() {
    $("#subsystem, #jobname, #jobuser").keyup(function () {
        this.value = this.value.toUpperCase();
    })
});