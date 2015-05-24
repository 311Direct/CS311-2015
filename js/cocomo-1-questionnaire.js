var cocomo1 = {
	show: function(qtns) {
		// For each question
		for (var qIndex = 0; qIndex < qtns.length; qIndex++) {
			var q = qtns[qIndex];
			$q = $('<div/>', {
				class: 'col-xs-12 question'
			});
			
			// If it is question that determines if its semi-attached or organic system
			if ('questionClass' in q) {
				// Mark what the question is for, for computation later on
				$q.addClass(q.questionClass);
			}
			
			// Show question
			$('<h4/>', {
				class: 'col-xs-12',
				text: q.question
			}).appendTo($q);

			// Build answer container			
			$a = $('<div/>', {
				class: 'col-xs-12 answer'
			});			
			
			// If question has answers
			if ('answers' in q) {			
				// For each answer
				for (var aIndex = 0; aIndex < q.answers.length; aIndex++) {
					var ans = q.answers[aIndex];
					var weightings = ans.weightings;
					if (weightings === undefined) weightings = '';
					
					// Show radio button
					$radio = $('<input/>', {
						'class': 'col-xs-2 answer-radio',
						'type': 'radio',
						'data-weightings': JSON.stringify(weightings),
						'value': '',
						'name': qIndex
					});
					
					// Show title
					$ansTitle = $('<span/>', {
						'class': 'col-xs-9 answer-title',
						'text': ans.title
					});

					$a.append($radio).append($ansTitle);
				// Endfor
				}
			// If not, then just show a textbox (i.e.: a "pre-emptive thinking question")
			} else {
				$('<input/>', {
					class: 'col-xs-12',
					type: 'text'
				}).appendTo($a);
			// Endif
			}
			
			// Show question & answers
			if ('forNonEmbeddedSys' in q) {
				$qTargetContainer = $('.cocomo1-questionnaire > .questions > .for-embedded-sys');
			} else {
				$qTargetContainer = $('.cocomo1-questionnaire > .questions > .for-not-embedded-sys');
			}
			$q.append($a).appendTo($qTargetContainer);
		// Endfor
		}
		
		// Show questionnaire
		$('.cocomo1-questionnaire').slideDown(4000);
	},
	newTechDetail: function() {
		$techDetail = $('<div/>', {
			class: 'tech-detail'
		});
		
		$typesSel = $('<select/>');
		$.getJSON('js/cocomo-1-tech-details.json', function(details) {
			// Create "details" container
			$detail = $('<div/>', {
				class: 'col-xs-12 detail'
			}).append('A <input type="text"/><span> which is a </span>');
			
			$types = $('<select/>', {
				class: 'types'
			}).appendTo($detail);
			
			$detail.append('<span> and is </span>');
			
			$complexities = $('<select/>', {
				class: 'complexities'
			}).appendTo($detail);
			
			// For each type
			var types = details.types;
			for (var tIndex = 0; tIndex < types.length; tIndex++) {
				// Show type
				var type = types[tIndex];
				$('<option/>', {
					text: type.title,
					value: type.type
				}).appendTo($types);
			// Endfor
			}
			
			// For each complexity
			var cpls = details.complexities;
			for (var cIndex = 0; cIndex < cpls.length; cIndex++) {
				// Show complexity
				var cpl = cpls[cIndex];
				$('<option/>', {
					text: cpl.title,
					value: cpl.level 
				}).appendTo($complexities);
			// Endfor
			}

			// Add container to DOM
			$detail.css({
				'height': 0,			
				'opacity': 0
			}).appendTo('.tech-details').animate({
				'height': '135px'
			}, {
				duration: 1000,
				queue: false
			}).animate({
				'opacity': 1
			}, {
				duration: 1000,
				queue: false
			});
		});
	}
};

$(document).ready(function() {
	// When the user has chosen to take the questionnaire
	$('.btn-cocomo1-questionnaire-start').on('click', function() {
		// Hide the "we can calculate for you" section
		$(this).attr('disabled', 'disabled');
		$.getJSON('js/cocomo-1-questions.json', function(qtns) {
			$('.cocomo-1-info-that-can-be-calculated, .btn-cocomo1-questionnaire-start, .cocomo1').css('opacity', 0) 
			// Show questionnaire			
			setTimeout(function() {
				$('.cocomo-1-info-that-can-be-calculated, .btn-cocomo1-questionnaire-start, .cocomo1').slideUp('slow');
				cocomo1.show(qtns);
			}, 3000);	// 3000 = CSS transition opacity duration
		});
	});
	
	// Initially, allow the user to set 1 technical detail, & allow them to add additional technical details
	cocomo1.newTechDetail();
	$('.btn-add-tech-detail').on('click', function() {
		cocomo1.newTechDetail();	
	});
	
	// Calculate COCOMO, when user is ready
	$('.btn-calc-cocomo-1').on('click', function() {
		cocomoVisualization.do();
	});
});