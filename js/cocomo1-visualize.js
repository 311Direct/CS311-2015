$.fn.applyWeightings = function(vars) {
	// Get weightings
	var weightings = JSON.parse($(this).attr('data-weightings'));
	// For each weighting
	for (var wIndex = 0; wIndex < weightings.length; wIndex++) {
		// Apply change to relevant variable
		var weighting = weightings[wIndex];
		vars[weighting.affects] += weighting.weight;
	}
}

var cocomoVisualization = {
	projectType: null,
	sysEmbedded: 'embedded',
	sysOrganic: 'organic',
	sysSemiDetached: 'semi-detached',
	effortApplied: null,
	devTime: null,
	peopleReq: null,
	smallTeamSizeUpperLimit: 11,
	slocFactor: Math.log(20000) / Math.log(15),
	kloc: null,
	effort: null,
	devTime: null,
	peopleReq: null,
	albrechtCplMultis: {		// Albrecht complexity multipliers table
		'EI': [3, 4, 6],
		'EO': [4, 5, 7],
		'EQ': [3, 4, 6],
		'ILF': [7, 10, 15],
		'EIF': [5, 7, 10]
	},
	cocomo81Consts: {
		'embedded': {
			'a': 3.6,
			'b': 1.2,
			'c': 2.5,
			'd': 0.32
		},
		'organic': {
			'a': 2.4,
			'b': 1.05,
			'c': 2.5,
			'd': 0.38
		},
		'semi-detached': {
			'a': 3,
			'b': 1.12,
			'c': 2.5,
			'd': 0.35
		}
	},
	do: function() {
		if (this.calc()) {
			this.show();
		}
	},
	calc: function() {
		// Before we do anything, ensure all questions are answered
		if (!this.allQAns()) return false;
		// Determine project type
		this.checkProjectType();
		// Determine KLOC
		this.getKloc();
		// Calculate effort using COCOMO81 function
		this.calcEffort();
		// Show graph
		return true;
	},
	allQAns: function() {
		// For all questions
		var allQAns = true;
		$('.question > .answer').each(function() {
			// If answer is text
			$ansTxt = $(this).children('input[type=text]');
			$ansRadio = $(this).children('input[type=radio]');
			if ($ansTxt.length) {
				// Ensure the user specified some details
				if (!$ansTxt.val().length) allQAns = false;
			// Else if answer is a series of radio buttons
			} else if ($ansRadio.length) {
				// Ensure at least 1 is selected
				if (!$ansRadio.is(':checked')) allQAns = false;
			// Else
			} else {
				// Log error that unknown answer type encountered
				console.log('checkEmbedded(): attempted to check if a question was answered, but encountered an unknown answer type');
				allQAns = false;
			// Endif
			}
			
			// In the interest of performance, stop as soon as a non-answered question is found
			if (!allQAns) {
				notify.fail('Please answer all COCOMO1 questionnaire questions');
				return false;
			}
		// Endfor
		});

		return allQAns;
	},
	checkProjectType: function() {
		if (!this.checkEmbedded()) this.checkOrgOrSemiDet();	
	},
	checkEmbedded: function() {
		var tightConstraints = 1, costlyChanges = 1;

		// For each question for determining if its an embedded system
		$('.for-embedded-sys > .question').each(function() {
			// If radio button answer
			$answers = $(this).children('.answer');
			if (!$answers.children('.answer-radio').length) return true;
			
			// Apply answer weighting from selected radio button
			var vars = {
				'tightConstraints': tightConstraints,
				'costlyChanges': costlyChanges
			};
			$answers.children('.answer-radio').applyWeightings(vars);		// Utilize JS version of "pass by reference"
			tightConstraints = vars.tightConstraints;
			costlyChanges = vars.costlyChanges;
		// Endfor
		});

		// Determine if project is for an embedded system
		if (tightConstraints >= 1) tightConstraints = true;
		else tightConstraints = false;
		if (costlyChanges >= 1) costlyChanges = true;
		else costlyChanges = false;

		var isEmbeddedSys = false;
		if (tightConstraints && costlyChanges) isEmbeddedSys = true;

		if (isEmbeddedSys) {
			this.projectType = this.sysEmbedded;
			return true;
		}
		return false;
	},
	checkOrgOrSemiDet: function() {
		// If NOT an embedded system, then determine if project is for organic or semi-detached system

		// Determine team size
		var teamSize = null;
		var numPeople = parseInt($('.people-in-project > .answer > input').val());
		var numTeams = parseInt($('.teams-in-project > .answer > input').val());
		var peoplePerTeam = numPeople / numTeams;
		if (peoplePerTeam < this.smallTeamSizeUpperLimit) {
			var teamSize = 'small';
		} else {
			var teamSize = 'medium';
		}

		// Determine if the teams have past experience in projects with flexible requirements
		var xpWithFlexibleReqs = null;
		$possibleAnswers = $('.experience-with-flexible-requirements > .answer > input');
		$possibleAnswers.each(function() {
			if ($(this).is(':checked')) {
				var selAnsData = JSON.parse($(this).attr('data-weightings'))[0];
				xpWithFlexibleReqs = selAnsData.weight;
				return false;
			}
		});

		// Its an organic system, IF small team size AND
		// high level of experience with flexible requirements. Otherwise
		// its a semi-detatached system
		if (teamSize == 'small' && xpWithFlexibleReqs == 'high') this.projectType = this.sysOrganic;
		else this.projectType = this.sysSemiDetached;
	},
	getKloc: function() {
		var funcPts = 0;
	
		// For each technical data supplied
		$('.tech-details > .detail').each(function() {
			// Get technical detail type & complexity level
			var type = $(this).children('.types').val();
			var complexityStr = $(this).children('.complexities').val();
			switch (complexityStr) {
				case 'low':
					var complexity = 0; 	// 0 = first element in Albrecht complexity multiplier array, which represents a function type's "low complexity" function point total
					break;
				case 'medium':
					var complexity = 1;
					break;
				case 'high':
					var complexity = 2;
					break;
				default:
					console.log('getKloc(): attempted to calculate function points, but encountered an unknown specified complexity for a user-given technical detail');
					break;										
			}
			
			// Calculate & add function point of technical detail to the total
			funcPts += cocomoVisualization.albrechtCplMultis[type][complexity];
		// Endfor
		});
		
		// Calculate & set Kloc
		this.kloc = Math.ceil(Math.pow(funcPts, this.slocFactor) / 1000);
	},
	calcEffort: function() {
		var consts = this.cocomo81Consts[this.projectType];	
		this.effort = Math.ceil(consts.a * Math.pow(this.kloc, consts.b));		// Effort applied
		this.devTime = Math.ceil(consts.b * Math.pow(this.effort, consts.d)); 	// Development time
		this.peopleReq = Math.ceil(this.effort / this.devTime);			// People required
	},
	show: function() {
		$('.project-type > .value').text(this.projectType.replace('-', ' '));
		$('.kloc > .value').text(this.kloc);
		$('.effort > .value').text(this.effort)
		$('.dev-time > .value').text(this.devTime);
		$('.people-req > .value').text(this.peopleReq);
		
		// Aesthetics
		$('html, body').animate({
			scrollTop: 0
		}, 5000);
		$('.cocomo1-questionnaire').slideUp(4000, function() {			
			$('.cocomo1').css('opacity', 1).slideDown(3000);
		});		
	},
	vis: function() {
		// This is an unused legacy function. Use if
		// enough time at end of project.
		var margin = {top: 20, right: 80, bottom: 30, left: 50},
		    width = 960 - margin.left - margin.right,
		    height = 500 - margin.top - margin.bottom;
		
		var parseDate = d3.time.format("%Y%m%d").parse;
		
		var x = d3.time.scale()
		    .range([0, width]);
		
		var y = d3.scale.linear()
		    .range([height, 0]);
		
		var color = d3.scale.category10();
		
		var xAxis = d3.svg.axis()
		    .scale(x)
		    .orient("bottom");
		
		var yAxis = d3.svg.axis()
		    .scale(y)
		    .orient("left");
		
		var line = d3.svg.line()
		    .interpolate("basis")
		    .x(function(d) { return x(d.date); })
		    .y(function(d) { return y(d.temperature); });
		
		var svg = d3.select(".cocomo1").append("svg")
		    .attr("width", width + margin.left + margin.right)
		    .attr("height", height + margin.top + margin.bottom)
		  .append("g")
		    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
		
		d3.tsv("data.tsv", function(data) {
		  color.domain(d3.keys(data[0]).filter(function(key) { return key !== "date"; }));
		
		  data.forEach(function(d) {
		    d.date = parseDate(d.date);
		  });
		
		  var cities = color.domain().map(function(name) {
		    return {
		      name: name,
		      values: data.map(function(d) {
		        return {date: d.date, temperature: +d[name]};
		      })
		    };
		  });
		
		  x.domain(d3.extent(data, function(d) { return d.date; }));
		
		  y.domain([
		    d3.min(cities, function(c) { return d3.min(c.values, function(v) { return v.temperature; }); }),
		    d3.max(cities, function(c) { return d3.max(c.values, function(v) { return v.temperature; }); })
		  ]);
		
		  svg.append("g")
		      .attr("class", "x axis")
		      .attr("transform", "translate(0," + height + ")")
		      .call(xAxis);
		
		  svg.append("g")
		      .attr("class", "y axis")
		      .call(yAxis)
		    .append("text")
		      .attr("transform", "rotate(-90)")
		      .attr("y", 6)
		      .attr("dy", ".71em")
		      .style("text-anchor", "end")
		      .text("Work completed");
		
		  var city = svg.selectAll(".city")
		      .data(cities)
		    .enter().append("g")
		      .attr("class", "city");
		
		  city.append("path")
		      .attr("class", "line")
		      .attr("d", function(d) { return line(d.values); })
		      .style("stroke", function(d) { return color(d.name); });
		
		  city.append("text")
		      .datum(function(d) { return {name: d.name, value: d.values[d.values.length - 1]}; })
		      .attr("transform", function(d) { return "translate(" + x(d.value.date) + "," + y(d.value.temperature) + ")"; })
		      .attr("x", 3)
		      .attr("dy", ".35em")
		      .text(function(d) { return d.name; });
		});
	}
};