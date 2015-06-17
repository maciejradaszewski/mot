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
