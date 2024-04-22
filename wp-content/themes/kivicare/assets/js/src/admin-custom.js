"use strict";
function notify_wordpress(e) {
    e = {
        action: "iqonic_dismiss_notice",
        data: e
    };
    jQuery.post(ajaxurl, e)
}
jQuery, jQuery(document).ready(function () {
    jQuery(".iqonic-notice-dismiss").click(function (e) {
        e.preventDefault(),
            jQuery(this).parent().parent(".iqonic-notice").fadeOut(600, function () {
                jQuery(this).parent().parent(".iqonic-notice").remove()
            }), notify_wordpress(jQuery(this).data("msg"))
    })
});

