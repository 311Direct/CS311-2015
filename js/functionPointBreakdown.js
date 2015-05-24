/**
 * Created by freakazo on 13/05/15.
 */

var FP_tasks = {};

$(document).ready(function () {
    //var table = $(".COCOMO2");
    var milestones = [];
    var tasks;
    $('.btn-fp-start').on('click', function () {
        // Hide the "we can calculate for you" section
        //$(this).attr('disabled', 'disabled');
        $('.fp2-not-calculated, .btn-fp-start').css('opacity', 0);

        // Show questionnaire
        setTimeout(function () {
            $('.fp2-not-calculated, .btn-fp-start').slideUp('slow');
            $.ajax({
                url: 'php/DirectPHPApi.php',
                method: 'post',
                data: {
                    'action': 'FUNCTION_POINTS',
                    id: loadContent.itemId()
                }
            }).done(function (response) {
                tasks = JSON.parse(response).payload;
                FP_tasks = tasks;
                var $fPQuestions = $('.function-point-q');
                var typeSelect = '<select><option value="ILF">file where data is stored</option>\
            <option value="EIF">referenced external interface</option>\
        <option value="EI">use or process input</option>\
        <option value="EO">output involving calculations</option>\
        <option value="EQ">output not involving calculations</option></select>';

                var compSelect = '<select><option value="0">Low</option>\
        <option value="1">Medium</option>\
        <option value="2">High</option></select>';

                for (var i = 0; i < tasks.length; i++) {
                    $fPQuestions.append('<li><div data-taskId=\"' + tasks[i].taskId + '\">The task \'' + tasks[i].taskTitle + "\' is a " + typeSelect + " with " + compSelect + " complexity.</div></li>");
                }
                $('.function-point-q').slideDown(1000);
            }).fail(function () {
                console.log('Failed to execute action: FUNCTION_POINTS');
            });
        }, 1000);	// 3000 = CSS transition opacity duration
    });
    $('.btn-function-point-calc').on('click', function () {
        if (tasks != undefined) {
            $.each(tasks, function (i, t) {
                var $fp_input = $('div[data-taskId=' + t.taskId + '] select');
                t['funcPts'] = cocomoVisualization.albrechtCplMultis[$fp_input[0].value][$fp_input[1].value];
                if (t.milestoneId in milestones) {
                    milestones[t.milestoneId].tasks = milestones[t.milestoneId].tasks.concat(t);
                }
                else {
                    milestones[t.milestoneId] = {tasks: [t], title: t.milestoneTitle};
                }
            });
            if (milestones != undefined) {
                var milestones_total = 0;
                var milestonesResults = [];
                var counter = 0;
                for(var i in milestones) {
                    var milestone = milestones[i];
                    if(!milestone.hasOwnProperty("tasks")) {
                        continue;
                    }
                    var milestoneFPTotal = 0;
                    for (var iT = 0; iT < milestone.tasks.length; iT++) {
                        milestoneFPTotal += milestone.tasks[iT].funcPts;
                    }
                    if ('title' in milestone) {
                        milestonesResults[counter] = '<li>Milestone \'' + milestone.title + '\': <b>' + milestoneFPTotal + '</b></li>';
                    }
                    else {
                        milestonesResults[counter] = '<li>Milestone id ' + i + ': <b>' + milestoneFPTotal + '</b></li>';
                    }

                    var ms_result = "<ul>";
                    for (iT = 0; iT < milestone.tasks.length; iT++) {
                        ms_result += '<li> Task \'' + milestone.tasks[iT].taskTitle + '\': <b>' + milestone.tasks[iT].funcPts + '</b></li>';
                    }
                    ms_result += "</ul>";
                    milestonesResults[counter] += ms_result;
                    milestones_total += milestoneFPTotal;
                    counter++;
                }

                $('.fp-milestones').append('<li>All milestones: <b>' + parseInt(milestones_total) + '</b></li>');
                var result = "<ul>";
                $.each(milestonesResults, function (i, m) {
                    result += m;
                });
                result += "</ul>";
                $('.fp-milestones').append(result);
            }
        }
        $('.function-point-q').css('opacity', 0);
        setTimeout(function(){
            $('.function-point-q').slideUp('slow');
            $('.fp-results .fp-summary').slideDown('slow');
        }, 1000);
    });
});


