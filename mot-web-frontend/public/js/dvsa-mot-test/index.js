//  was moved from Application/display-mot-test/mot-test/index.phtml (if you need history)
$(document).ready(function () {
    $("#odometer").click(function () {
        $("#odometerValue").trigger("click");
    });
    $("#unit").change(function () {
        $("#odometerValue").trigger("click");
    });

    var $odometerReading = $("#odometerReading");

    $odometerReading.after(
        '<div class="col-sm-2"> ' +
        '<button class="btn btn-primary pull-right" id="addOdometer" data-toggle="collapse" data-target="#showHideOdometerResults"><span>Add<span class="fa fa-chevron-down"></span></span></button>' +
        '</div>'
    );
    var $addOdometer = $("#addOdometer");

    $("#showHideOdometerResults").addClass("collapse");

    var hash = window.location.hash;

    if (!MotTestIndex.FAIL_REGEX.test(hash)) {
        $("#showHideFailureResults").addClass("collapse");
    }
    if (!MotTestIndex.ADVISORY_REGEX.test(hash)) {
        $("#showHideAdvisoryResults").addClass("collapse");
    }
    if (!MotTestIndex.PRS_REGEX.test(hash)) {
        $("#showHidePrsResults").addClass("collapse");
    }

    $("#showHideOdometerResults").on('shown.bs.collapse hidden.bs.collapse', function (e) {
        e.stopPropagation();
        $addOdometer.find("span").eq(1).toggleClass("fa-chevron-down fa-chevron-up");
    });

    if (!$odometerReading.find("p").eq(0).text().match(/Not recorded/i)) {
        $addOdometer.html('<span>Edit<span class="fa fa-chevron-down"></span></span>');
    }
    
    if ($('#prsResults').find('li').length !== 0) {
        $("#prsCount").append(' <i class="fa fa-chevron-down"></i>');
        $("#showHidePrsResults").on('shown.bs.collapse hidden.bs.collapse', function (e) {
            e.stopPropagation();
            $("#prsCount").children("i").toggleClass("fa-chevron-down fa-chevron-up");
        });
    }

    if ($('#failureResults').find('li').length !== 0) {
        $("#failureCount").append(' <i class="fa fa-chevron-down"></i>');
        $("#showHideFailureResults").on('shown.bs.collapse hidden.bs.collapse', function (e) {
            e.stopPropagation();
            $("#failureCount").children("i").toggleClass("fa-chevron-down fa-chevron-up");
        });

        if (MotTestIndex.IS_RETEST) {
            $("#showHideFailureResults").collapse('toggle');
        }
    }

    if ($('#advisoryResults').find('li').length !== 0) {
        $("#advisoryCount").append(' <i class="fa fa-chevron-down"></i>');
        $("#showHideAdvisoryResults").on('shown.bs.collapse hidden.bs.collapse', function (e) {
            e.stopPropagation();
            $("#advisoryCount").children("i").toggleClass("fa-chevron-down fa-chevron-up");
        });
    }
});