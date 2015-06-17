var assert = require('assert'),
	sinon  = require('sinon'),
	_  = require('underscore'),
	should  = require('should');

var Tomill = require('../../../src/modules/styleguide/tomill');

describe ('Tomill Living Styleguide', function() {

    var tomill = new Tomill();

	before(function(done) {
        tomill.parseFile(__dirname + '/data/breadcrumbs.scss');
		done();
	});

	beforeEach(function(done) {
		done();
	});

	it('should find comment elements', function() {
		tomill.comments.should.have.length(3);
	});

    it('should create atoms', function() {
        tomill.atoms.should.have.length(3);

        var firstAtom = tomill.atoms[0];
        firstAtom.name.should.equal('breadcrumb');
        firstAtom.category.should.equal('Molecule');
        firstAtom.status.should.equal('production');
        firstAtom.examples.should.have.length(1);

        var secondAtom = tomill.atoms[1];
        secondAtom.name.should.equal('buttons');
        secondAtom.category.should.equal(tomill.config.labels.defaultCategory);
        secondAtom.status.should.equal('production');
        secondAtom.examples.should.have.length(5);
    });

});

/*
 ======== A Handy Little Nodeunit Reference ========
 https://github.com/caolan/nodeunit

 Test methods:
 test.expect(numAssertions)
 test.done()
 Test assertions:
 test.ok(value, [message])
 test.equal(actual, expected, [message])
 test.notEqual(actual, expected, [message])
 test.deepEqual(actual, expected, [message])
 test.notDeepEqual(actual, expected, [message])
 test.strictEqual(actual, expected, [message])
 test.notStrictEqual(actual, expected, [message])
 test.throws(block, [error], [message])
 test.doesNotThrow(block, [error], [message])
 test.ifError(value)
 */

