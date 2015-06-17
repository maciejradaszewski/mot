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

casper.start("http://localhost:3900/enforcement/compare-screen/steering.html?posted=1", function() {

    this.echo('Enforcement Reinspection Comparison Screen Steering - Posted State', 'info');

    var veModel = steering.createRowModel(casper, this.test, 'table tr.vehicle-examiner-row');
    var testerModel = steering.createRowModel(casper, this.test, 'table tr.tester-row');

    this.test.begin('A row should initialise to the desired state when posted', 32, function suite(test) {

        veModel.score.assertIndex(0).assertEnabled();
        veModel.defect.assertDisabled();
        veModel.category.assertDisabled();

        for (var index = 1; index <= SCORE_MAX_INDEX; index++) {
            casper.reload();

            veModel.score.index(index).assertIndex(index).assertEnabled();
            veModel.defect.assertEnabled();
            veModel.category.assertEnabled();
        }

        test.done();
    });

});

casper.run();