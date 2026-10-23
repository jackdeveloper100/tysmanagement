/**
 * Created by alex on 8/15/16.
 */

var updateHours = function () {
    var subJobCache = {};
    var weeklyStartWeek = 0;

    var initJobIdUpdateHandler = function () {
        $('#taskform-job_id, #weeklytaskform-job_id').change(function () {
            var val = $(this).val()
            if (val.length > 0) {
                if (subJobCache[$(this).val()]) {
                    updateSubJobField(subJobCache[val]);
                } else {
                    $.getJSON('get-sub-jobs?job=' + $(this).val() + '&only_active=' + $('meta[name="isNewRecord"]').attr('content'), {}, function (data) {
                        subJobCache[val] = data;
                        if ($('#taskform-job_id').val() == val || $('#weeklytaskform-job_id').val() == val)
                            updateSubJobField(data);
                    })
                }

            } else {
                updateSubJobField([]);
            }
        });
    };

    var updateSubJobField = function (data) {
        $('#taskform-sub_job_id, #weeklytaskform-sub_job_id').empty();
        var options = '<option value=""></option>';
        $.each(data, function (key, item) {
            options += '<option value="' + key + '">' + item + '</option>';
        });
        $('#taskform-sub_job_id, #weeklytaskform-sub_job_id').html(options);
        $('#taskform-sub_job_id, #weeklytaskform-sub_job_id').trigger("chosen:updated");
        showOrHideSubJob();
    };

    var showOrHideSubJob = function () {
        if ($('#taskform-sub_job_id option, #weeklytaskform-sub_job_id option').length == 1) {
            $('.form-group.field-taskform-sub_job_id, .form-group.field-weeklytaskform-sub_job_id').hide();
        } else {
            $('.form-group.field-taskform-sub_job_id, .form-group.field-weeklytaskform-sub_job_id').show();
        }
    };

    var initWeekly = function () {
        updateDates(moment().startOf('week'));

        $('.add-weekly .prev').click(function (e) {
            e.preventDefault();

            weeklyStartWeek -= 1;
            updateDates(moment().startOf('week').add(7 * weeklyStartWeek, 'days'));
        });
        $('.add-weekly .next').click(function (e) {
            e.preventDefault();

            weeklyStartWeek += 1;
            updateDates(moment().startOf('week').add(7 * weeklyStartWeek, 'days'));
        });
    };

    var updateDates = function (startDate) {
        if (typeof times === 'undefined')
            return;
        var currentDate = startDate;

        for (var i = 0; i <= 6; i++) {
            currentDate = currentDate.add(1, 'days');
            var $day = $('.week .day[data-id="' + (i + 1) + '"]');
            if (times[currentDate.format('YYYY-MM-DD')]) {
                $day.find('.logged span').html(times[currentDate.format('YYYY-MM-DD')]);
            } else {
                $day.find('.logged span').html(0);
            }
            if (currentDate.format('MM/DD/YY') == moment().format('MM/DD/YY')) {
                $day.find('h3').html(currentDate.format('MM/DD/YY'));
                $day.addClass('today');
            } else {
                $day.find('h3').html(currentDate.format('MM/DD/YY'));
                $day.removeClass('today');
            }
            $day.find('.hidden_date').val(currentDate.format('MM/DD/YY'));
        }
    };

    var initAdvancedSearch = function () {
        $('#advanced_client').on('change', function (e) {
            var options = '';
            var val = $(this).val();
            $.each(jobs, function (index, item) {
                if (!val || item.client == val) {
                    options = options + '<option value="' + item.id + '">' + item.name + '</option>';
                }
            });
            $('#advanced_job').html(options).trigger('chosen:updated');
        });

        $('#advanced_choose').click(function (e) {
            e.preventDefault();

            var val = $('#advanced_job').val();

            $('#advancedSearch').modal('hide');
            $('#taskform-job_id option[value="' + val + '"], #weeklytaskform-job_id option[value="' + val + '"]').prop('selected', true);
            $('#taskform-job_id, #weeklytaskform-job_id').trigger('chosen:updated');

            if (val.length > 0) {
                if (subJobCache[val]) {
                    updateSubJobField(subJobCache[val]);
                } else {
                    $.getJSON('get-sub-jobs?job=' + val, {}, function (data) {
                        subJobCache[val] = data;
                        if ($('#taskform-job_id').val() == val || $('#weeklytaskform-job_id').val() == val)
                            updateSubJobField(data);
                    })
                }

            } else {
                updateSubJobField([]);
            }
        })
    };

    var initDescriptionTemplates = function () {
        $('.description-templates a').click(function (e) {
            e.preventDefault();

            $('#taskform-description, #weeklytaskform-description').html($(this).data('content'));
        })
    };

    return {
        init: function () {
            initJobIdUpdateHandler();
            showOrHideSubJob();
            initWeekly();
            initAdvancedSearch();
            initDescriptionTemplates();
        }
    };
}();
updateHours.init();