$(document).ready(function () {
    var $eForm = $("#customDateSearch");

    //  --  validation  --
    $eForm.validate({
        errorClass: "inputError",
        errorContainer: '#validation-summary-id',
        errorLabelContainer: '#validation-summary-id ol',
        wrapper: 'li'
    });

    DateGroup.validateDate($eForm, 'dateFrom', 'From');
    DateGroup.validateDate($eForm, 'dateTo', 'To');

    //  --  set focus on first element in form  --
    $eForm.find('input[type="text"]:first').focus();
});
