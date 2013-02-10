<?php

//echo '<h1>Saving...</h1>';

// USE OLD MySQL (Not Improved :( )
// Connect to database
mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) or die(mysql_error());
mysql_select_db(MYSQL_DB) or die(mysql_error());

// Sanitize POST
$post = $_POST;

// Remove 'save' POST array element
if (isset($post['save'])) unset($post['save']);

// Data Arrays
$yrweights = array(); 	// $yrweights[year1] = year1's weight
$modules = array(); 	// $module[year1][module1]['crds'] = year1_module1's credits
$modules_temp = array();

// Parse POST variables
foreach ($post as $POSTkey => $POSTval) {
	
	// Split post key
	$POSTkeyBANG = explode('_',$POSTkey);
	
	// Handle year percentage
	if ($POSTkeyBANG[0] == 'yearpercentage') {
		if(is_numeric($POSTval)) {
			$yrweights[(int)$POSTkeyBANG[1]] = (float)$POSTval;
		}
		else {
			if ($POSTval == '') show_error('The parameter {POST['.$POSTkey.']} was blank.');
			else show_error('The parameter {POST['.$POSTkey.'] = "'.$POSTval.'"} was not numeric.');
		}
	}
	// Else expect Y_M_XXXX (where Y=yearnum, M=modulenum, XXXX = name||crds||mark||code)
	elseif ($POSTkeyBANG[0] == 'coursetitle') {
		$course_title = mysql_real_escape_string($POSTval);
	}
	else {
		if (in_array($POSTkeyBANG[2],array('name','crds','mark','code'))) {
			$modules_temp[(int)$POSTkeyBANG[0]][(int)$POSTkeyBANG[1]][$POSTkeyBANG[2]] = $POSTval;
		}
		else {
			show_error('The parameter {POST['.$POSTkey.'] = "'.$POSTval.'"} was invalid.');
		}
		
	}
}

d("fixing modules array");
d($modules_temp,'modules_temp');

// Fix array so that module numbers increment continuously from 1
// (this allows for cases where user has deleted e.g. module 3 of 7)
foreach ($modules_temp as $yr => $moduledetails) {
	$yrmodcount = 0;
	foreach ($moduledetails as $key => $val) {
		$modules[$yr][$yrmodcount++] = $modules_temp[$yr][$key];
	}
}

d($modules,'modules');

//d($yrweights,'$yrweights');
//d($modules,'$modules');

// Check year weights sum to 100
if (array_sum($yrweights) != 100) {
	show_error('The year weights total ['.array_sum($yrweights).'], not [100].');
}



// Ensure shortcode does not already exist in DB.
do {
    // Generate shortcode
	$shortcode = createShortcode();
	
	//d($shortcode,'$shortcode');
	
	// Query database
	$query = '
	SELECT COUNT("name") as namecount
	FROM courses
	WHERE course_shortcode = "'.$shortcode.'"';
	$result = mysql_query($query) or show_error(mysql_error());

	$row = mysql_fetch_array($result);
	
	//d($row,'$row');
	
} while ($row['namecount'] > 0);

$year_weights = implode('|',$yrweights);

// Insert course data
$query_string  = 'INSERT INTO courses (course_shortcode,course_added,course_title,year_weights) ';
$query_string .= "VALUES ('$shortcode',NOW(),'$course_title','$year_weights')";


$result_courses = mysql_query($query_string) or show_error(mysql_error()); 
$course_id = mysql_insert_id();

// d($course_id,'$course_id');

// Insert module data
$query_string = 'INSERT INTO modules (course_id,module_year,module_name,module_code,module_crds,module_mark) ';
$query_string .= 'VALUES ';
$query_string_arr = array();
foreach ($modules as $yr => $moduledetails) {
	foreach ($moduledetails as $module) {
		$query_string_arr[] = "($course_id,'$yr','{$module['name']}','{$module['code']}','{$module['crds']}','{$module['mark']}')";
	}
}
$query_string .= implode(',',$query_string_arr);

// echo '$query_string (modules):<pre>';
// var_dump($query_string);
// echo '</pre>';

$result_modules = mysql_query($query_string) or die(mysql_error()); 


$sharestring = 'I just calculated my degree grade in no time, with CourseCalc.';
$sharestring_enc = urlencode($sharestring);
// would be good if this was customised dependant on whether course was completed or not...

// redirect
//header('Location: ' . $shortcode);
