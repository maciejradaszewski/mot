var EventListHelper = function(isShowDate, ajaxUrl) {

    var eListLs = $('#listLogs'),
        oTable = eListLs.dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "aaSorting": [
                [ 1, "desc" ]
            ],
            "bInfo": true,
            "bAutoWidth": true,
            "oLanguage": {
                "sSearch": "Filter:",
                "sLengthMenu": "Show _MENU_ events per page",
                "sInfo": "_START_ to _END_ events showing from a total of _TOTAL_ ",
                "sInfoFiltered": "(filtered from _MAX_ events)",
                "sInfoEmpty": "No events to show"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "dom": '<"top"if>rt<"bottom"lp><"clear">',
            'aoColumns' : [
                { 'mData' : 'type'},
                { 'mData' : 'date'},
                { 'mData' : 'description'}
            ],
            "aoColumnDefs": [
                {
                    "sClass": "longer-truncate",
                    "aTargets": [2]
                },
                {
                    'aTargets': [0],
                    'render': function (data) {
                        return '<a href="' + data.url + '">' + data.type + '</a>'
                    }
                }
            ],
            "sAjaxDataProp": 'data',
            "sServerMethod": 'POST',
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": false,
            "sAjaxSource": ajaxUrl,
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                aoData.push({"name" :"_csrf_token",     "value" :$('input[name="_csrf_token"]').val()});
                aoData.push({"name" :"search",          "value" :$('#search').val()});
                aoData.push({"name" :"isShowDate",      "value" :$('#isShowDate').val()});
                aoData.push({"name" :"dateFrom[Day]",   "value" :$('#dateFrom-Day').val()});
                aoData.push({"name" :"dateFrom[Month]", "value" :$('#dateFrom-Month').val()});
                aoData.push({"name" :"dateFrom[Year]",  "value" :$('#dateFrom-Year').val()});
                aoData.push({"name" :"dateTo[Day]",     "value" :$('#dateTo-Day').val()});
                aoData.push({"name" :"dateTo[Month]",   "value" :$('#dateTo-Month').val()});
                aoData.push({"name" :"dateTo[Year]",    "value" :$('#dateTo-Year').val()});
                $.getJSON( sSource, aoData, function (json) {
                    /* Do whatever additional processing you want on the callback, then tell DataTables */
                    fnCallback(json);
                });
            }
        });

    eListLs.find("thead").attr("style", "background-color:#DEE0E2;");

    // Hide the datatables filter in the default position - with Javascript enabled, we'll feed
    // it with another one called '#searchbox' so that we can position it where we want.
    $('.dataTables_filter').hide();

    // Feed the hidden dataTables search/filter box from a custom one (left-hand side).
    $("#searchbox").keyup(function() {
        oTable.fnFilter(this.value);
    });

    // Hide the dataTables page-length selecter dropdown - and feed it with
    // a horizontal link list (to look more like the Slot Usage screen)
    var dataTables_length = $('.dataTables_length'),
        length_selector = dataTables_length.find('select'),
        length_selector_label = dataTables_length.find('label');

    $(length_selector_label).hide();
    $('.page-results-control').find('a').click(function() {
        $(length_selector).val(this.text);
        $(length_selector).trigger('change');
        // Then get the current table page length, and do something like this:
        var currentPageLength = $(length_selector).find(":selected").text();
        // first reset all apparently disabled links then make one of links look like its disabled
        $('.page-results-control').find('a').removeClass("item-link-disabled");
        //var selectorPageLength = "a#length"+currentPageLength;
        $('a#length'+currentPageLength).addClass("item-link-disabled");
    });

    var dateRangeFields = $('#dateRangeFields'),
        isShowDateInput = $('#isShowDate'),
        allEvents       = $('#allEvents'),
        searchInput     = $('#search');

    if (!isShowDate) {
        dateRangeFields.hide();
        // Set the selected Date Range Nav Filter (LHS) to be bold and not look like a link
        allEvents.addClass("item-bold-link-disabled");
    }
    $('#customRange').click(function() {
        dateRangeFields.show('slow');
        isShowDateInput.val(1);
        //allEvents.removeClass("item-bold-link-disabled");
    });

    // Handle all event click
    allEvents.click(function() {
        if ($(this).hasClass('item-bold-link-disabled')) {
            return false;
        }
        if (searchInput.val().length !== 0) {
            $(this).attr('href', $(this).attr('href') + '?search=' + searchInput.val());
        }
        return true;
    });

    // Then get the current table page length, and make one of links look like its disabled
    var currentPageLength = $(length_selector).find(":selected").text();
    $('a#length'+currentPageLength).addClass("item-link-disabled");

};
