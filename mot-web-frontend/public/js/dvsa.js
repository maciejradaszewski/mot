/**
 * This is a generated file, please do not edit.
 * created on Wednesday, April 8th, 2015, 10:11:26 AM
 */

/*

DVSA Module

*/

var DVSA = (function() {
    'use strict';

    var version = '0.1.0';

    return {
        version: version
    };
}());

/*

DVSA.utils submodule

*/

DVSA.utils = (function(DVSA) {
    'use strict';

    var addClass = function(el, cssClass) {
        if (el.className.indexOf(cssClass) != -1) {
            return;
        }
        el.className += ' ' + cssClass;
    };

    var removeClass = function(el, cssClass) {
        var cn = el.className;
        var rxp = new RegExp("\\s?\\b" + cssClass + "\\b", "g");
        cn = cn.replace(rxp, '');
        el.className = cn;
    };

    return {
        addClass: addClass,
        removeClass: removeClass
    };
}(DVSA));

/*

DVSA.toggle submodule

Browser Support:
- ie8+

*/

DVSA.toggle = (function(DVSA) {
    'use strict';

    var toggle = function toggle(options) {
        this.trigger = options.trigger || null;
        this.target = options.target || null;
        this.bindEvents();
        this.checkState();
    };

    toggle.prototype.init = function() {
        this.checkState();
    };

    toggle.prototype.showElement = function() {
        DVSA.utils.removeClass(this.target, 'hidden');
    };

    toggle.prototype.hideElement = function() {
        DVSA.utils.addClass(this.target, 'hidden');
    };

    toggle.prototype.checkState = function() {
        if (this.trigger.checked === true) {
            this.showElement();
        } else {
            this.hideElement();
        }
    };

    toggle.prototype.bindEvents = function() {
        var self = this;
        if (window.addEventListener) {
            this.trigger.addEventListener("click", function() {
                self.checkState();
            });
        } else if (window.attachEvent) {
            this.trigger.attachEvent("onclick", function() {
                self.checkState();
            });
        }
    };

    return toggle;
}(DVSA));

/*jslint browser: true, evil: false, plusplus: true */
/*global DVSA, $, console */

/*
    TODO:

        1. Mustard test - and swap to classList method (!target.className.replace)
        2. Refactor closure
        3. Investigate/implement .bind
        4. Keep element state in scope to reduce repaint (nice to have)
        5. Test the target element exists: document.querySelector('#' + targetId); 
        6. Write unit tests
        7. Construct 'targetVals' array once (not on every change)
        8. Refactor clearData to The Observer Pattern (https://carldanley.com/js-observer-pattern/)
*/

DVSA.selectToggle = (function(DVSA) {

    'use strict';
    var init,
        selectToggle;
    

    var selectToggle = function selectToggle() {
        //this.init();
    };

    selectToggle.prototype.init = function(){
        var self = this;
        
        this.triggerElements = document.querySelectorAll("select[data-target]");

        for (var i=0; i < this.triggerElements.length; i++){
            (function () {
                var triggerEl = self.triggerElements[i];
                var targetId = triggerEl.getAttribute('data-target');
                var targetEl = document.querySelector('#' + targetId);  // Needs test
                var targetVal = triggerEl.getAttribute('data-target-value');

                // ARIA attributes
                triggerEl.setAttribute('aria-controls', targetId);

                if (window.addEventListener) {
                    triggerEl.addEventListener("keyup", function() {
                        self.checkState(triggerEl, targetEl, targetVal);
                    });
                    triggerEl.addEventListener("change", function() {
                        self.checkState(triggerEl, targetEl, targetVal);
                    });
                }

                self.checkState(triggerEl, targetEl, targetVal);
            }());
        };
    };

    selectToggle.prototype.checkState = function(triggerEl, targetEl, targetVal){
        
        var triggerMet = false;
        var targetVals = this.getTargetValues(targetVal);
        
        for (var i=0; i < targetVals.length; i++){
            if (triggerEl.value === targetVals[i]) {
                triggerMet = true;
            }
        }

        if (triggerMet === true) {
            this.showContent(triggerEl, targetEl);
        }else{
            this.hideContent(triggerEl, targetEl);
            this.clearData(targetEl);
        }
    };

    selectToggle.prototype.getTargetValues = function(targetVal){
        return targetVal.split(',');
    };

    selectToggle.prototype.clearData = function(target){
        var i;
        var inputList = target.querySelectorAll('input[type="text"]:not([value=""]), input[type="email"]:not([value=""]),input[type="password"]:not([value=""]),input[type="tel"]:not([value=""])');

        for (i = 0; i < inputList.length; ++i) {
            inputList[i].value = '';
        }
    };

    selectToggle.prototype.hideContent = function(trigger, target){
        //target.className = 'panel-indent toggle-content';
        target.className = target.className.replace(" toggle-content","");
        target.className += " toggle-content";
        // ARIA attributes
        target.setAttribute('aria-hidden', 'true');
        trigger.setAttribute('aria-expanded', 'false');
    };

    selectToggle.prototype.showContent = function(trigger, target){
        //target.className = 'panel-indent';
        target.className = target.className.replace(" toggle-content","");
        // ARIA attributes
        target.setAttribute('aria-hidden', 'false');
        trigger.setAttribute('aria-expanded', 'true');
    };

    return new selectToggle;

}(DVSA));

/*

DVSA Password submodule

*/

DVSA.criteriaValidation = (function(DVSA) {
    'use strict';

    var criteria = function criteria(options) {
        options = options || {};

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
    };

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
    };

    criteria.prototype.checkCriteria = function() {

        var currentValue = this.trigger.value;

        for (var i = 0; i < this.criteria.length; i++) {
            var criteriaName = this.criteria[i].name;
            var criteriaParam = this.criteria[i].param;

            var status = this[criteriaName](currentValue, criteriaParam);

            this.decorateElement(i, status);

        }
    };

    criteria.prototype.decorateElement = function(i, state) {
        if (this.criteria[i].state != state) {
            this.criteria[i].state = state;
            this.criteria[i].element.className = state;
        }
    };

    criteria.prototype.hasMixedCase = function(val) {
        var cssClass = this.stateNeutral;

        if ((/^(?=.*[a-z])(?=.*[A-Z]).+$/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    };

    criteria.prototype.minLength = function(val, param) {
        var cssClass = this.stateNeutral;
        var regEx = new RegExp("^.{" + param + ",}$");

        if ((regEx.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    };

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

    };

    criteria.prototype.hasNumeric = function(val) {
        var cssClass = this.stateNeutral;

        if ((/[0-9]/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    };

    criteria.prototype.hasUpperCase = function(val) {
        var cssClass = this.stateNeutral;

        if ((/[A-Z]/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    };

    criteria.prototype.hasLowerCase = function(val) {
        var cssClass = this.stateNeutral;

        if ((/[a-z]/.test(val))) {
            cssClass = this.statePass;
        }

        return cssClass;
    };

    return criteria;
}(DVSA));
