PARTIAL_REINSPECTION = 2;

    $(document).ready(function() {
        var fullPartialRetest = $('#fullPartialRetest');

        if (fullPartialRetest.val() != PARTIAL_REINSPECTION) {
            $('#partialReasonsRow').hide();
            $('#partialitemsMissedRow').hide();
            $("#partialNotify").hide();
        }

        fullPartialRetest.on('change', function () {

            if ($(this).val() == 2) {
                $('#partialReasonsRow').fadeIn();
                // Don't apply the autosize until the textarea is visible - otherwise
                // we get the wrong size.
                $('#partialReasons').attr("required","required").autosize();
                $("#partialNotify").show().delay(9000).fadeOut('slow');

                $('#partialitemsMissedRow').fadeIn();
                $('#partialItemsMissed').attr("required","required").autosize();
            } else {
                $('#partialReasonsRow').fadeOut();
                $('#partialReasons').attr("required",false);
                $("#partialNotify").hide();

                $('#partialitemsMissedRow').fadeOut();
                $('#partialItemsMissed').attr("required",false);
            }
        });

        /**
         * VM-2556
         *
         * Disable location input box if equivalent
         * box contains text
         */
        var motLocationEnforcement = function() {
            var siteidentry = $('#siteidentry'),
                location = $("#location");

            if (siteidentry.val().trim().length) {
                location.prop('disabled', true);
            }

            siteidentry.on('keyup', function () {
                if ($(this).val().length > 0) {
                    location.prop('disabled', true);
                } else {
                    location.prop('disabled', false);
                }
            });
            location.on('keyup', function () {
                if ($(this).val().length > 0) {
                    siteidentry.prop('disabled', true);
                    $('#siteid').val('');
                } else {
                    siteidentry.prop('disabled', false);
                }
            });
        };
        new motLocationEnforcement();

        /**
         * VM-1825 - Validate One Person Test Fields
         */
        if($('#onePersonTest').length){
            $('#submit-test-results').validate({
                debug: false,
                errorClass: 'inputError',
                messages: {
                    'partialReasons': 'Please enter reasons why this is a partial retest',
                    'partialItemsMissed': 'Please outline the items not retested', 
                    'onePersonTest': 'Please indicate if a One Person test has been performed',
                    'onePersonReInspection': 'Please indicate if a One Person reinspection has been performed'
                    
                },

                rules: {
                    'partialReasons': 'required',
                    'partialItemsMissed': 'required',
                    'onePersonTest': 'required',
                    'onePersonReInspection': 'required'
                },

                errorContainer: '#validationBox',
                errorLabelContainer: '#validationErrors ol',
                wrapper: 'li'
            });
        }
    });
