/**
 * Created by alex on 8/17/16.
 */

var jobReports = function () {

    var initProjectExportButton = function () {
        $('button.export-projects').click(function (e) {
            e.preventDefault();

            var form = $(this).closest('form');
            form
                .attr('action', '/accounting/reports/generate-excel-projects')
                .submit()
                .attr('action', '');
        })
    };
    var initExportButton = function () {
        $('button.export').click(function (e) {
            e.preventDefault();

            var form = $(this).closest('form');
            form
                .attr('action', '/accounting/reports/generate-excel')
                .submit()
                .attr('action', '');
        })
    };

    var subJobCache = {};

    var initJobIdUpdateHandler = function () {
        $('#job').change(function () {
            var val = $(this).val()
            if (val.length > 0) {
                if (subJobCache[$(this).val()]) {
                    updateSubJobField(subJobCache[val]);
                } else {
                    $.getJSON('/tracker/tasks/get-sub-jobs?job=' + $(this).val(), {}, function (data) {
                        subJobCache[val] = data;
                        if ($('#job').val() == val)
                            updateSubJobField(data);
                    })
                }

            } else {
                updateSubJobField([]);
            }
        });
    };

    var updateSubJobField = function (data) {
        var $obj = $('#sub_job_id');
        $obj.empty();
        var options = '<option value=""></option>';
        $.each(data, function (key, item) {
            options += '<option value="' + key + '">' + item + '</option>';
        });
        $obj.html(options);
        $obj.trigger("chosen:updated");
        showOrHideSubJob();
    };

    var showOrHideSubJob = function () {
        if ($('#sub_job_id option').length === 1) {
            $('.sub_job_container').hide();
        } else {
            $('.sub_job_container').show();
        }
    };

    return {
        init: function () {
            initExportButton();
            initProjectExportButton();
            initJobIdUpdateHandler();
            showOrHideSubJob();
        }
    };
}();
jobReports.init();