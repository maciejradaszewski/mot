var DateGroup = {};

(function () {
    DateGroup.validateDate = function ($eForm, fieldPrefix, fieldName) {
        var currentYear = new Date().getFullYear(),
            rangeMsg = '';

        rangeMsg = "'" + fieldName + "' Day must be in the range 1 - 31.";
        $eForm.find("#" + fieldPrefix + "-Day").rules('add', {
            required: true,
            min: 1,
            max: 31,
            messages: {
                required: "'" + fieldName + "' Day is required.",
                min: rangeMsg,
                max: rangeMsg
            }
        });

        rangeMsg = "'" + fieldName + "' Month must be in the range 1 - 12.";
        $eForm.find("#" + fieldPrefix + "-Month").rules('add', {
            required: true,
            min: 1,
            max: 12,
            messages: {
                required: "'" + fieldName + "' Month is required.",
                min: rangeMsg,
                max: rangeMsg
            }
        });

        $eForm.find("#" + fieldPrefix + "-Year").rules('add', {
            required: true,
            max: currentYear,
            messages: {
                required: "'" + fieldName + "' Year is required.",
                max: "'" + fieldName + "' Year must be current year or less."
            }
        });
    }
})();