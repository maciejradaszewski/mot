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
    }

    var removeClass = function(el, cssClass) {
        var cn = el.className;
        var rxp = new RegExp("\\s?\\b" + cssClass + "\\b", "g");
        cn = cn.replace(rxp, '');
        el.className = cn;
    }

    return {
        addClass: addClass,
        removeClass: removeClass
    };
}(DVSA));
