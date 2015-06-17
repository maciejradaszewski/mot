var SiteAssessment = {};

(function () {
    SiteAssessment.ASCII_0 = 48;
    SiteAssessment.ASCII_9 = 57;

    /**
     * Format the score box to 1 decimal place when called.
     */
    SiteAssessment.saFormatScore = function () {
        var score = $(this).val();
        if (score.length) {
            if ($(this).valid()) {
                $(this).val(parseFloat(score).toFixed(1));
            }
        }
    };

    /**
     * Ensure DD/MM fields are zero padded.
     */
    SiteAssessment.saPad2 = function () {
        var value = $(this).val();
        if (1 == value.length) {
            $(this).val('0' + value);
        }
    };


    /**
     * If a visit outcome of of satisfactory or shortcomings found has
     * been selected then the user has to enter a site assessment score.
     */
    SiteAssessment.saOutcomeChanged = function () {
        var siteScore = $('#site-score');
        var testerId = $('#tester-id');
        var option = parseInt($(this).val());

        switch (option) {
            case 1:
            case 2:
                siteScore.rules('add', 'required');
                testerId.rules('add', 'required');
                break;

            case 3:
            default:
                siteScore.val('');
                siteScore.rules('remove', 'required');
                testerId.rules('remove', 'required');
                break;
        }

        siteScore.valid();
        $('#visit-outcome').valid();
    };


    /**
     * If the representative name has data then we must make it such
     * that the representative ID is no longer required.
     */
    SiteAssessment.saCheckAeIdNamePair = function () {
        var aeRepName = $('#ae-rep-name');
        var aeRepId = $('#ae-rep-id');

        if (0 == aeRepName.val().length) {
            aeRepId.rules('add', 'required');
        }
        else {
            aeRepId.rules('remove', 'required');
        }
        aeRepId.valid();
        aeRepName.valid();
    };


    /**
     * Ensure the number of days is valid for month/year tuple.
     *
     * @param val
     * @param elem
     * @returns {boolean}
     */
    SiteAssessment.saValidateDayBox = function (val, elem) {

        var isOk = true;
        var dVal = parseInt($('#day').val());
        var mVal = parseInt($('#month').val());
        var yVal = parseInt($('#year').val());

        if (isNaN(dVal) || isNaN(mVal) || isNaN(yVal)) {
            return true;
        }

        mVal--;

        var uiMoment = moment([yVal, mVal, dVal]);

        if (!uiMoment.isValid()) {
            isOk = false;
        }
        return isOk;
    };

    SiteAssessment.saWipeServer = function () {
        $('#validation-summary-id').hide();
        return true;
    };

    /**
     * Check if the DMY fields are in the future.
     *
     * @param val
     * @param elem
     * @returns {*}
     */
    SiteAssessment.saIsFutureDate = function (val, elem) {

        var dVal = parseInt($('#day').val());
        var mVal = parseInt($('#month').val());
        var yVal = parseInt($('#year').val());

        if (isNaN(dVal) || isNaN(mVal) || isNaN(yVal)) {
            return true;
        }

        var today = new Date();
        var userDate = new Date(yVal, mVal - 1, dVal);

        return userDate <= today;
    }
})();

$(document).ready(function () {
    // Days must be within the range for the month and leap years
    jQuery.validator.addMethod('checkDayMonth', SiteAssessment.saValidateDayBox);
    jQuery.validator.addMethod('noFutureDate', SiteAssessment.saIsFutureDate);
    jQuery.validator.addMethod('resetServer', SiteAssessment.saWipeServer);

    var $eAERepName = $('#ae-rep-name'),
        $eVisitOutcome = $('#visit-outcome'),
        $eSiteScore = $('#site-score');

    // Check for pre-populated fields. If the AE/name is non-empty then
    // we can relax the required status of the AE/rep-id!
    var aeRepIdRequired = true;
    if ($eAERepName.val().length) {
        aeRepIdRequired = false;
    }

    // For an outcome of abandoned we don't need the tester ID value
    var testerIdRequired = true;
    if ($('#tester-id').val().length) {
        testerIdRequired = false;
    }

    //Install jQuery validation...
    $('#ve-site-assessment').validate({
        errorClass: 'inputError',
        messages: {
            'vts-search': 'A VTS site number and name is required',
            'site-score': {
                required: 'The site score is required',
                number: 'A valid site score is required',
                max: 'A site score cannot be higher than 1477.6',
                min: 'A site score must be greater than zero'
            },
            'day': {
                required: 'The day is required',
                number: 'The day must be a number in the range 1-31 and valid for the month value / year',
                checkDayMonth: 'Day is invalid for the month/year combination',
                noFutureDate: 'Dates cannot be in the future'
            },
            'month': {
                required: 'The month is required',
                number: 'Month must be in the range 1 - 12',
                noFutureDate: 'Dates cannot be in the future'
            },
            'year': {
                required: 'The year is required, as YYYY',
                number: 'The year must be a number from 1970 to the current year as YYYY',
                noFutureDate: 'Dates cannot be in the future',
                checkDayMonth: 'Day is invalid for the month/year combination',
                min: 'Please enter a valid year'
            },
            'ae-rep-id': 'AE/representative ID is required',
            'ae-rep-name': 'AE/representative name is required',
            'ae-rep-pos': 'AE/representative position is required',
            'tester-id': 'The Tester ID is required',
            'visit-outcome': 'The visit outcome is required'
        },

        rules: {
            'vts-search': {resetServer: true, required: true},
            'site-score': {
                resetServer: true,
                required: true,
                number: true,
                min: SiteAssessment.RISK_SCORE_MIN,
                max: SiteAssessment.RISK_SCORE_MAX
            },
            'day': {resetServer: true, required: true, number: true, min: 1, max: 31, checkDayMonth: true},
            'month': {resetServer: true, required: true, number: true, min: 1, max: 12},
            'year': {
                resetServer: true,
                required: true,
                number: true,
                min: 1900,
                max: new Date().getFullYear(),
                checkDayMonth: true,
                noFutureDate: true
            },
            'visit-outcome': 'required',
            'tester-id': {resetServer: true, required: testerIdRequired},
            'ae-rep-id': {resetServer: true, required: aeRepIdRequired},
            'ae-rep-pos': 'required'
        },

        errorContainer: '#validationBox',
        errorLabelContainer: '#validationErrors ol',
        wrapper: 'li'
    });

    // Install UI update / checking behaviours...
    $eVisitOutcome.change(SiteAssessment.saOutcomeChanged);
    $eSiteScore.blur(SiteAssessment.saFormatScore);

    // Install auto-focus behaviours
    $('#day')
        .blur(SiteAssessment.saPad2)
        .keyup(function (event) {
            if (event.which >= SiteAssessment.ASCII_0 && event.which <= SiteAssessment.ASCII_9) {
                if ($(this).val().length == 2) {
                    $('#month').focus();
                }
            }
        }
    );
    $('#month')
        .blur(SiteAssessment.saPad2)
        .keyup(function (event) {
            if (event.which >= SiteAssessment.ASCII_0 && event.which <= SiteAssessment.ASCII_9) {
                if ($(this).val().length == 2) {
                    $('#year').focus();
                }
            }
        }
    );
    $eAERepName.blur(SiteAssessment.saCheckAeIdNamePair);


    // At least one character in the AE/rep-id disables AE/rep-name
    $('#ae-rep-id').keyup(function () {
        checkDisabled();
    });

    // Disable site core input if abandoned
    $eVisitOutcome.on('change.select', function () {
        checkDisabled();
    });

    function checkDisabled() {
        if ($eVisitOutcome.val() == 3) {
            $eSiteScore
                .attr('disabled', true)
                .css('background-color', '#EBEBE4');
        } else {
            $eSiteScore
                .attr('disabled', false)
                .css('background-color', '#FFF');
        }

        if ($('#ae-rep-id').val().length) {
            $eAERepName
                .prop('disabled', true)
                .val('');
        }
        else {
            $eAERepName.prop('disabled', false);
        }
    }

    // On page load check disabled fields
    checkDisabled();
});
