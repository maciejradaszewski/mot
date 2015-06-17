var MotTestSeachByVehicle = {};

$(document).ready(function () {
    $('.show-hide-container').addClass('collapse');

    var oTable = $('#listMOTs').dataTable({				// Usage info here: http://datatables.net/release-datatables/examples/basic_init/filter_only.html
        "bPaginate": true,					            // Disable pagination
        "bLengthChange": true,			                // Disable ability to change the number of rows
        "bFilter": true,						        // Enable filtering
        "sDom": "<'row'<'col-lg-12'f><'col-lg-12'l>r>t<'row'<'col-lg-12'i><'col-lg-12'p>>",
        "bSort": true,							        // Enable sorting
        "aaSorting": [
            [1, "desc"]
    ],                                                  // Sort descending by the 1st column (date/time)
        "bInfo": true,							        // Suppress the "Showing x to N of N entries" info-footer
        "bAutoWidth": true,
        "oLanguage": {"sSearch": "Filter:"},            // Rename the Search label
        "bProcessing": false,
        "bServerSide": false,
        "bDeferRender": true,
        "aoColumnDefs": [
            {
                "sClass": "truncate",
                "aTargets": [8, 9]
            },
            {
                "bVisible": false, "aTargets": [0]
            },
            {
                "iDataSort": 0, "aTargets": [1]
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td:eq(7)', nRow).attr('title', aData[8]);
            $('td:eq(8)', nRow).attr('title', aData[9]);
        }
    });

    $('#listMOTs_filter').find("input").addClass("form-control");

    var formCompare = $('#compareTests'),
        btnExpand = $('.btn-expand'),
        motTestNumber = $('#motTestNumber'),
        motTestNumberToCompare = $('#motTestNumberToCompare');

    btnExpand.click(function () {
        $(this).find('.chevron_toggleable').toggleClass('fa-chevron-up fa-chevron-down');
    });

    if (MotTestSeachByVehicle.isEnableExpandBtn) {
        btnExpand.click();
    }

    // Click Test Number to add primary and secondary Tests to Comp1 & Comp2 fields...
    $('#listMOTs_wrapper').on('click', '.compare', function () {
        var vin = $(this).data('vin');
        if (btnExpand.find('.chevron_toggleable').hasClass('fa-chevron-down')) {
            btnExpand.click();
        }

        if (motTestNumber.val().trim() == "") {
            if (motTestNumberToCompare.val().trim() == ""
                || motTestNumberToCompare.val().trim() != $(this).data('testNumber')) {
                motTestNumber.val($(this).data('testNumber'));
                motTestNumber.data('vin', vin);
            }
        } else {
            if (motTestNumber.val().trim() != $(this).data('testNumber')) {
                motTestNumberToCompare.val($(this).data('testNumber'));
                motTestNumberToCompare.data('vin', vin);
            }
        }

        return false;
    });

    // Clicking Swap symbol swaps primary and secondary Comp1 & Comp2 fields...
    $('#swap').on('click', function () {
        var tempStore = motTestNumber.val();
        motTestNumber.val(motTestNumberToCompare.val());
        motTestNumberToCompare.val(tempStore);
    });

    function motTestCantMatch(val, elem) {
        return motTestNumber.val() != motTestNumberToCompare.val();
    }

    jQuery.validator.addMethod('motTestCantMatch', motTestCantMatch);

    var errorContainer = '#validationBox',
        errorLabelContainer = '#validationBox ol';

    if ($('#validation-summary-id').length) {
        errorContainer = '#validation-summary-id';
        errorLabelContainer = '#validation-summary-id ol';
    }

    formCompare.validate({
        errorClass: 'inputError',
        messages: {
            'motTestNumber': {
                required: 'A VE\'s Test Number is required',
                motTestCantMatch: 'The Mot test number must be different'
            },
            'motTestNumberToCompare': 'a Tester\'s Test Number is required'
        },
        rules: {
            'motTestNumber': {required: true, motTestCantMatch: true},
            'motTestNumberToCompare': {required: true}
        },
        errorContainer: errorContainer,
        errorLabelContainer: errorLabelContainer,
        wrapper: 'li'
    });
});