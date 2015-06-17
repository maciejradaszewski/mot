module.exports = function(grunt) {
    grunt.config('concat', {
        options: {
            banner: '/**\n * This is a generated file, please do not edit.\n * created on <%= buildDateTime %>\n */\n\n'
        },
        casper_classes: {
            src: [
	            './mot-static/src/js/enforcement/compare-screen/row-steering.js',
	            './mot-static/src/js/enforcement/compare-screen/steering.js'
            ],
            dest: './mot-static/tests/js/casper/public/js/classes.js'
        },
        libraries: {
            src: [
                './mot-static/src/js/vendor/min/jquery/jquery.min.js',
                './mot-static/src/js/vendor/min/jquery/jquery.autosize.min.js',
                './mot-static/src/js/vendor/min/jquery/jquery.dataTables.min.js',
                './mot-static/src/js/vendor/min/jquery/jquery.validate.min.js',
                './mot-static/src/js/vendor/min/jquery/additional-methods.min.js',
                './mot-static/src/js/vendor/min/jquery/select2.min.js',
                './mot-static/src/js/vendor/min/bootstrap.min.js',
                './mot-static/src/js/vendor/min/typeahead.min.js',
                './mot-static/src/js/vendor/min/lodash.min.js',
                './mot-static/src/js/vendor/min/backbone-min.js',
                './mot-static/src/js/vendor/min/handlebars.1.3.0.min.js',
                './mot-static/src/js/vendor/min/showdown.min.js',
                './mot-static/src/js/vendor/min/moment.min.js'
            ],
            dest: './mot-static/compiled/js/libraries.min.js'
        },
        lt_ie9: {
            src: [
                './mot-static/src/js/vendor/min/html5.min.js',
                './mot-static/src/js/vendor/min/respond.min.js'
            ],
            dest: './mot-static/compiled/js/lt.ie9.min.js'
        },
        enforcement: {
            src: [
                './node_modules/jsface/jsface.js',
                './mot-static/src/js/enforcement/global.functions.js',
                './mot-static/src/js/enforcement/vehicle/vehicle-search.js',
                './mot-static/src/js/enforcement/vehicle/vehicle-result.js',
                './mot-static/src/js/enforcement/event/event-list.js',
                './mot-static/tests/js/casper/public/js/classes.js'
            ],
            dest: './mot-static/compiled/js/enforcement.js'
        },
        dvsa: {
            src: [
                './mot-static/public/js/lib/dvsa.js',
                './mot-static/public/js/lib/dvsa/utils.js',
                './mot-static/public/js/lib/dvsa/toggle.js',
                './mot-static/public/js/lib/dvsa/selectToggle.js',
                './mot-static/public/js/lib/dvsa/criteriaValidation.js'
            ],
            dest: './mot-static/compiled/js/dvsa.js'
        }
    });
};