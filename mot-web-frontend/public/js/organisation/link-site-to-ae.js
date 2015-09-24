var substringMatcher = function (strs) {
    return function findMatches(q, cb) {
        var matches = [];
        var substrRegex = new RegExp(q, 'i');

        $.each(strs, function (i, str) {
            if (substrRegex.test(str)) {
                matches.push({site: str});
            }
        });
        cb(matches);
    };
};

$(document).ready(function () {
    $('#siteNumber').typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 1,
            limit: 5
        },
        {
            name: 'siteNumber',
            displayKey: 'site',
            limit: '5',
            source: substringMatcher(sites)
        }
    );
});