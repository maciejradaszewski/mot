$(document).ready(function () {
    var
        $eListAE = $('#listAe'),
        oTable = $eListAE.dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "aaSorting": [
                [ 0, "asc" ]
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
                    "aTargets": [7]
                },
                {
                    "sClass": "longer-truncate",
                    "aTargets": [2]
                }
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(2)', nRow).attr('title', aData[2]);
                $('td:eq(7)', nRow).attr('title', aData[7]);
            }
        });

    $('#listAe_filter').find("input").addClass("form-control");
    $eListAE.find("thead").attr("style", "background-color:#DEE0E2;");
});

