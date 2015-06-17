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
 * templateFn        NO             Callback to create drown-down suggestion text. See typeahead.js docs for details.
 *
 * filterFn          NO             User supplied filter callback. Please see typeahead.js docs for the details.
 *
 * triggerLength     NO             The minimum amount of characters required to trigger an AJAX data fetch.
 *                                  Default is 2.
 *
 * onDatum           NO             If present, must be a function that returns a value to be used in the dropdown
 *                                  suggestion. It will be given a JSON object for each row of the returned data.
 *
 * clipLength        NO             Trims string in dropdown to avoid busted layouts. Undefined means no clipping.
 *
 * dropDownLimit     NO             Default 10. The number of items to show in the suggestions drop-down menu.
 *
 * @param config
 */
function installTypeahead(config) {
    var $eTextBox = $(config['sourceId']);

    // :( I miss Haskell....
    var param = function (x, d) {
        return (undefined == config[x]) ? d : config[x];
    };

    function paramFnc(paramName, defFnc) {
        var fnc = config[paramName] || undefined;

        if ((undefined == fnc)) {
            return defFnc;
        }

        //  --  find function or property if it presented in CLassName.function style   --
        try {
            if (typeof fnc === 'string') {
                var parts = fnc.split('.'),
                    result = window[parts[0]];

                for (var i = 1, n = parts.length; i < n; i++) {
                    result = result[parts[i]];
                }

                fnc = result;
            }
        } catch (e) {
        }

        if (typeof fnc !== 'function') {
            log('incorrect function at parameter [' + paramName + ']');
        }

        return fnc;
    }

    // used in a loop...
    var clipLength = param('clipLength', 0);

    var fncOnAutoComplete = paramFnc('onAutoComplete');
    var fncOnSelected = paramFnc('onSelected');

    //-----------------------------------------------------------------------
    // Provide a default filter function if not supplied...
    //-----------------------------------------------------------------------
    var fncFilter = paramFnc('filterFn',
        function (parsedResponse) {
            if (typeof(parsedResponse.searched.isElasticSearch) !== 'undefined' && !parsedResponse.searched.isElasticSearch) {
                return [];
            }
            return $.map(parsedResponse.data, function (v, k) {
                return {id: k, value: v};
            })
        });

    //-----------------------------------------------------------------------
    // Provide a default template function if not supplied...
    //-----------------------------------------------------------------------
    var fncTemplate = paramFnc('templateFn',
        function (datum) {
            var content = (clipLength > 0) ? datum.value.substr(0, clipLength) : datum.value;
            return ['<p data-id=""', datum.id, '">', content, '</p>'].join('');
        });


    //-----------------------------------------------------------------------
    // All configuration is locked and loaded, wire it up....
    //-----------------------------------------------------------------------
    var typeahead = $eTextBox.typeahead({ //TODO: using 0.9.3, upgrade to latest version?!
        name: param('cacheName', config.sourceId + '-data'),
        minLength: param('triggerLength', 3),
        limit: param('dropDownLimit', 10),

        remote: {
            url: param('dataUrl', '/404') + '?search=%QUERY',
            filter: fncFilter,
            cache: false // TODO: set to true when done with development!
        },
        template: fncTemplate
    });


    var prevTypeValue = null,
        wasSelected = false;

    typeahead.on('typeahead:opened', function () {
        resetBeforeChange();
    });

    $eTextBox.change(function (e) {
        updateIfChanged();
    });

    $eTextBox.blur(function (e) {
        updateIfChanged();
    });

    function resetBeforeChange() {
        wasSelected = false;
        prevTypeValue = $eTextBox.val();
    }

    function updateIfChanged() {
        var val = $eTextBox.val();

        if (((val != prevTypeValue && !wasSelected) || val == '') && fncOnSelected) {
            fncOnSelected(null, val);
        }
    }

    typeahead.on('typeahead:selected', function () {
        wasSelected = true;
        prevTypeValue = $eTextBox.val();
    });

    //
    // EVENT: user has selected a drop-down suggestion
    //
    if (fncOnSelected) {
        typeahead.on('typeahead:selected', fncOnSelected);
    }

    //
    // EVENT: user has tab-completed a suggestion
    //
    if (fncOnAutoComplete) {
        typeahead.on('typeahead:autocompleted', fncOnAutoComplete);
    }

    if (config.focus) {
        $eTextBox.focus();
    }
}
