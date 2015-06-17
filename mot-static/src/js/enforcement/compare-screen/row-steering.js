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