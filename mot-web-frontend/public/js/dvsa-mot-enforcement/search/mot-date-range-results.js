/* Table initialisation */
$(document).ready(function() {
    var paramsDataTable = {
        'bPaginate': true,
        'bLengthChange': true,
        'bFilter': false,
        'bAutoWidth': true,
        'bSort': true,
        'aaSorting': [[1, 'desc']],
        'bInfo': true,
        'oLanguage': {'sSearch': 'Filter:'},
        'bServerSide': true,
        'sAjaxSource': URL_SEARCH_MOT_DATE_RANGE_AJAX,
        'sAjaxDataProp': 'data',
        'sServerMethod': 'POST',
        'aoColumns' : [
            { 'mData' : 'test_date'},
            { 'mData' : 'display_date'},
            { 'mData' : 'status'},
            { 'mData' : 'vin'},
            { 'mData' : 'registration'},
            { 'mData' : 'link'},
            { 'mData' : 'make'},
            { 'mData' : 'model'},
            { 'mData' : 'display_test_type'},
            { 'mData' : 'site_number'},
            { 'mData' : 'username'}
        ],
        'aoColumnDefs': [
            {
                'bSortable': false,
                'aTargets': [5],
                'render': function (data, type, row) {
                    return '<a ' + data.id + '" href="' + data.url + '">' + data.text + '</a>'
                }
            },
            {'sClass': 'truncate', 'aTargets': [7, 8]},
            {
                "bVisible": false,
                "aTargets": [0]
            },
            {
                "iDataSort": 0,
                "aTargets": [1]
            }
        ],
        'fnCreatedRow': function (nRow, aData) {
            $('td:eq(6)', nRow).attr('title', aData['model']);
            $('td:eq(7)', nRow).attr('title', aData['display_test_type']);
            $('td:eq(4) a', nRow).attr('href', $('td:eq(4) a', nRow).attr('href') + paramUrlSummaryPage);
        },
        'fnDrawCallback': function () {
            $('.info-popup').popover({
                "placement": "top",
                "html": true,
                "trigger": "hover"
            })
        },
        fnServerParams: function (data) {
            data.push({name: CSRF.paramName, value: CSRF.token});
        }
    };

    if (searchType == 'tester') {
        paramsDataTable.aoColumnDefs.push({ 'bVisible': false, 'aTargets': [10] });
    }

    var listMots = $('#listMOTs');
    listMots.dataTable(paramsDataTable);
    listMots.find("thead").attr("style","background-color:#DEE0E2;");
    $('#listMOTs_filter').find("input").addClass( "form-control");

});