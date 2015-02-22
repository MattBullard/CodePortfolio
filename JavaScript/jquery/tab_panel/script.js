$(function() {
	$('.tab-panels .tabs li').on('click', function() {

		// to avoid confusion with multiple tab blocks, assign
		// the entire tab panel to a variable

		// standards dictate that if a variable is assigned to a jQuery selector,
		// it should be preceeded by a doller sign $

		var panel = $(this).closest('.tab-panels');

		// remove active class from the active tab header
		panel.find('.tabs li.active').removeClass('active');

		//add the active class to the tab header clicked on
		$(this).addClass('active');

		// set a variable to the value of the rel attribute of the header clicked
		var panelToShow = $(this).attr('rel');

		// slide up the Div with the active class
		panel.find('.panel.active').slideUp(300, showNextPanel);


		function showNextPanel() {

			// remove the active class from the Div after it has scrolled up
			$(this).removeClass('active');

			// slide down the Div with the same ID as the header clicked on
			$('#'+panelToShow).slideDown(300, function() {
				// add the active class to the Div after it has slide down
				$(this).addClass('active');
			});
		}


	});


});
