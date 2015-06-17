'use strict';

var jsface = require('jsface');

var CasperSelect  = require('../../lib/ui/casper-select');

var JasperCompareSteering = jsface.Class({
    $statics: {
        SCORE_DISREGARD: "Disregard",
        SCORE_OVERRULED_MARGINALLY: "0 - Overruled, marginally wrong",
        SCORE_OBVIOUSLY_WRONG: "5 - Obviously wrong",
        SCORE_SIGNIFICANTLY_WRONG: "10 - Significantly wrong",
        SCORE_NO_DEFECT: "20 - No defect",
        SCORE_OTHER_DEFECT_MISSED: "20 - Other defect missed",
        SCORE_NOT_TESTABLE: "20 - Not testable",
        SCORE_EXCESSIVE_DAMAGE: "30 - Exs. corr/wear/damage missed",
        SCORE_RISK_INJURY: "40 - Risk of injury missed",

        DEFECT_PLEASE_SELECT: 'Please select',
        DEFECT_NOT_APPLICABLE: 'Not applicable',
        DEFECT_DEFECT_MISSED: 'Defect missed',
        DEFECT_INCORRECT_DECISION: 'Incorrect decision',

        CATEGORY_PLEASE_SELECT: 'Please select',
        CATEGORY_NOT_APPLICABLE: 'Not applicable',
        CATEGORY_IMMEDIATE: "Immediate",
        CATEGORY_DELAYED: "Delayed",
        CATEGORY_INSPECTION_NOTICE: "Inspection notice"

    },
    constructor: function(selector, name, test) {
        this.selector = selector;
        this.name = name;
        this.test = test;
    },
    /**
     * Extract elements from html into accessible objects
     *
     * @param casper
     * @param test
     * @param css
     * @returns {{score: Select, defect: Select, category: Select}}
     */
    createRowModel: function(casper, test, css) {
        var model = {
            score: (new CasperSelect(css +' .point-score', 'score', test)),
            defect: (new CasperSelect(css +' .defects-decisions', 'defect', test)),
            category: (new CasperSelect(css +' .categories', 'category', test))
        };

        return model;
    }
});

module.exports = JasperCompareSteering;
