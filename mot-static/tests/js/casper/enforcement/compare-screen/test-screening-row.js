/**
 * Example spike into using casper to functionally test the compare screen, which
 * is way too complex to test in selenium, so we will do it quicker here.
 */
var Steering = require('steering.js');
var steering = new Steering();
var SCORE_MAX_INDEX = 7;

casper.echoComment = function(text) {
    casper.echo(text, "PARAMETER");
}

casper.start("http://localhost:3900/enforcement/compare-screen/steering.html", function() {

    this.echo('Enforcement Reinspection Comparison Screen Steering', 'info');

    var veModel = steering.createRowModel(casper, this.test, 'table tr.vehicle-examiner-row');
    var testerModel = steering.createRowModel(casper, this.test, 'table tr.tester-row');

    this.test.begin('A row should initialise to the desired state', 7, function suite(test) {

        veModel.score
            .assertIndex(0)
            .assertValue(Steering.SCORE_DISREGARD)
            .assertEnabled();
        veModel.defect
            .assertDisabled()
            .assertValue(Steering.DEFECT_NOT_APPLICABLE);
        veModel.category
            .assertDisabled()
            .assertValue(Steering.CATEGORY_NOT_APPLICABLE);

        test.done();
    });

    this.test.begin('Defect and Category should be enabled on Score changing from Disregard', 28, function suite(test) {

        for (var index = 1; index <= SCORE_MAX_INDEX; index++) {

            casper.reload();

            casper.echoComment("- Test for score changing to index "+index);

            veModel.score
                .index(0)
                .assertIndex(0)
                .index(index)
                .assertIndex(index);

            veModel.defect.assertEnabled();
            veModel.category.assertEnabled();
        }

        test.done();
    });

    this.test.begin('When Score changes from Disregard then Defect and Category should default to "please select" unless score is "No Defect" ', 48, function suite(test) {

        for (var index = 1; index <= SCORE_MAX_INDEX; index++) {

            // This test is not true if the index is "20 - no defect"
            if (index === 4) {
                continue;
            }

            casper.reload();

            veModel.defect.index(2).assertIndex(2);
            veModel.category.index(2).assertIndex(2);

            casper.echoComment("- Test for score changing to index "+index);

            veModel.score
                .index(0)
                .assertIndex(0)
                .index(index)
                .assertIndex(index);

            veModel.defect
                .assertIndex(0)
                .assertValue(Steering.DEFECT_PLEASE_SELECT);
            veModel.category
                .assertIndex(0)
                .assertValue(Steering.CATEGORY_PLEASE_SELECT);
        }

        test.done();
    });

    this.test.begin('Defect and Category should be disabled on Score changing back to Disregard', 42, function suite(test) {

        for (var index = 1; index <= SCORE_MAX_INDEX; index++) {

            casper.reload();

            casper.echoComment("- Test for score changing from index "+index);

            veModel.score.index(index).assertIndex(index);
            veModel.defect.assertEnabled();
            veModel.category.assertEnabled();

            veModel.score.index(0).assertIndex(0);

            veModel.defect.assertDisabled();
            veModel.category.assertDisabled();
        }

        test.done();
    });

    this.test.begin('For a Tester, Defect should not contain "Defect Missed"', 3, function suite(test) {

        casper.reload();

        testerModel.defect.index(0).assertValue(Steering.DEFECT_PLEASE_SELECT);
        testerModel.defect.index(1).assertValue(Steering.DEFECT_NOT_APPLICABLE);
        testerModel.defect.index(2).assertValue(Steering.DEFECT_INCORRECT_DECISION);

        test.done();
    });

		this.test.begin('For a Tester, Score should not contain "20 - Other Defect Missed"', 8, function suite(test) {

			casper.reload();

			testerModel.score.index(0).assertValue(Steering.SCORE_DISREGARD);
			testerModel.score.index(1).assertValue(Steering.SCORE_OVERRULED_MARGINALLY);
			testerModel.score.index(2).assertValue(Steering.SCORE_OBVIOUSLY_WRONG);
			testerModel.score.index(3).assertValue(Steering.SCORE_SIGNIFICANTLY_WRONG);
			testerModel.score.index(4).assertValue(Steering.SCORE_NO_DEFECT);
			testerModel.score.index(5).assertValue(Steering.SCORE_NOT_TESTABLE);
			testerModel.score.index(6).assertValue(Steering.SCORE_EXCESSIVE_DAMAGE);
			testerModel.score.index(7).assertValue(Steering.SCORE_RISK_INJURY);

			test.done();
		});

    this.test.begin('For a Tester, When Score is not "Disregard" or "20 - No Defect", then Defect must be "Incorrect Decision"', 12, function suite(test) {

        casper.reload();

        for (var index = 0; index <= SCORE_MAX_INDEX; index++) {

            casper.echoComment("Testing correct defect for score index "+index);

            testerModel.defect.index(0);
            testerModel.score.index(index);

            if (index != 0 && index != 4) {
                testerModel.defect
                    .assertIndex(2)
                    .assertEnabled();
            }
        }

        test.done();
    });

    this.test.begin('For a Tester, When Score is not "Disregard" or "20 - No Defect", Category must be unchanged', 12, function suite(test) {

        casper.reload();

        for (var index = 0; index <= SCORE_MAX_INDEX; index++) {

            casper.echoComment("Testing correct category for score index "+index);

            testerModel.category.index(2);
            testerModel.score.index(index);

            if (index != 0 && index != 4) {
                testerModel.category
                    .assertIndex(2)
                    .assertEnabled();
            }
        }

        test.done();
    });

    this.test.begin('For a VE, When score is Disregard, Defect should always contain "Not applicable"', 6, function suite(test) {

        casper.reload();

        veModel.score.index(0);
        veModel.defect.index(1).assertValue(Steering.DEFECT_NOT_APPLICABLE);

        veModel.score
            .index(1)
            .index(0);

        veModel.defect.index(0).assertValue(Steering.DEFECT_PLEASE_SELECT);
        veModel.defect.index(1).assertValue(Steering.DEFECT_NOT_APPLICABLE);
        veModel.defect.index(2).assertValue(Steering.DEFECT_DEFECT_MISSED);
        veModel.defect.index(3).assertValue(Steering.DEFECT_INCORRECT_DECISION);
        veModel.defect.assertOptionLength(4);

        test.done();
    });

    this.test.begin('For a VE, When score is not Disregard, Defect should not contain "Not applicable"', 5, function suite(test) {

        casper.reload();

        veModel.score.index(0);
        veModel.defect.index(1).assertValue(Steering.DEFECT_NOT_APPLICABLE);

        veModel.score.index(1);
        veModel.defect.index(0).assertValue(Steering.DEFECT_PLEASE_SELECT);
        veModel.defect.index(1).assertValue(Steering.DEFECT_DEFECT_MISSED);
        veModel.defect.index(2).assertValue(Steering.DEFECT_INCORRECT_DECISION);
        veModel.defect.assertOptionLength(3);

        test.done();
    });

    this.test.begin('For a VE, When score is Disregard, Category should always contain "Not applicable"', 7, function suite(test) {

        casper.reload();

        veModel.score.index(0);
        veModel.category.index(1).assertValue(Steering.CATEGORY_NOT_APPLICABLE);

        veModel.score
            .index(1)
            .index(0);

        veModel.category.index(0).assertValue(Steering.CATEGORY_PLEASE_SELECT);
        veModel.category.index(1).assertValue(Steering.CATEGORY_NOT_APPLICABLE);
        veModel.category.index(2).assertValue(Steering.CATEGORY_IMMEDIATE);
        veModel.category.index(3).assertValue(Steering.CATEGORY_DELAYED);
        veModel.category.index(4).assertValue(Steering.CATEGORY_INSPECTION_NOTICE);
        veModel.category.assertOptionLength(5);

        test.done();
    });

    this.test.begin('For a VE, When score is not Disregard, Category should not contain "Not applicable"', 6, function suite(test) {

        casper.reload();

        veModel.score.index(0);
        veModel.category.index(1).assertValue(Steering.CATEGORY_NOT_APPLICABLE);

        veModel.score.index(1);
        veModel.category.index(0).assertValue(Steering.CATEGORY_PLEASE_SELECT);
        veModel.category.index(1).assertValue(Steering.CATEGORY_IMMEDIATE);
        veModel.category.index(2).assertValue(Steering.CATEGORY_DELAYED);
        veModel.category.index(3).assertValue(Steering.CATEGORY_INSPECTION_NOTICE);
        veModel.category.assertOptionLength(4);

        test.done();
    });

    this.test.begin('For a VE, When defect changes to "Please select" then Category should default to "Please select" ', 4, function suite(test) {

        casper.reload();

        veModel.defect.index(1);
        veModel.category.index(1).assertValue(Steering.CATEGORY_IMMEDIATE);

        veModel.defect.index(0);
        veModel.category
            .assertIndex(0)
            .assertValue(Steering.CATEGORY_PLEASE_SELECT)
            .assertEnabled();

        test.done();
    });

    this.test.begin('For a VE, score should not contain "No defect"', 8, function suite(test) {

		    casper.reload();

	      veModel.score.index(0).assertValue(Steering.SCORE_DISREGARD);
		    veModel.score.index(1).assertValue(Steering.SCORE_OVERRULED_MARGINALLY);
		    veModel.score.index(2).assertValue(Steering.SCORE_OBVIOUSLY_WRONG);
		    veModel.score.index(3).assertValue(Steering.SCORE_SIGNIFICANTLY_WRONG);
		    veModel.score.index(4).assertValue(Steering.SCORE_OTHER_DEFECT_MISSED);
		    veModel.score.index(5).assertValue(Steering.SCORE_NOT_TESTABLE);
		    veModel.score.index(6).assertValue(Steering.SCORE_EXCESSIVE_DAMAGE);
		    veModel.score.index(7).assertValue(Steering.SCORE_RISK_INJURY);

	      test.done();
    });

    this.test.begin('For a VE, When score is "No defect" then Category should be "Please select"', 5, function suite(test) {

        casper.reload();

        veModel.score.index(0);
        veModel.category
            .index(1)
            .assertIndex(1)
            .assertValue(Steering.CATEGORY_NOT_APPLICABLE);

        veModel.score
            .index(4)
            .assertIndex(4);
        veModel.category
            .assertIndex(0)
            .assertValue(Steering.CATEGORY_PLEASE_SELECT);

        test.done();
    });

    this.test.begin('For a Tester, When defect changes to "Please select" then Category should default to "Please select" ', 4, function suite(test) {

        casper.reload();

        testerModel.defect.index(1);
        testerModel.category.index(1).assertValue(Steering.CATEGORY_NOT_APPLICABLE);

        testerModel.defect.index(0);
        testerModel.category
            .assertIndex(0)
            .assertValue(Steering.CATEGORY_PLEASE_SELECT)
            .assertEnabled();

        test.done();
    });

    this.test.begin('For a Tester, When defect changes to "Not applicable" then Category should default to "Not applicable"', 6, function suite(test) {

        casper.reload();

        testerModel.defect.assertIndex(0);
        testerModel.category.assertIndex(0).assertValue(Steering.CATEGORY_PLEASE_SELECT);

        testerModel.defect.index(1);
        testerModel.category
            .assertValue(Steering.CATEGORY_NOT_APPLICABLE)
            .assertIndex(1)
            .assertEnabled();

        test.done();
    });

		this.test.begin('For a Tester, When score is "20 - No defect" and defect is "Incorrect decision" then Category should be disbaled', 1, function suite(test) {

			casper.reload();

			testerModel.score.index(4);
			testerModel.defect.index(2);
			testerModel.category.assertDisabled();

			test.done();
		});

});

casper.run();