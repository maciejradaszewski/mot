var VehicleSearchHelper = function() {
    var textBox = $('#vehicle-search'),
        formSearch = $('#vehicle-search-form'),
        selectedElement = document.getElementById('type').selectedIndex,
        dataPopover = $("[data-toggle=popover]");

    formSearch.validate({
        rules: {
            'search': {
                required: true
            }
        },
        errorClass: "inputError",
        errorContainer: $('#validation-summary-id'),
        errorLabelContainer: $('#validation-summary-id ol'),
        wrapper: 'li'
    });

    if (selectedElement == REGISTRATION) { // Registration = 0
        textBox.attr('placeholder', REGISTRATION_PLACEHOLDER);
    } else { // VIN/Chassis = 1
        textBox.attr('placeholder', VIN_PLACEHOLDER);
    }

    // If search entity changes - then change the placeholder text example..

    $('#type').on('change', function () {
        selectedElement = document.getElementById('type').selectedIndex;
        switch (selectedElement) {
            case 0: // Registration (VRM)
                textBox.attr('placeholder', REGISTRATION_PLACEHOLDER);
                textBox.unbind();
                break;
            case 1: // VIN/Chassis
                textBox.attr('placeholder', VIN_PLACEHOLDER);
                textBox.unbind();
                break;
            default:
                break;
        }
    });


    // Set up all popovers as clicks for touchscreens / hovers for laptops...
    var is_touch_device = 'ontouchstart' in document.documentElement;
    if (!is_touch_device) {  // If its not a touch-device - then use hovers...
        dataPopover.popover({ // Info here http://getbootstrap.com/javascript/#popovers-examples
            "placement": "top",      // Stick it where there's room
            "html": true,            // Enable html in popovers
            "trigger": "hover"       // Set up all popovers as hovers...
        });
    } else {    // Else - If it IS a touch-device use clicks (rather than hovers)...
        dataPopover.popover({ // More info here http://getbootstrap.com/javascript/#popovers-examples
            "placement": "top",      // Stick it where there's room
            "html": true,            // Enable html in popovers
            "trigger": "click"       // If a touchscreen device -  set up all popovers as clicks...
        });
    }

};
