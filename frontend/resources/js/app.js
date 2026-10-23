/**
 * Created by alex on 8/16/16.
 */

$(document).ready(function () {
    autosize($('textarea[data-autosize]'));

    $('[data-toggle="fancybox"]').fancybox({
        prevEffect: 'none',
        nextEffect: 'none',

        arrows: false,
        nextClick: true,

        autoCenter: true,

        helpers: {},
        width: "100%",
        height: 870,
        autoSize: false,
        fitToView: false,
        scrolling: 'auto',
        afterClose: function () {
            $('.zoomContainer').remove();
        }
    });
});
