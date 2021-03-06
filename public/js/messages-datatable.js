$(function() {
    function format(d) {
        return $(d[6]).show();
    }

    var messages = $('table#messages').DataTable({
        "order": [[0, "asc"]],
        // Définir ici autant de lignes que le nombre de <th> du tableau
        "columns": [
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ],
        // "sDom": 'lrtip',
        // "bLengthChange": false,
        "iDisplayLength": 10,
        "language": {
            "paginate": {
                "previous": 'Précédent',
                "next": 'Suivant'
            },
            "search": 'Recherche par mots clés',
            "lengthMenu": 'Afficher _MENU_ messages par page',
            "sZeroRecords": 'Aucun message en attente de réponse',
            "sInfoEmpty": '0 message en attente de réponse',
            "sInfoFiltered": '',
            "emptyTable": '',
            "info": '_TOTAL_ messages au total'
        }
    });

    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('table#messages tbody').on('click', 'td.detail', function() {
        var tr = $(this).closest('tr');
        var row = messages.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        } else {
            tr.addClass('details');
            row.child(format(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });

    // On each draw, loop over the detailRows array and show any child rows
    messages.on('draw', function() {
        $.each(detailRows, function(i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
});