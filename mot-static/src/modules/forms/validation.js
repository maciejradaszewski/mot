/*

JS Validation object. This object contains various methods to validation
values against.

*/

'use strict';

var Validation = {
	config: {
		version: '0.1.0'
	}
	,notEmpty: function(value) {
		return (value.length > 0);
	}
	,empty: function(value) {
		return !this.notEmpty(value);
	}
};

module.exports = Validation;
