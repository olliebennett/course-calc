/* JavaScript for CourseCalc.com
 by Ollie Bennett [http://olliebennett.co.uk] */

// JSLint Configuration
/*jslint white: true, browser: true, plusplus: true */
/*global $, data_yrweights, data_modules */ 

// Note: "use strict" places the program in a "strict" operating context.
// i.e. it helps you make fewer errors, by detecting more things that could lead to breakages.
// See http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/

// A data array (for use as temporary storage when decrementing and re-incrementing years/modules) is set up by the server, for use in this script
// format: data_modules[yearnum][modnum][0-3] = value; where 0-3 are the fields

// Update year (module) credits sum
function updateYearModCredsSum(year) {
	
	"use strict";
	
	var total_year_mod_creds = 0;
	$('#year'+year).find(".inputmodcreds").each(function() {
		total_year_mod_creds += Number($(this).val());
	});
	
	// update sum
	$('#sum_mod_creds_year'+year).text(total_year_mod_creds);
	
}

// Update year weightings sum
function updateYearWeightingsSum() {
	
	"use strict";
	
	var total_year_weight = 0;
	$('.inputyearnumber').each(function() {
		total_year_weight += Number($(this).val());
	});
	
	// update sum
	$('#year_weightings_total').text(total_year_weight);
	
	if (total_year_weight === 100) {
		$("#year_weightings_total").css('color', 'green');
	} else {
		$("#year_weightings_total").css('color', 'red');
	}
	
}

function incrementModule(year) {
	
	"use strict";
	
	// Determine nummodules
	var nummodules = parseInt($('#num_modules_year' + year).text(), 10) + 1; // parseInt(string, radix) - radix specifies the base of the integer
	
	// Update module counter
	$('#num_modules_year' + year).text(nummodules);
	
	// Add a new row to corresponding tbody.
	$('#year' + year)
	.append($('<tr>')
		.append($('<td>')
			.append($('<input>') // module code
				.attr({
					'type': 'text',
					'id': year + '_' + nummodules + '_code',
					'name': year + '_' + nummodules + '_code',
					'class': 'inputmodcode'
				})
			)
		)
		.append($('<td>')
			.append($('<input>') // module name
				.attr({
					'type': 'text',
					'id': year + '_' + nummodules + '_name',
					'name': year + '_' + nummodules + '_name',
					'class': 'inputmodname'
				})
			)
		)
		.append($('<td>')
			.append($('<input>') // module credits
				.attr({
					'type': 'text',
					'value': '10',
					'id': year + '_' + nummodules + '_crds',
					'name': year + '_' + nummodules + '_crds',
					'class': 'inputmodcreds'
				})
			)
		)
		.append($('<td>')
			.append($('<input>') // module mark
				.attr({
					'type': 'text',
					'id': year + '_' + nummodules + '_mark',
					'name': year + '_' + nummodules + '_mark',
					'class': 'inputmodmark'
				})
			)
			.append($('<span>')
				.text('%')
				.attr('class','percent')
			)
		)			
	);
	
	// Change plurality (module/modules) accordingly
	if (nummodules === 1) {
		$('span#num_modules_plural' + year).text('module');
	} else {
		$('span#num_modules_plural' + year).text('modules');
	}
	
	// Insert any previously saved module data
	if ($.isArray(data_modules[year][nummodules])) {
		// alert('yup! have data for this module');
		$('#'+year +'_'+nummodules+'_code').val(data_modules[year][nummodules][0]);
		$('#'+year +'_'+nummodules+'_name').val(data_modules[year][nummodules][1]);
		$('#'+year +'_'+nummodules+'_crds').val(data_modules[year][nummodules][2]);
		$('#'+year +'_'+nummodules+'_mark').val(data_modules[year][nummodules][3]);
	}
	
	// Update module credits sum
	updateYearModCredsSum(year);
	
}

function decrementModule(year) {
	
	"use strict";
	
	// Determine nummodules
	var nummodules = parseInt($('#num_modules_year'+year).text(), 10) - 1,
		last_row;
	
	//alert(nummodules);
		
	// Check not hitting zero.
	if (nummodules >= 1) {
		//alert('Must define at least 1 module.');
		//return false; // using this causes problems in FF (but is ok in chrome..)
		// perhaps use preventDefault next time...
		// see http://fuelyourcoding.com/jquery-events-stop-misusing-return-false/
		
		
		// Update module counter
		$('#num_modules_year' + year).text(nummodules);
		
		// Change plurality (module/modules) accordingly
		if (nummodules === 1) {
			$('span#num_modules_plural' + year).text('module');
		} else {
			$('span#num_modules_plural' + year).text('modules');
		}
		
		// get last tbody row.
		last_row = $('#year' + year).find('tr').last();
		// save last_row data
		// Increase size of data array to accommodate a new module
		if (!$.isArray(data_modules[year][nummodules+1])) {
			data_modules[year][nummodules+1] = [];
			//alert('here');
		}
		data_modules[year][nummodules+1][0] = last_row.find('.inputmodcode').val();
		data_modules[year][nummodules+1][1] = last_row.find('.inputmodname').val();
		data_modules[year][nummodules+1][2] = last_row.find('.inputmodcreds').val();
		data_modules[year][nummodules+1][3] = last_row.find('.inputmodmark').val();
		// delete last row
		last_row.remove();
	}
	
	// Update module credits sum
	updateYearModCredsSum(year);
}

function incrementYear() {

	"use strict";
	
	// Determine numyears.
	var numyears = parseInt($('#num_years').text(), 10) + 1,
		nummodules,
		modnum; // counter for looping modules
	
	// Update year counter.
	$('#num_years').text(numyears);
	
	// Add new row to #weightings table.
	$("#weightings")
	.append($('<tr>')
		.append($('<td>')
			.text(numyears)
			.attr('class','yearnum')
		)
		.append($('<td>')
			.append($('<input>')
				.attr({
					'type': 'text',
					'value': '0',
					'name': 'yearpercentage_' + numyears,
					'id': 'yearpercentage_' + numyears,
					'class': 'inputyearnumber'
				})
			)
			.append($('<span>')
				.text('%')
				.attr('class','percent')
			)
		)
	);
	
	// Increase size of data array to accommodate a new year
	if (!$.isArray(data_modules[numyears])) {
		data_modules[numyears] = [];
		// Add a module to get the year started.
		nummodules = 1;
		//alert('not array, ' + nummodules);
	} else {
		// Get number of previously saved modules
		nummodules = (data_modules[numyears].length-1); // -1 because the array doesn't have a [0] element, so .length is one too big
		//alert('array, ' + nummodules);
	}
	
	// Update year weighting if it was previously entered
	if (data_yrweights[(numyears)]) {
		$('#yearpercentage_'+(numyears)).val(data_yrweights[(numyears)]);
	}
	
	// Add new tbody section to modules table.
	$("#modules")
	.append($('<tbody>')
		.attr('id', 'year' + numyears)
		.attr('class', 'yeartitle')
		.append($('<tr>')
			.append($('<td>')
				.attr('colspan', '4')
				.append($('<strong>')
					.text('Year ' + numyears)
				)
				.append(' (consisting of ')
				.append($('<a>')
					.attr('href', 'javascript:decrementModule(\'' + numyears + '\')')
					.text('[-]')
				)
				.append(' ')
				.append($('<span>')
					.attr('id', 'num_modules_year' + numyears)
					.text('0')
				)
				.append(' ')
				.append($('<a>')
					.attr('href', 'javascript:incrementModule(\'' + numyears + '\')')
					.text('[+]')
				)
				.append(' ')
				.append($('<span>')
					.attr('id', 'num_modules_plural' + numyears)
					.text('module')
				)
				.append(', totalling ')
				.append($('<span>')
					.attr('id', 'sum_mod_creds_year' + numyears)
					.text('0')
				)
				.append(' credits)')
			)
		)
	);
	
	// Increment module once for each previously saved (or single new) module
	for (modnum=nummodules; modnum>0; modnum--) {
		incrementModule(numyears);
	}
	
	// Force changes to this year's credits fields to trigger updating credits sum
	$('#year'+numyears).find('.inputmodcreds').live("blur", function () {
		updateYearModCredsSum(numyears);
	});
	// Also update sum now
	updateYearModCredsSum(numyears);
	
	// Update year weightings sum
	updateYearWeightingsSum();
	
}

function decrementYear() {
	
	"use strict";
	
	// Determine numyears.
	var numyears = parseInt($('#num_years').text(), 10),
	nummodules,
	modnum, // counter for looping modules
	last_row;
		
	// Check not hitting zero (must be at least 1 year!).
	if (numyears > 1) {
	
		// Update year counter.
		$('#num_years').text(numyears-1);
		
		// Store all module data for the year
		
		// get number of modules
		nummodules = parseInt($('#num_modules_year' + numyears).text(), 10) - 1;
		
		// Run through each module
		for (modnum=nummodules+1; modnum>0; modnum--) {
			// Increase size of data array to accommodate a new module
			if (!$.isArray(data_modules[numyears][modnum])) {
				data_modules[numyears][modnum] = [];
			}
			// Store module data
			data_modules[numyears][modnum][0] = $('#' + numyears + '_' + modnum + '_code').val();
			data_modules[numyears][modnum][1] = $('#' + numyears + '_' + modnum + '_name').val();
			data_modules[numyears][modnum][2] = $('#' + numyears + '_' + modnum + '_crds').val();
			data_modules[numyears][modnum][3] = $('#' + numyears + '_' + modnum + '_mark').val();
		}
		
		// Remove last row from #weightings table (after storing for possible later use).
		last_row = $('#weightings').find('tr').last();
		data_yrweights[numyears] = last_row.find('.inputyearnumber').val();
		last_row.remove();
	
		// Remove last tbody from #modules table (each tbody corresponds to a year).
		$('#modules').find('tbody').last().remove();
	
	}
	
	// Update year weightings sum
	updateYearWeightingsSum();
}

function validateForm() {
	
	"use strict";
	
	// Store Errors
	var num_errors = 0,
	errors = [],
	x; // looping through errors
	
	// Check year weightings total 100%
	updateYearWeightingsSum();
	if ($("#year_weightings_total").text() !== '100') {
		errors[++num_errors] = 'Year weightings must sum to 100%';
	}
	
	// Check module credits
	$('.inputmodcreds').each(function() {
		// Num credits must be specified
		if ($(this).val() === '') {
			errors[++num_errors] = 'Number of credits must be specified for every module (even if incomplete).';
			$(this).focus(); // focus the cursor to this location
			return false; // break the each loop
		}
		// Check for non-numeric module credits
		if (!$.isNumeric($(this).val())) {
			errors[++num_errors] = 'Invalid (non-numeric) value supplied for one or more module credits.';
			$(this).select(); // focus cursor to this location and select all text, ready for deletion/editing
			return false; // break the each loop
		}
	});
	
	// Check module credits
	$('.inputmodmark').each(function() {
		// Check for non-numeric module mark
		if (!$.isNumeric($(this).val()) && ($(this).val() !== '')) {
			errors[++num_errors] = 'Invalid (non-numeric) value supplied for one or more module marks.';
			$(this).select(); // focus cursor to this location and select all text, ready for deletion/editing
			return false; // break the each loop
		}
	});
	
	// Clean any results from previous data
	$('#results').empty();
		
	// Check for, and display errors
	if (num_errors > 0) {
		
		// Show Errors
		for(x = 1; x < errors.length; x++) {
			$('#results').append('<p class="error">Error: ' + errors[x] + '</p>');
		}
		
		// Disable form submission
		return false;
	}
	
	// Passed validation
	$('#results').append('<p class="success">The form was successfully validated.</p>');
	
	// Allow form submission
	return true;
	
}

$(document).ready(function(){
	
	"use strict";
	
	// Force validation when submit is pressed.
	$("#course").submit(function(){
		// Validate the form (will return false if fails, so submit is aborted)
		return validateForm();
	});
	
	// Update year weighting percentage sum on blur (now, and on all future .inputyearnumber elements)
	$('.inputyearnumber').live("blur", function () {
		// Update weightings sum (should be 100)
		updateYearWeightingsSum();
		
		// Fill in default value of '0' if field is emptied
		if ($(this).val() === '') {
			$(this).val('0');
		}
	});
	// Also update sum immediately
	updateYearWeightingsSum();
	
	// Clear the default '0' from year weightings
	$('.inputyearnumber').live("focus", function() {
		// Wipe input if currently 0
		if($(this).val() === '0') {
			$(this).val('');
		}
	});
	
	// Select share link when clicked
	$(".copyurl").click(function() {
		$(this).select();
	});
	
});