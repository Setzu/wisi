$(function() {
    $.ajax({
        method: 'POST',
        url: '/wisi/accueil/status',
        data: {
            action: 'messages'
        },
        dataType: "json",
        success: function (data) {
            var ctx = document.getElementById("myChart").getContext('2d');
            var _datasets = [];
            $.each(data, function(index, element) {
                $.each(element.status, function(k, v) {
                    _datasets.push({
                        label: element.SYSNAME,
                        data: [
                            parseInt(v.ISRCRUSON),
                            parseInt(v.BATJOBSRUN),
                            parseInt(v.BATJHLDJBQ),
                            parseInt(v.BATJBQHLD),
                            parseInt(v.BATJBUNAJQ),
                            parseInt(v.BATENDWPRT) / 1000
                        ],
                        backgroundColor: [
                            element.COLOR,
                            element.COLOR,
                            element.COLOR,
                            element.COLOR,
                            element.COLOR,
                            element.COLOR
                        ],
                        borderColor: [
                            element.BORDER,
                            element.BORDER,
                            element.BORDER,
                            element.BORDER,
                            element.BORDER,
                            element.BORDER
                        ],
                        borderWidth: 1
                    });
                });
            });

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Connected", "Running", "Hold JobQ", "On Hold JobQ", "Unass. JobQ", "Ended OutQ"],
                    datasets: _datasets
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            // alert('error');
            // alert(xhr.status);
            // alert(xhr.responseText);
            // alert(thrownError);
        }
    });
});
