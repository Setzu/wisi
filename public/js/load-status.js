$(function() {
    $.ajax({
        method: 'POST',
        url: '/apps/wisi/public/index/status',
        data: {
            action: 'messages'
        },
        dataType: "json",
        success: function (data) {

            var _dataChart = [];

            $.each(data, function(index, element) {
                $.each(element.status, function(k, v) {
                    _dataChart.push({
                        type: "column",
                        showInLegend: true,
                        name: element.SYSNAME,
                        legendText: element.SYSNAME,
                        dataPoints: [
                            {x: 1, y: parseInt(v.ISRCRUSON), label: "Connected users"},
                            {x: 2, y: parseInt(v.BATJBSWMSG), label: "Messages wait"},
                            {x: 3, y: parseInt(v.BATJOBSRUN), label: "Running"},
                            {x: 4, y: parseInt(v.BATJBSHRUN), label: "Hold"},
                            {x: 5, y: parseInt(v.BATJHLDJBQ), label: "Hold JobQ"},
                            {x: 6, y: parseInt(v.BATJBQHLD), label: "On JobQ hold"},
                            {x: 7, y: parseInt(v.BATJBUNAJQ), label: "Unassigned JobQ"},
                            {x: 8, y: parseInt(v.BATENDWPRT) / 1000, label: "Ended OutQ"}
                        ]
                    });
                });
            });

            var chart = new CanvasJS.Chart("chartContainer",
                {
                    title:{
                        text: 'Etat des systèmes'
                    },
                    data: _dataChart,
                    legend: {
                        fontSize: 20
                    }
                }
            );

            chart.render();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            // alert('error');
            // alert(xhr.status);
            // alert(xhr.responseText);
            // alert(thrownError);
        }
    });
});
