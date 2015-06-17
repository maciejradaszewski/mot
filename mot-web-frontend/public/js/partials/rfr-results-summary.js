
// VM-4478 - separated out JS as part of this.

$(document).ready(function(){

    showHideRfrResults = $("#showHideRfrResults");
    $rfrCount = $("#rfrCount");

    $rfrCount.append(' <i class="fa fa-chevron-down"></i>');
    showHideRfrResults.on('shown.bs.collapse hidden.bs.collapse', function (e) {
        e.stopPropagation();
        $rfrCount.children("i").toggleClass("fa-chevron-down fa-chevron-up");
    });

    showHideRfrResults.addClass("collapse");

});