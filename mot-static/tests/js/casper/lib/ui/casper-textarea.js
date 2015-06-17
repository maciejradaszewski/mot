var CasperTextArea = jsface.Class({
    constructor: function(selector, name, test) {
        this.selector = selector;
        this.name = name;
        this.test = test;
    },
    value: function() {
        return casper.evaluate(function(selector) {
            return $(selector+' option:selected')  .text().trim();
        }, this.selector);
    },
    enabled: function() {
        return casper.evaluate(function(selector) {
            return $(selector).prop('disabled') === false;
        }, this.selector);
    },
    assertValue: function(value) {
        this.test.assertEqual(this.value(), value, this.name+' value should equal "'+value+'"');
        return this;
    },
    assertEnabled: function() {
        this.test.assertEqual(this.enabled(), true, this.name+' should be enabled');
        return this;
    },
    assertDisabled: function() {
        this.test.assertEqual(this.enabled(), false, this.name+' should be disabled');
        return this;
    }
});

module.exports = CasperTextArea;