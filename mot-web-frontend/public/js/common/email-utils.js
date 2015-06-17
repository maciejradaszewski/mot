
var EmailUtils = (function() {
    'use strict';

    var cleanIfNotSupply = function ($eEmail, $eEmailConf, $eIsSupply) {
        //  --  clean email and email confirm if user select 'not supply email' --
        $eIsSupply.change(function() {
            if ($(this).is(':checked')) {
                cleanUpEmailField($eEmail);
                cleanUpEmailField($eEmailConf);
            }
        });

        function cleanUpEmailField($elm) {
            $elm
                //  --  clean field value   --
                .val('')
                //  --  get block element   --
                .parent('div.form-group')
                    //  --  remove error class   --
                    .removeClass('has-error')
                    //  --  remove error message element    --
                    .find('label span.validation-message').remove();
        }

        //  --  unset 'not supply email' when user start typing email   --
        function unsetIsSupplyEmail() {
            $eIsSupply.is(':checked')
            && $eIsSupply.prop('checked', false)
                .parent().removeClass('selected');
        }

        $eEmail.bind({
            keypress: unsetIsSupplyEmail,
            change:  unsetIsSupplyEmail
        });

        $eEmailConf.bind({
            keypress: unsetIsSupplyEmail,
            change:  unsetIsSupplyEmail
        });
    };

    return {
        cleanIfNotSupply: cleanIfNotSupply
    };
}());
