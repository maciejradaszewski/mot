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
    }

    toggle.prototype.showElement = function() {
        DVSA.utils.removeClass(this.target, 'hidden');
    }

    toggle.prototype.hideElement = function() {
        DVSA.utils.addClass(this.target, 'hidden');
    }

    toggle.prototype.checkState = function() {
        if (this.trigger.checked === true) {
            this.showElement();
        } else {
            this.hideElement();
        }
    }

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
    }

    return toggle;
}(DVSA));
