$(document).ready(function() {
    $('textarea').autosize();   // Set up all textareas to auto-resize automatically

    // Initial state - hide all but the first Test/AE/Present details row...
    // ...later on, do this only if not pre-populated (from memcache or wherever)
    $('#testerRow2, #testerRow3, #testerRow4, #testerRow5').hide();
    $('#aeRow2, #aeRow3, #aeRow4, #aeRow5').hide();
    $('#presentRow2, #presentRow3, #presentRow4, #presentRow5, #presentRow6').hide();
    $('.fa-plus-square, .fa-minus-square').show();

    // Add extra Test/AE/Present Rows as required (up to a maximum of 5)
    $('.fa-plus-square').on('click', function() {
        $(this).closest('.row').next().show('slow');
    });
    $('.fa-minus-square').on('click', function() {
        $(this).closest('.row').hide('slow');
    });


    // Hide textarea if not applicable
    var textareaSwitch = $('#inputVehicleSwitch, #switchPoliceStatus, #switchPoliceStatusReport, #switchDetail, #switchDetailReport'),
        saleInterest = $('#inputPromoteSaleInterest'),
        agreeVehicle = $('#inputAgreeVehicleToCertificate');

    textareaSwitch.hide();
    saleInterest.hide();
    agreeVehicle.hide();

    // Show/Hide textarea if yes/no is selected for the switch vehicle
    $('#vehicleSwitch').on('change', function(){
        if(this.selectedIndex == 1) {
            textareaSwitch.show('slow');
        } else {
            textareaSwitch.hide('slow');
        }
    });
    // Show/Hide textarea if yes/no is selected for issued due to a promote sale interest
    $('#promoteSaleInterest').on('change', function(){
        if(this.selectedIndex == 1) {
            saleInterest.show('slow');
        } else {
            saleInterest.hide('slow');
        }
    });
    // Show/Hide textarea if yes/no is selected for agreement of the vehicle
    $('#agreeVehicleToCertificate').on('change', function(){
        if(this.selectedIndex == 2) {
            agreeVehicle.show('slow');
        } else {
            agreeVehicle.hide('slow');
        }
    });

    // Change placeholder for textarea Police Status
    $('#vehiclePoliceStatus').on('change', function(){
        if(this.selectedIndex == 2) {
            $('#inputPoliceStatusReport').attr('placeholder','Was the Tester/AE advised to report this to the Police?');
        } else {
            $('#inputPoliceStatusReport').attr('placeholder','Give name of Police Officer and Office if applicable');
        }
    });

});
