/**
 * Created by alex on 8/23/16.
 */

var projectList = function () {
    var initStatusFormAutoSubmit = function () {
        $('.status_form select, .status_form input').change(function () {
            $(this).closest('form').submit();
        })
    };

    var initFavorite = function () {
        $('.favorite').click(function (e) {
            e.preventDefault();

            var tmp = this;
            $.get('/tracker/jobs/toggle-favorite?id=' + $(this).data('id')).done(function () {
                $(tmp).toggleClass('active');
            });
        });
    };
    return {
        init: function () {
            initStatusFormAutoSubmit();
            initFavorite();
        }
    };
}();
$(document).ready(function () {
    projectList.init();
});