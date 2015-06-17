$(function() {
    if (OrganisationEdit.isCorrAddressTheSame) {
        $('#addressCorrespondence').hide();
    }

    var $eIsEmailSupply = $('input[name="correspondenceEmailSupply"]'),
        $eCorrEmail = $('#correspondenceEmail'),
        $eCorrEmailConf = $('#correspondenceEmailConfirmation');

    EmailUtils.cleanIfNotSupply($eCorrEmail, $eCorrEmailConf, $eIsEmailSupply);
});
