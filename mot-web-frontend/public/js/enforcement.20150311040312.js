/**
 * This is a generated file, please do not edit.
 * created on Wednesday, March 11th, 2015, 4:09:12 PM
 */

/*
 * JSFace Object Oriented Programming Library
 * https://github.com/tnhu/jsface
 *
 * Copyright (c) 2009-2013 Tan Nhu
 * Licensed under MIT license (https://github.com/tnhu/jsface/blob/master/LICENSE.txt)
 */
(function(context, OBJECT, NUMBER, LENGTH, toString, undefined, oldClass, jsface) {
  /**
   * Return a map itself or null. A map is a set of { key: value }
   * @param obj object to be checked
   * @return obj itself as a map or false
   */
  function mapOrNil(obj) { return (obj && typeof obj === OBJECT && !(typeof obj.length === NUMBER && !(obj.propertyIsEnumerable(LENGTH))) && obj) || null; }

  /**
   * Return an array itself or null
   * @param obj object to be checked
   * @return obj itself as an array or null
   */
  function arrayOrNil(obj) { return (obj && typeof obj === OBJECT && typeof obj.length === NUMBER && !(obj.propertyIsEnumerable(LENGTH)) && obj) || null; }

  /**
   * Return a function itself or null
   * @param obj object to be checked
   * @return obj itself as a function or null
   */
  function functionOrNil(obj) { return (obj && typeof obj === "function" && obj) || null; }

  /**
   * Return a string itself or null
   * @param obj object to be checked
   * @return obj itself as a string or null
   */
  function stringOrNil(obj) { return (toString.apply(obj) === "[object String]" && obj) || null; }

  /**
   * Return a class itself or null
   * @param obj object to be checked
   * @return obj itself as a class or false
   */
  function classOrNil(obj) { return (functionOrNil(obj) && (obj.prototype && obj === obj.prototype.constructor) && obj) || null; }

  /**
   * Util for extend() to copy a map of { key:value } to an object
   * @param key key
   * @param value value
   * @param ignoredKeys ignored keys
   * @param object object
   * @param iClass true if object is a class
   * @param oPrototype object prototype
   */
  function copier(key, value, ignoredKeys, object, iClass, oPrototype) {
    if ( !ignoredKeys || !ignoredKeys.hasOwnProperty(key)) {
      object[key] = value;
      if (iClass) { oPrototype[key] = value; }                       // class? copy to prototype as well
    }
  }

  /**
   * Extend object from subject, ignore properties in ignoredKeys
   * @param object the child
   * @param subject the parent
   * @param ignoredKeys (optional) keys should not be copied to child
   */
  function extend(object, subject, ignoredKeys) {
    if (arrayOrNil(subject)) {
      for (var len = subject.length; --len >= 0;) { extend(object, subject[len], ignoredKeys); }
    } else {
      ignoredKeys = ignoredKeys || { constructor: 1, $super: 1, prototype: 1, $superp: 1 };

      var iClass     = classOrNil(object),
          isSubClass = classOrNil(subject),
          oPrototype = object.prototype, supez, key, proto;

      // copy static properties and prototype.* to object
      if (mapOrNil(subject)) {
        for (key in subject) {
          copier(key, subject[key], ignoredKeys, object, iClass, oPrototype);
        }
      }

      if (isSubClass) {
        proto = subject.prototype;
        for (key in proto) {
          copier(key, proto[key], ignoredKeys, object, iClass, oPrototype);
        }
      }

      // prototype properties
      if (iClass && isSubClass) { extend(oPrototype, subject.prototype, ignoredKeys); }
    }
  }

  /**
   * Create a class.
   * @param parent parent class(es)
   * @param api class api
   * @return class
   */
  function Class(parent, api) {
    if ( !api) {
      parent = (api = parent, 0);                                     // !api means there's no parent
    }

    var clazz, constructor, singleton, statics, key, bindTo, len, i = 0, p,
        ignoredKeys = { constructor: 1, $singleton: 1, $statics: 1, prototype: 1, $super: 1, $superp: 1, main: 1, toString: 0 },
        plugins     = Class.plugins;

    api         = (typeof api === "function" ? api() : api) || {};             // execute api if it's a function
    constructor = api.hasOwnProperty("constructor") ? api.constructor : 0;     // hasOwnProperty is a must, constructor is special
    singleton   = api.$singleton;
    statics     = api.$statics;

    // add plugins' keys into ignoredKeys
    for (key in plugins) { ignoredKeys[key] = 1; }

    // construct constructor
    clazz  = singleton ? {} : (constructor ? constructor : function(){});

    // make sure parent is always an array
    parent = !parent || arrayOrNil(parent) ? parent : [ parent ];
    len = parent && parent.length;

    if ( !singleton && len) {
      clazz.prototype             = classOrNil(parent[0]) ? new parent[0] : parent[0];
      clazz.prototype.constructor = clazz;
    }

    // determine bindTo: where api should be bound
    bindTo = singleton ? clazz : clazz.prototype;

    // do inherit
    while (i < len) {
      p = parent[i++];
      for (key in p) {
        if ( !ignoredKeys[key]) {
          bindTo[key] = p[key];
          if ( !singleton) { clazz[key] = p[key]; }
        }
      }
      for (key in p.prototype) { if ( !ignoredKeys[key]) { bindTo[key] = p.prototype[key]; } }
    }

    // copy properties from api to bindTo
    for (key in api) {
      if ( !ignoredKeys[key]) {
        bindTo[key] = api[key];
      }
    }

    // copy static properties from statics to both clazz and bindTo
    for (key in statics) { clazz[key] = bindTo[key] = statics[key]; }

    // if class is not a singleton, add $super and $superp
    if ( !singleton) {
      p = parent && parent[0] || parent;
      clazz.$super  = p;
      clazz.$superp = p && p.prototype ? p.prototype : p;
    }

    for (key in plugins) { plugins[key](clazz, parent, api); }                 // pass control to plugins
    if (functionOrNil(api.main)) { api.main.call(clazz, clazz); }              // execute main()
    return clazz;
  }

  /* Class plugins repository */
  Class.plugins = {};

  /* Initialization */
  jsface = {
    Class        : Class,
    extend       : extend,
    mapOrNil     : mapOrNil,
    arrayOrNil   : arrayOrNil,
    functionOrNil: functionOrNil,
    stringOrNil  : stringOrNil,
    classOrNil   : classOrNil
  };

  if (typeof module !== "undefined" && module.exports) {                       // NodeJS/CommonJS
    module.exports = jsface;
  } else {
    oldClass          = context.Class;                                         // save current Class namespace
    context.Class     = Class;                                                 // bind Class and jsface to global scope
    context.jsface    = jsface;
    jsface.noConflict = function() { context.Class = oldClass; };              // no conflict
  }
})(this, "object", "number", "length", Object.prototype.toString);
/**
 * Client side HTML entity escaping.
 *
 * @param string
 * @returns string
 */
function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;',
            "/": '&#x2F;'
        }[s];
    });
}


/**
 * (G)global (F)unctions
 */
var GF = new (function () {

    // for screens already using the file level function
    this.escapeHtml = escapeHtml;

    /**
     * Make it so that data in id1 disables id2 and vice versa, this is a very
     * common requirement on a lot of the screens.
     *
     * @param id1 string
     * @param id2 string
     */
    this.uiEitherOr = function(id1, id2) {
        var $eId1 = $(id1),
            $eId2 = $(id2);

        $eId1.bind({
            keyup: function () {
                $eId2.prop('disabled', $(this).val().length);
            },
            blur: function() {
                if ($eId1.val() != '') {
                    $eId2.prop('disabled', 'disable');
                }
            }
        });

        $eId2.bind({
            keyup: function () {
                $eId1.prop('disabled', $(this).val().length);
            },
            blur: function() {
                if ($eId2.val() != '') {
                    $eId1.prop('disabled', 'disable');
                }
            }
        });
    }
});

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

var VehicleResultHelper = function() {
    var $eListVs = $('#listVehicles');

    $eListVs.dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "aaSorting": [
            [ 6, "desc" ]
        ],
        "bInfo": true,
        "bAutoWidth": true,
        "oLanguage": {"sSearch": "Filter:"},
        "bProcessing": false,
        "bServerSide": false,
        "bDeferRender": true,
        "aoColumnDefs": [
            {
                "sClass": "truncate",
                "aTargets": [4,5]
            }
        ]
    });

    $('#listVehicles_filter').find("input").addClass("form-control");
    $eListVs.find("thead").attr("style", "background-color:#DEE0E2;");
};

var EventListHelper = function(isShowDate, ajaxUrl) {

    var eListLs = $('#listLogs'),
        oTable = eListLs.dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "aaSorting": [
                [ 1, "desc" ]
            ],
            "bInfo": true,
            "bAutoWidth": true,
            "oLanguage": {
                "sSearch": "Filter:",
                "sLengthMenu": "Show _MENU_ events per page",
                "sInfo": "_START_ to _END_ events showing from a total of _TOTAL_ ",
                "sInfoFiltered": "(filtered from _MAX_ events)",
                "sInfoEmpty": "No events to show"
            },
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "dom": '<"top"if>rt<"bottom"lp><"clear">',
            'aoColumns' : [
                { 'mData' : 'type'},
                { 'mData' : 'date'},
                { 'mData' : 'description'}
            ],
            "aoColumnDefs": [
                {
                    "sClass": "longer-truncate",
                    "aTargets": [2]
                },
                {
                    'aTargets': [0],
                    'render': function (data) {
                        return '<a href="' + data.url + '">' + data.type + '</a>'
                    }
                }
            ],
            "sAjaxDataProp": 'data',
            "sServerMethod": 'POST',
            "bProcessing": true,
            "bServerSide": true,
            "bDeferRender": false,
            "sAjaxSource": ajaxUrl,
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                aoData.push({"name" :"_csrf_token",     "value" :$('input[name="_csrf_token"]').val()});
                aoData.push({"name" :"search",          "value" :$('#search').val()});
                aoData.push({"name" :"isShowDate",      "value" :$('#isShowDate').val()});
                aoData.push({"name" :"dateFrom[Day]",   "value" :$('#dateFrom-Day').val()});
                aoData.push({"name" :"dateFrom[Month]", "value" :$('#dateFrom-Month').val()});
                aoData.push({"name" :"dateFrom[Year]",  "value" :$('#dateFrom-Year').val()});
                aoData.push({"name" :"dateTo[Day]",     "value" :$('#dateTo-Day').val()});
                aoData.push({"name" :"dateTo[Month]",   "value" :$('#dateTo-Month').val()});
                aoData.push({"name" :"dateTo[Year]",    "value" :$('#dateTo-Year').val()});
                $.getJSON( sSource, aoData, function (json) {
                    /* Do whatever additional processing you want on the callback, then tell DataTables */
                    fnCallback(json);
                });
            }
        });

    eListLs.find("thead").attr("style", "background-color:#DEE0E2;");

    // Hide the datatables filter in the default position - with Javascript enabled, we'll feed
    // it with another one called '#searchbox' so that we can position it where we want.
    $('.dataTables_filter').hide();

    // Feed the hidden dataTables search/filter box from a custom one (left-hand side).
    $("#searchbox").keyup(function() {
        oTable.fnFilter(this.value);
    });

    // Hide the dataTables page-length selecter dropdown - and feed it with
    // a horizontal link list (to look more like the Slot Usage screen)
    var dataTables_length = $('.dataTables_length'),
        length_selector = dataTables_length.find('select'),
        length_selector_label = dataTables_length.find('label');

    $(length_selector_label).hide();
    $('.page-results-control').find('a').click(function() {
        $(length_selector).val(this.text);
        $(length_selector).trigger('change');
        // Then get the current table page length, and do something like this:
        var currentPageLength = $(length_selector).find(":selected").text();
        // first reset all apparently disabled links then make one of links look like its disabled
        $('.page-results-control').find('a').removeClass("item-link-disabled");
        //var selectorPageLength = "a#length"+currentPageLength;
        $('a#length'+currentPageLength).addClass("item-link-disabled");
    });

    var dateRangeFields = $('#dateRangeFields'),
        isShowDateInput = $('#isShowDate'),
        allEvents       = $('#allEvents'),
        searchInput     = $('#search');

    if (!isShowDate) {
        dateRangeFields.hide();
        // Set the selected Date Range Nav Filter (LHS) to be bold and not look like a link
        allEvents.addClass("item-bold-link-disabled");
    }
    $('#customRange').click(function() {
        dateRangeFields.show('slow');
        isShowDateInput.val(1);
        //allEvents.removeClass("item-bold-link-disabled");
    });

    // Handle all event click
    allEvents.click(function() {
        if ($(this).hasClass('item-bold-link-disabled')) {
            return false;
        }
        if (searchInput.val().length !== 0) {
            $(this).attr('href', $(this).attr('href') + '?search=' + searchInput.val());
        }
        return true;
    });

    // Then get the current table page length, and make one of links look like its disabled
    var currentPageLength = $(length_selector).find(":selected").text();
    $('a#length'+currentPageLength).addClass("item-link-disabled");

};

/**
 * This is a generated file, please do not edit.
 * created on Wednesday, March 11th, 2015, 4:09:12 PM
 */

/**
 * CompareScreenRowSteering
 *
 * Pre-requisite: jsface, lodash & backbone should be loaded
 *
 * This class attaches to a compare screen row, and controls the logic
 * used to steer the elements within the row.
 *
 * It is person aware, so works differently if the row is by a VE or a Tester
 * and is unit tested using the casper framework.
 *
 * To test this class run grunt test:js
 */

var CompareScreenRowSteering = jsface.Class({
    $statics: {
        SCORE_VALUE_DISREGARD: 1,
        SCORE_VALUE_NO_DEFECT: 5,
        SCORE_INDEX_DISREGARD: 0,
        DEFECT_VALUE_PLEASE_SELECT: "",
        DEFECT_VALUE_NOT_APPLICABLE: 1,
        DEFECT_VALUE_DEFECT_MISSED: 2,
        DEFECT_VALUE_INCORRECT_DECISION: 3
    },
    constructor: function(row, isPosted) {
        this.posted = isPosted;
	      this.loading = true;
	      this.isTesterRow = $(row).hasClass('tester-row');
        this.isVehicleExaminerRow = $(row).hasClass('vehicle-examiner-row');
        this.score = $(row).find('.point-score');
        this.defect = $(row).find('.defects-decisions');
        this.category = $(row).find('.categories');
        this.justification = $(row).find('.justification');
        this.changingItem = null;
        this.previousIndex = {
            score: null,
            defect: null,
            category: null
        };
        this.initDefaults();
    },
    initDefaults: function() {
        _.bindAll(this, 'onScoreChange', 'onDefectChange', 'onCategoryChange');

        this.initScoreOptions();
        this.initDefectOptions();

        this.disableElement(this.defect);
        this.disableElement(this.category);

        this.score.on('change', this.onScoreChange);
        this.defect.on('change', this.onDefectChange);
        this.category.on('change', this.onCategoryChange);

        if (this.isPosted) {
            this.score.trigger('change');
        }

    },
    isPosted: function() {
        return this.posted === true;
    },
    onScoreChange: function() {
        if (this.changingItem !== null) {
            return;
        }
        this.changingItem = this.score;

        // VE, should not be able to select "Not Applicable" in Defect & Category when Score is not Disregard
        if (this.isVehicleExaminerRow) {
            this.toggleNotApplicableOption(this.defect, this.score.val() == "1");
            this.toggleNotApplicableOption(this.category, this.score.val() == "1");
        }

        switch (parseInt(this.score.val())) {

            case this.SCORE_VALUE_DISREGARD:
                this.changeSelectValue(this.defect, 1);
                this.changeSelectValue(this.category, 1);
                this.disableElement(this.defect);
                this.disableElement(this.category);
                break;

            case this.SCORE_VALUE_NO_DEFECT:

                this.enableElement(this.defect);
	              this.enableElement(this.category);

                if (this.previousIndex.score == this.SCORE_INDEX_DISREGARD) {
                    this.changeSelectIndex(this.defect, 0);
                    this.changeSelectIndex(this.category, 0);
                }

			          if (this.isTesterRow && this.defect.val() == this.DEFECT_VALUE_INCORRECT_DECISION ) {
							    this.changeSelectIndex(this.category, 1);
							    this.disableElement(this.category);
						    }

                break;
            default:

                if (this.isTesterRow) {
                    this.changeSelectIndex(this.defect, 2);

                } else if(this.isVehicleExaminerRow && this.loading === false) {
                    this.changeSelectIndex(this.defect, 0);
                    this.changeSelectIndex(this.category, 0);
                }

                this.enableElement(this.defect);
                this.enableElement(this.category);
        }

        Backbone.Events.trigger('score-changed', {});

        this.previousIndex.score = this.index(this.score);
        this.changingItem = null;
    },
    onDefectChange: function() {
        if (this.changingItem !== null) {
            return;
        }
        this.changingItem = this.score;
	      var value = parseInt(this.defect.val());

        if (this.defect.val() == this.DEFECT_VALUE_PLEASE_SELECT) {
            this.changeSelectValue(this.category, 0);
	          this.enableElement(this.category);
        } else if (value === this.DEFECT_VALUE_NOT_APPLICABLE) {
	          this.enableElement(this.category);
            if (this.isTesterRow) {
                this.changeSelectValue(this.category, 1);
            } else if( this.isVehicleExaminerRow) {
                this.changeSelectValue(this.category, 0);
            }
        } else if (this.isTesterRow && this.score.val() == this.SCORE_VALUE_NO_DEFECT && value === this.DEFECT_VALUE_INCORRECT_DECISION ) {
	        this.changeSelectIndex(this.category, 1);
		      this.disableElement(this.category);
	      }

        this.previousIndex.defect = this.index(this.defect);
        this.changingItem = null;
    },
    onCategoryChange: function() {
        if (this.changingItem !== null) {
            return;
        }
        this.changingItem = this.category;
        // console.log('category changed to ' + this.category.val());
        this.previousIndex.category = this.index(this.category);
        this.changingItem = null;
    },
		initScoreOptions: function() {
			if (this.isTesterRow === true && this.score.find('option:nth-child(6)').text().trim() == "20 - Other defect missed") {
				this.score.find('option:nth-child(6)').remove();
			}
			if (this.isVehicleExaminerRow === true && this.score.find('option:nth-child(5)').text().trim() == "20 - No defect") {
				this.score.find('option:nth-child(5)').remove();
			}
		},
    initDefectOptions: function() {
        if (this.isTesterRow === true && this.defect.find('option:nth-child(3)').text().trim() == "Defect missed") {
            this.defect.find('option:nth-child(3)').remove();
        }
    },
    toggleNotApplicableOption: function(element, ensure) {
        var containsElement = element.find('option:contains(Not applicable)').length > 0;

        if (ensure === true && containsElement === true) {
            return;
        }
        if (ensure === false && containsElement === false) {
            return;
        }
        if (ensure === true && containsElement === false) {
            element.find('option').eq(0).after('<option value="1">Not applicable</option>');
            return;
        }
        element.find('option:contains(Not applicable)').remove();
    },
    changeSelectValue: function(element, value) {
        element.val(value).change();
    },
    index: function(element) {
        return element.prop('selectedIndex');
    },
    changeSelectIndex: function(element, index) {
        element.prop('selectedIndex', index);
    },
    disableElement: function(element) {
        element.prop('disabled', true);
    },
    enableElement: function(element) {
        element.prop('disabled', false);
    }
});
/**
 * CompareScreenSteering
 *
 * Pre-requisite: jsface, lodash & backbone should be loaded
 *
 * This class attaches to the compare screen table, it creates a CompareScreenRowSteering
 * object per row and manages the score & case outcome logic. It is person aware, so works
 * differently if the row is by a VE or a Tester and is unit tested using the casper framework.
 *
 * The plan is to move ALL of the js from the differences-found-between-tests.js file into unit
 * tested code but must be done gently..
 *
 * To test this class run grunt test:js
 */

var CompareScreenSteering = jsface.Class({
	$statics: {
		CASE_OUTCOME_NO_FURTHER_ACTION: 1,
		CASE_OUTCOME_ADVISORY_WARNING_LETTER: 2,
		CASE_OUTCOME_DISCIPLINARY_ACTION_REPORT: 3
	},
	constructor: function(options) {
		this.models = [];
		this.table = null;
		this.selector = options.selector || null;
		this.isPosted = options.isPosted || false;
		this.caseOutcome = null;
		this.totalScore = 0;

		// Note that these bounds frequently change and should come from a database!
		// VE can override if he wishes
		// todo: pass bound(s) as a config object in constructor
		this.bound1 = 9;  // 0 - 9 - No further action
		this.bound2 = 29; // 10-29 - Advisory warning letter
		this.bound3 = 30; // 30+   - Disciplinary Action Report

		_.bindAll(this, 'onCaseOutcomeChange');
	},
	/**
	 * Attach this object to the page table, extract the relevant model rows and case outcome
	 */
	attach: function() {
		var that = this;

		this.table = $(this.selector);

		if (this.table === null) {
			// throw error and die
		}

		this.caseOutcome = this.table.find('#caseOutcome');
		this.caseOutcome.on('change', this.onCaseOutcomeChange);

		$(this.table.find('tr.tester-row,tr.vehicle-examiner-row')).map(function(index, row) {
			that.models.push(new CompareScreenRowSteering(row, that.isPosted));
		});

		// When a score changes, count & dispatch the new total score
		Backbone.Events.on('score-changed', function() {
			this.calcTotalScore();
			this.onTotalScoreChange(false);
		}, this);

		// Trigger initialised calc
		this.calcTotalScore();
		this.onTotalScoreChange(true);

		this.models.map(function(item) {
			item.loading = false;
			return item;
		});
		return this;
	},
	/**
	 * Count the current scores, extracts the data-value from the scores selected item
	 *
	 * @returns {number}
	 */
	calcTotalScore: function() {
		this.totalScore = 0;

		this.models.map(function (item) {
			this.totalScore += parseInt(item.score.find(':selected').data('value')) || 0;
		}, this);

		return this;
	},
	/**
	 * Rules to determine the selected case outcome depending on the current score
	 */
	preSelectCaseOutcome: function() {
		// Pre-select the recommended case outcome select dropdown - according to total score.
		if (this.totalScore < this.bound1) {
			this.caseOutcome.val(1);
		} else if (this.totalScore <= this.bound2) {
			this.caseOutcome.val(2);
		} else if (this.totalScore >= this.bound3) {
			this.caseOutcome.val(3);
		}
	},
	onTotalScoreChange: function(suppressCaseOutcome) {
		Backbone.Events.trigger('total-score-changed', {
			totalScore: this.totalScore,
			suppressCaseOutcome: suppressCaseOutcome === true ? 1 : 0
		});
		return this;
	},
	onCaseOutcomeChange: function () {
		var caseOutcomeValue = parseInt(this.caseOutcome.find(":selected").val());

		if (this.totalScore <= this.bound1 && caseOutcomeValue != "1") {
			this.triggerCaseOutcomeJustificationRequired('Rule 1');
		} else if (this.totalScore > this.bound1 && this.totalScore <= this.bound2 && caseOutcomeValue != "2") {
			this.triggerCaseOutcomeJustificationRequired('Rule 2');
		} else if (this.totalScore >= this.bound3 && caseOutcomeValue != "3") {
			this.triggerCaseOutcomeJustificationRequired('Rule 3');
		}
	},
	triggerCaseOutcomeJustificationRequired: function(rule) {
		Backbone.Events.trigger('case-outcome-justification-required', {
			totalScore: this.totalScore,
			caseOutcome: parseInt(this.caseOutcome.find(":selected").val()),
			rule: rule
		});
		return this;
	}
});
