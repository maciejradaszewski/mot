/* Table initialisation */
$(document).ready(function() {
    var paramsDataTable = {
        'bPaginate': true,
        'bLengthChange': true,
        'bFilter': true,
        'bAutoWidth': true,
        'bSort': true,
        'aaSorting': [[ 1, 'asc' ]],
        'bInfo': true,
        'oLanguage': {'sSearch':'Filter:'},
        'bServerSide': true,
        'sAjaxSource': URL_DATATABLES_AJAX,
        'sAjaxDataProp': 'data',
        'sServerMethod': 'POST',
        'aoColumnDefs': [
            { 'bSortable': false, 'sClass': 'truncate', 'aTargets': [2] },
            { 'bSortable': false, 'aTargets': [5] },
            { 'sClass': 'truncate', 'aTargets': [3, 4, 7] },
        ],
        'fnCreatedRow': function( nRow, aData) {
            var url = URL_DETAILS_PAGE.substr(0, URL_DETAILS_PAGE.length - 1) + escape(aData[0]);

            $('td:eq(0)', nRow).html( '<a href="' + url + '?q=' + escape(vtsSearch) + '"/>');
            $('td:eq(0) a', nRow).text(aData[0]);

            $('td:eq(1)', nRow).html( '<a href="' + url + '?q=' + escape(vtsSearch) + '"/>');
            $('td:eq(1) a', nRow).text(aData[1]);

            $('td:eq(2)', nRow).attr('title', aData[2]);
            $('td:eq(2)', nRow).css('max-width', '15em');

            $('td:eq(1) a', nRow).attr('title', aData[1]);
            $('td:eq(1) a', nRow).attr('class', 'truncate');
            $('td:eq(1) a', nRow).css('max-width', '10em');

            $('td:eq(3)', nRow).attr('title', aData[3]);
            $('td:eq(7)', nRow).attr('title', aData[7]);

        },
        fnServerParams: function ( data ) {
            data.push({ name : CSRF.paramName, value : CSRF.token });
        }
    };
    var listVTS = $('#listVTS');
    listVTS.dataTable(paramsDataTable);
    listVTS.find("thead").attr("style","background-color:#DEE0E2;");
    $('#listVTS_filter').find("input").addClass( "form-control" );
});