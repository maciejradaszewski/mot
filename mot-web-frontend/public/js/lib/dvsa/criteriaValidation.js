/*

DVSA Password submodule

*/

DVSA.criteriaValidation = (function(DVSA) {
    'use strict';

    var criteria = function criteria(options) {

        var options = options || {};

        this.trigger = options.trigger || null;
        this.scope = options.criteria || null;

        this.stateNeutral = 'criteria__criterion';
        this.stateFail = 'criteria__criterion--has-failed';
        this.statePass = 'criteria__criterion--has-passed';

        this.criteria = [];

        this.getCriteria();
        this.bindEvents();

    };

    criteria.prototype.getCriteria = function() {

        var criteriaElements = this.scope.querySelectorAll('[data-criteria]');

        for (var i = 0; i < criteriaElements.length; i++) {
            var criteriaName = criteriaElements[i].getAttribute('data-criteria');
            var criteriaParam = criteriaElements[i].getAttribute('data-criteria-param') || null;
            this.criteria.push({
                name: criteriaName,
                param: criteriaParam,
                state: criteriaElements[i].className,
                element: criteriaElements[i]
            });
        }
    }

    criteria.prototype.bindEvents = function() {
        var self = this;
        if (window.addEventListener) {
            this.trigger.addEventListener("keyup", function() {
                self.checkCriteria();
            });
            this.trigger.addEventListener("paste", function() {
                self.checkCriteria();
            });
        } else if (window.attachEvent) {
            this.trigger.attachEvent("onkeyup", function() {
                self.checkCriteria();
            });
            this.trigger.attachEvent("onpaste", function() {
                self.checkCriteria();
            });
        }
    }

    criteria.prototype.checkCriteria = function() {

        var currentValue = this.trigger.value;

        for (var i = 0; i < this.criteria.length; i++) {
            var criteriaName = this.criteria[i].name;
            var criteriaParam = this.criteria[i].param;

            var status = this[criteriaName](currentValue, criteriaParam);

            this.decorateElement(i, status);

        }
    }

    criteria.prototype.decorateElement = function(i, state) {
        if (this.criteria[i].state != state) {
            this.criteria[i].state = state;
            this.criteria[i].element.className = state;
        }
    }

    criteria.prototype.hasMixedCase = function(val) {
        var cssClass = this.stateNeutral;

        if ((/^(?=.*[a-z])(?=.*[A-Z]).+$/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    }

    criteria.prototype.minLength = function(val, param) {
        var cssClass = this.stateNeutral;
        var regEx = new RegExp("^.{" + param + ",}$");

        if ((regEx.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    }

    criteria.prototype.notMatch = function(val, param) {
        var cssClass = this.stateNeutral;
        var val_lc = val.toLowerCase();
        var param_lc = param.toLowerCase();


        if (val_lc === param_lc && val_lc.length === param_lc.length) {
            cssClass = this.stateFail;
        } else if (val_lc != param_lc.substring(0, val_lc.length)) {
            cssClass = this.statePass;
        }

        return cssClass;

    }

    criteria.prototype.hasNumeric = function(val) {
        var cssClass = this.stateNeutral;

        if ((/[0-9]/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    }

    criteria.prototype.hasUpperCase = function(val) {
        var cssClass = this.stateNeutral;

        if ((/[A-Z]/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    }

    criteria.prototype.hasLowerCase = function(val) {
        var cssClass = this.stateNeutral;

        if ((/[a-z]/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    }

    return criteria;
}(DVSA));
