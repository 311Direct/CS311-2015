/**
 * Created by freakazo on 13/05/15.
 */

var cocomo2 = {
    showQ: function (qtns) {
        var $qdiv = $(".cocomo2-questions");

        for (var qIndex = 0; qIndex < qtns.length; qIndex++) {
            var q = qtns[qIndex];


            $qdiv.append("<h4>" + q.question + "</h4>");

            if ('type' in q) {
                if (q.type === "degree") {

                    var start = 0;
                    var end = 6;
                    if ('degree_start' in q)
                        start = q.degree_start;
                    if ('degree_end' in q)
                        end = q.degree_end;

                    var $select = $("<select></select>");
                    $.each(this.scaleFactorOptions, function (i, val) {
                        if (i >= start && i <= end) {
                            $select.append($("<option>", {
                                value: i,
                                text: val
                            }));
                        }
                    });
                    $qdiv.append($select);
                }
            }
        }

        $qdiv.append("<h4>What's the estimated thousands of lines of code?</h4>");
        $qdiv.append( $("<input>", {
            class: 'col-xs-12',
            type: 'number'
        }));

    },
    scaleFactorOptions: ["Extra Low", "Very Low", "Low", "Nominal", "High", "Very High", "Extra High"],

    scaleFactorValues: [
        [6.2, 4.96, 3.72, 2.48, 1.24, 0.0],
        [5.07, 4.05, 3.04, 2.03, 1.01, 0.0],
        [7.07, 5.65, 4.24, 2.83, 1.41, 0.0],
        [5.48, 4.38, 3.29, 2.19, 1.1, 0.0],
        [7.8, 6.24, 4.68, 3.12, 1.56, 0.0]
    ],

    effortMult: [
        [0.49, 0.6, 0.83, 1.0, 1.33, 1.91, 2.72],
        [0, 0, 0.95, 1.0, 1.07, 1.15, 1.24],
        [0, 0, 0.87, 1.0, 1.29, 1.81, 2.61],
        [2.12, 1.62, 1.26, 1, 0.83, 0.63, 0.5],
        [1.59, 1.33, 1.12, 1, 0.87, 0.74, 0.62],
        [1.43, 1.3, 1.1, 1, 0.87, 0.73, 0.62],
        [0, 1.43, 1.14, 1, 1, 1, 0]
    ],

    calculate: function () {

        var scaleFactorV = 0;
        var effortMultiplier = 1;
        var $qstions = $(".cocomo2-questions > select");

        var sfv = this.scaleFactorValues;
        var em = this.effortMult;
        $.each($qstions, function (i, q) {
            if (i < sfv.length) {
                scaleFactorV += sfv[i][q.selectedOptions[0].value - 1]; // .value -1 since all sf has no  Extra Low option.
            }
            else if (i < sfv.length + em.length){
                console.log("sup" + (i - sfv.length));
                effortMultiplier *= em[i - sfv.length][q.selectedOptions[0].value];
            }
        });
        console.log(scaleFactorV*0.01+0.91);
        console.log(effortMultiplier);
        return 2.94*Math.pow($('.cocomo2-questions input')[0].value, scaleFactorV*0.01+0.91)*effortMultiplier;
    }
};


$(document).ready(function () {
    $('.btn-cocomo2-questionnaire-start').on('click', function () {
        // Hide the "we can calculate for you" section
        $(this).attr('disabled', 'disabled');
        $.getJSON('js/cocomo2-questions.json', function (qtns) {
            $('.cocomo-2-not-calculated, .btn-cocomo2-questionnaire-start, .cocomo2').css('opacity', 0);

            // Show questionnaire
            setTimeout(function () {
                $('.cocomo-2-not-calculated, .btn-cocomo2-questionnaire-start').slideUp('slow');
                cocomo2.showQ(qtns);
                $('.cocomo2-questionnaire').slideDown(1000);

            }, 1000);	// 3000 = CSS transition opacity duration


        });
    });

    $('.btn-calc-cocomo-2').on('click', function() {
        var pm = Math.trunc(cocomo2.calculate() * 10) / 10;

        $('.cocomo2-results b').after(pm + ".");

        $('.cocomo2-questionnaire').slideUp('slow');

        setTimeout(function () {
            $('.cocomo2-results').slideDown('slow');
        }, 1000);
    })
});