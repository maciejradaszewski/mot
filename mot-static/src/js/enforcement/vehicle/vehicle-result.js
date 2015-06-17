var VehicleResultHelper = function() {
    var $eListVs = $('#listVehicles');

    $eListVs.dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "aaSorting": [
            [ 6, "desc" ]
        ],
        "bInfo": true,
        "bAutoWidth": true,
        "oLanguage": {"sSearch": "Filter:"},
        "bProcessing": false,
        "bServerSide": false,
        "bDeferRender": true,
        "aoColumnDefs": [
            {
                "sClass": "truncate",
                "aTargets": [4,5]
            }
        ]
    });

    $('#listVehicles_filter').find("input").addClass("form-control");
    $eListVs.find("thead").attr("style", "background-color:#DEE0E2;");
};
