/**
 * Created by freakazo on 22/04/15.
 */

//Gannt Chart For

$(document).ready(function () {
  $(".gannt-panel").height($("#gannt-container").height());
    console.log(".gannt-panel height:" + $(".gannt-panel").height());
    updateDateRanges();
})

function updateDateRanges() {

    var size = 4;

    var heightOffset = $("#gannt-table td").height() + 16;
    var positionTrack = heightOffset / 2 - 14
    $(".gannt-date-range").each(function(index, value) {

        var dateStart = (((new Date( $(".gannt-task-start", this).attr('datetime'))) .getTime()) - Date.now()) / 1000 / 60 / 60 / 24;
        var dateEnd = (((new Date( $(".gannt-task-end", this).attr('datetime'))) .getTime()) - Date.now()) / 1000 / 60 / 60 / 24;
        $(this).css({left: size * dateStart, width: size * dateEnd + dateStart*size, top: positionTrack });
        positionTrack += heightOffset;
    });
}