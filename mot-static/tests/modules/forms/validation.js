var assert = require('assert'),
	sinon  = require('sinon'),
	_  = require('underscore'),
	should  = require('should');

var validation = require('../../../src/modules/forms/validation');

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

describe ('Form Validator Test', function() {

	beforeEach(function(done) {
		done();
	});

	it('should validate empty values', function() {
		validation.empty('').should.be.true;
		validation.empty('Test').should.be.false;
	});

	it('should validate not empty values', function() {
		validation.notEmpty('').should.be.false;
		validation.notEmpty('Test').should.be.true;
	});

});

