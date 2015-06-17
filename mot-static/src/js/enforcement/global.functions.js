/**
 * Client side HTML entity escaping.
 *
 * @param string
 * @returns string
 */
function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': '&quot;',
            "'": '&#39;',
            "/": '&#x2F;'
        }[s];
    });
}


/**
 * (G)global (F)unctions
 */
var GF = new (function () {

    // for screens already using the file level function
    this.escapeHtml = escapeHtml;

    /**
     * Make it so that data in id1 disables id2 and vice versa, this is a very
     * common requirement on a lot of the screens.
     *
     * @param id1 string
     * @param id2 string
     */
    this.uiEitherOr = function(id1, id2) {
        var $eId1 = $(id1),
            $eId2 = $(id2);

        $eId1.bind({
            keyup: function () {
                $eId2.prop('disabled', $(this).val().length);
            },
            blur: function() {
                if ($eId1.val() != '') {
                    $eId2.prop('disabled', 'disable');
                }
            }
        });

        $eId2.bind({
            keyup: function () {
                $eId1.prop('disabled', $(this).val().length);
            },
            blur: function() {
                if ($eId2.val() != '') {
                    $eId1.prop('disabled', 'disable');
                }
            }
        });
    }
});
