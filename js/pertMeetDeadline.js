var pertMeetDeadline = {
	chance: function(z) {
		// Probability of NOT meeting target date determined using the following formula:
		// P(notMeetTargetDate) = (root(x,3) * -1) * 34 + 50
		//		= 50-34 (z)^(1/3) 
		// Formula figured out using the following sources:
		//
		// (1) https://graphsketch.com/?eqn1_color=1&eqn1_eqn=x%5E3&eqn2_color=2&eqn2_eqn=&eqn3_color=3&eqn3_eqn=&eqn4_color=4&eqn4_eqn=&eqn5_color=5&eqn5_eqn=&eqn6_color=6&eqn6_eqn=&x_min=-17&x_max=17&y_min=-10.5&y_max=10.5&x_tick=1&y_tick=1&x_label_freq=5&y_label_freq=5&do_grid=0&do_grid=1&bold_labeled_lines=0&bold_labeled_lines=1&line_width=4&image_w=850&image_h=525
		// (2) https://www.wolframalpha.com/input/?i=50-34+%28-2%29%5E%281%2F3%29&a=%5E_Real	
		// (3) https://www.symbolab.com/solver/function-inverse-calculator/inverse%20f%5Cleft(x%5Cright)%20%3D%20%5Csqrt%5B3%5D%7Bx%7D/?origin=button
		//
		// N.B.: due to this calculation involving imaginary (opposed to real) numbers, we need to utilize an external JavaScript library for calculating with imaginary numbers. This library is "math.js"
		// Michael Nguyen 23/05/2015 4:08PM michael.nguyen5777@gmail.com
		
		var probNotMeetDeadline = math.eval('50 - 34 * ' + z + '^(1/3)');
		var probMeetDeadline = 100 - probNotMeetDeadline;
		
		// Bounds checking
		if (probMeetDeadline < 1) probMeetDeadline = 1;
		else if (probMeetDeadline > 99) probMeetDeadline = 99;
		
		var probMeetDeadlineFormatted = Math.floor(probMeetDeadline) + '%';	// Floor(), to have a pessimistic bias
		return probMeetDeadlineFormatted;
	}
};