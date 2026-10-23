/**
 * Created by alex on 9/8/16.
 */
var Artcomp = function () {
    var initInlineEdit = function () {
        $('.artcomp h4:not(.no_edit)').editable();
    };

    var initRevisionUpload = function () {
        $('.artcomp a.upload_revision').click(function (e) {
            e.preventDefault();
            e.stopPropagation();

            $('#artworkcomprevisionform-item_id').val($(this).data('item'));
            $($(this).attr('href')).modal();
        });
    };

    var initDropFiles = function () {

            var $fileselect = $(".artitem");

            $(".artitem .upload-container").dropzone({
                init: function () {
                    this.on("queuecomplete", function () {
                        location.reload();
                    });
                }
            });

            var xhr = new XMLHttpRequest();
            if (xhr.upload) {


                var over = {};
                $fileselect.bind({
                    dragover: function (ev) {
                        ev.preventDefault(); // needed for IE
                        $(this).addClass('drag');
                        over[$(this).data('id')] = true;
                    },
                    dragleave: function () {
                        var t = this;
                        over[$(this).data('id')] = false;
                        setTimeout(function () {
                            if (!over[$(t).data('id')])
                                $(t).removeClass('drag');
                        }, 100);

                    },
                    drop: function () {
                        $(this).addClass('drop');
                    }
                });
                $(".artitem .upload-container").bind({
                    dragover: function (ev) {
                        ev.preventDefault(); // needed for IE
                        $(this).closest('.artitem').addClass('drag');
                        over[$(this).closest('.artitem').data('id')] = true;
                    },
                    dragleave: function () {
                        var t = this;
                        over[$(this).closest('.artitem').data('id')] = false;
                        setTimeout(function () {
                            if (!over[$(t).closest('.artitem').data('id')])
                                $(t).closest('.artitem').removeClass('drag');
                        }, 100);
                    },
                    drop: function () {
                        $(this).closest('.artitem').addClass('drop');
                    }
                });
            }
        }
        ;

    return {
        init: function () {
            initInlineEdit();
            initRevisionUpload();
            initDropFiles();
        }
    };
}();

Dropzone.options.revisionUploadForm = {
    init: function () {
        this.on("queuecomplete", function () {
            location.reload();
        });
    }
};
Dropzone.options.compForm = {
    init: function () {
        this.on("queuecomplete", function () {
            location.reload();
        });
    }
};
$(document).ready(function () {
    Artcomp.init();
});