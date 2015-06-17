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
