$(document).ready(function() {
    $('textarea').autosize();   // Set up all textareas to auto-resize automatically

    // Initial state - hide all but the first DAR Interviewer details row...
    // ...later on, do this only if not pre-populated (from memcache or wherever)
    $('#interviewRow2').hide();
    $('#interviewRow3').hide();
    $('#interviewRow4').hide();
    $('#interviewRow5').hide();

    // Add extra DAR Interview Rows as required (up to a maximum of 5)
    $('.fa-plus-square').on('click', function() {
        $(this).closest('.row').next().show('slow');
        $(this).hide('slow');        // Hide the '+' icon just clicked
        $(this).next().hide('slow'); // Hide the neighbouring '-' icon
    });

    $('.fa-minus-square').on('click', function() {
        // Clear the row's input boxes, prior to hiding
        $(this).closest('.row').find('input').val(''); 
        $(this).closest('.row').hide('slow');
        // Don't forget to show the +/- icons in row above
        $(this).closest('.row').prev().find('.fa-plus-square, .fa-minus-square').show('slow');
    });

    $('#reinspection-form').validate({
        debug: false,
        errorClass: 'inputError',
        messages: {
            'concludingRemarks': 'Please enter your concluding remarks',
            'reinspection-outcome': 'Please enter a valid reinspection outcome'
        },
        rules: {
            'concludingRemarks': {required: true},
            'reinspection-outcome': {required: true}
        },
        errorContainer: '#validationBox',
        errorLabelContainer: '#validationErrors ol',
        wrapper: 'li'
    });
});
