$(function() {

    $('#jobs').DataTable({
        "order": [[ 0, "asc" ]],
        // Définir ici autant de lignes que le nombre de <th> du tableau
        "columns": [
            null,
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
        "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "Tous"]],
        "iDisplayLength": 15,
        "language": {
            "paginate": {
                "previous": 'Précédent',
                "next": 'Suivant'
            },
            "search": 'Recherche par mots clés',
            "lengthMenu": 'Afficher _MENU_ jobs par page',
            "sZeroRecords": 'Aucun job n\'a été trouvé',
            "sInfoEmpty": '0 message en attente de réponse',
            "sInfoFiltered": '',
            "emptyTable": '',
            "info": '_TOTAL_ jobs au total'
        }
    });
});