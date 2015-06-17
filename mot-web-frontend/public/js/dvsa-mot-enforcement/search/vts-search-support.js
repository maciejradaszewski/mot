$(document).ready(function () {
    //
    // Page specific setup for the VTS search input box
    //
    setupTypeaheadOnVTS({
        sourceId: '#vts-search',
        dataUrl: typeaheadDataUrl,
        onSelected: function () {
            $('#item-selector-btn-search').click();
        },
        onAutoCompleted: function () {
            $('#item-selector-btn-search').click();
        }
    });

    $('#vts-search').focus();
});
