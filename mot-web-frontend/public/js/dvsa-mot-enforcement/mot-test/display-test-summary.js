
    $(document).ready(function() {

        // Initially hide the complaintRefLine section assuming Full re-inspection...
        // Only re-show if Inverted or Statutory appeal selected...
        $('#complaintRefLine').hide();


        // If Inverted or Statutory appeal selected then re-show...
        $('#motTestType').on('change',function () {
            if ($(this).val() == "EI" || $(this).val() == "ES") {
                $('#complaintRefLine').show('slow');
                $('#complaintRef').attr("required","required");
                $('#complaintRef').focus();
            } else {
                $('#complaintRefLine').hide('slow');
                $('#complaintRef').attr("required",false);
            }
        })

    });