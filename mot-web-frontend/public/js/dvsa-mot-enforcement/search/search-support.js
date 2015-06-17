/* log function - Used to make console logging cross browser */
function log(display) {
    if (typeof window.console !== 'undefined') {
        console.log(display);
    }
}

/**
 * (This requires the typeahead.js file to have been previously included)
 *
 * The configuration object allows for various tweaking of behaviours.
 *
 * Field name        Required       Does
 * --------------------------------------------------------------------------------------------------------------------
 * sourceId          YES            Specifies the target INPUT box as '#foo'
 *
 * focus             NO             Default: true, causes the focus to be given to the input control when initialised.
 *
 * dataUrl           YES            Specifies the data source for completions
 *
 * onSelected        NO             A function, which if supplied is called when the event 'typeahead:selected' fires.
 *
 * onAutoComplete    NO             As above but called when the typeahead event 'typeahead:autocompleted' fired.
 *
 * templateFn        NO             If given must be a closure/function that creates a suggestion entry. The function
 *                                  has the signature:  fn()
 *
 * triggerLen        NO             The minimum amount of characters required to trigger an AJAX data fetch.
 *
 * onDatum           NO             If present, must be a function that returns a value to be usedin the dropdown
 *                                  selection. It will be given a JSON object for each row of the returned data.
 *
 * clipLen           NO             Defaults to 70. Trims string in dropdown to avoid busted layouts.
 *
 * @param config
 */
function setupTypeaheadOnVTS(config) {

    var textBox = $(config['sourceId']);
    var triggerLen = 4;
    var clipLen = 70;
    var ddLimit = 10;

    if (undefined != config.triggerLen) {
        triggerLen = config.triggerLen;
    }
    if (undefined != config.clipLen) {
        clipLen = config.clipLen;
    }
    if (undefined != config.ddLimit) {
        ddLimit = config.ddLimit;
    }

    textBox.typeahead([
            {
                name: config['cacheName'],
                cache: false, // TODO: set to true when done with development!
                minLength: triggerLen,
                limit: ddLimit,
                //
                // TODO: understand "remote", does it need to be configurable ?
                //
                remote: {
                    url: config.dataUrl + '?search=%QUERY',
                    //
                    // TODO: does "filter" need to be configurable ?
                    //
                    filter: function (parsedResponse) {
                        var data = [];
                        $.each(parsedResponse.data, function (index, value) {
                            var datum = {
                                id: index,
                                value: value
                            };
                            data.push(datum);
                        });
                        return data;
                    }
                },
                //
                // TEMPLATE: Used to construct the drop-down suggestion content
                //
                template: function (datum) {
                    if (undefined == config.templateFn) {
                        var html = '<p data-id="' + datum.id + '">' + datum.value + '</p>';
                        return html.substring(0, clipLen);
                    } else {
                        return config.templateFn(this, datum);
                    }
                }
            }
        ])
        //
        // EVENT:
        //
        .on('typeahead:selected', function (e, datum) {
            if (undefined != config['onSelected']) {
                config['onSelected'](e, datum);
            }
        })
        //
        // EVENT:
        //
        .on('typeahead:autocompleted', function (e, datum) {
            if (undefined != config['onAutoComplete']) {
                config['onAutoComplete'](e, datum);
            }
        });

    if (config.focus) {
        textBox.focus();
    }
}
