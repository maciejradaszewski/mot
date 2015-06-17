$(document).ready(function() {
    //
    // VTS Search box control code
    //
    $('#vts-search').typeahead([
        {
            name: 'vtsData',
            cache: false, // set to true when done with development
            minLength: 4, // set's the min length of chars needed before Autocomplete kicks in
            remote: {
                url: '<?php echo $this->url('vehicle-testing-station-search-api', array('action' => 'vehicleTestingStationFetchData')) ?>?siteNumber=%QUERY',
                filter: function(parsedResponse) {
                    var data = [];
                    var count = parsedResponse.data.length;
                    for (var i=0; i<count; i++) {
                        var dbData = parsedResponse.data[i];
                        // Construct the typeahead datum object.  https://github.com/twitter/typeahead.js#datum
                        var datum = {
                            value: dbData.siteNumber,
                            address: dbData.address,
                            name: dbData.name
                        };
                        data.push(datum);
                    }
                    return data;
                }
            },
            template: function(datum) {
                var html = datum.value + ' - <b>' + datum.name + '</b>,  <i>' + datum.address + '</i>';
                return html;
            }
        }
    ]).on('typeahead:selected', function (e, data) {
        $('#item-selector-btn-search').click();
    }).on('typeahead:autocompleted', function (e, data) {
        $('#item-selector-btn-search').click();
    });

    $("#vts-search").focus();

});
