var CasperSelect = jsface.Class({
    constructor: function(selector, name, test) {
        this.selector = selector;
        this.name = name;
        this.test = test;
    },
    index: function(position) {
        if (typeof position == 'number') {
            casper.evaluate(function(selector, position) {
                $(selector+' :nth-child('+(position+1)+')').prop('selected', true);
                $(selector).trigger('change');
            }, this.selector, position);
            return this;
        }
        return casper.evaluate(function(selector) {
            return $(selector+' option:selected').index();
        }, this.selector, position);
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
    optionsLength: function() {
        return casper.evaluate(function(selector) {
            return $(selector+' option').length;
        }, this.selector);
    },
    assertIndex: function(index) {
        this.test.assertEqual(this.index(), index, this.name+' index should equal '+index);
        return this;
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
    },
    assertOptionLength: function(length) {
        this.test.assertEqual(this.optionsLength(), length, this.name+' should have '+length+' option'+ (length > 1 ? 's, ':', ')+this.optionsLength()+' found.');
        return this;
    }
});

module.exports = CasperSelect;