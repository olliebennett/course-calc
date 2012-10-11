<?php

//echo '<h1>Loading...</h1>';

// USE MYSQLi (IMPROVED)
$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
// Check for errors
if(mysqli_connect_errno()){
	show_error(mysqli_connect_error());
}

// FETCH COURSE DETAILS
$stmt = $mysqli->stmt_init();
$sql = "SELECT `course_id`,`course_shortcode`,`course_added`,`course_title`,`year_weights` FROM courses WHERE `course_shortcode` = ?";
if ($stmt->prepare($sql)) {
	$stmt->bind_param('s', $_GET['sc']);
	$stmt->execute();
	$stmt->store_result(); // store result of prepared statement
	
	// Check module was found
	if ($stmt->num_rows != 1) {
		show_error('Course "'.$_GET['sc'].'" not found. Go <a href="/">here</a> to create a new course.');
	}
	
	$stmt->bind_result($course['id'], $course['shortcode'], $course['added'], $course['title'], $year['weights']);
	$stmt->fetch();
	$stmt->close();
}

$shortcode = $course['shortcode'];
$course_title = $course['title'];

// Set up year weights array ($yrweights[yr] = 'weight')
$tmp = explode('|',$year['weights']);
$count = count($tmp);
for ($i=1; $i <= $count; $i++) {
	$yrweights[$i] = $tmp[$i-1];
}

// FETCH MODULE DETAILS
$stmt = $mysqli->stmt_init();
$sql = "SELECT 	`module_id`, `module_year`, `module_name`, `module_code`, `module_crds`, `module_mark` FROM modules WHERE `course_id` = ?";
if ($stmt->prepare($sql)) {
	$stmt->bind_param('i', $course['id']);
	$stmt->execute();
	$stmt->store_result(); // store result of prepared statement
	if ($stmt->num_rows < 1) {
		show_error('No modules found for course: "'.$_GET['sc'].'".');
	}
	
	// bind variables to prepared statement
	$stmt->bind_result($module['id'], $module['year'], $module['name'], $module['code'], $module['crds'], $module['mark']);

	// fetch values
	$yrtmp = 'X'; // initial dummy value
	while ($stmt->fetch()) {
		// Reset modnum to start at 1 for each year
		if ($yrtmp != $module['year']) {
			$modtmp = 1;
			$yrtmp = $module['year'];
		} else {
			$modtmp++;
		}
		
		$modules[$module['year']][$modtmp] =
		array(
		'name' => $module['name'],
		'code' => $module['code'],
		'crds' => $module['crds'],
		'mark' => $module['mark']
		);
	}
	$stmt->fetch(); // possible, because there is only one.

	$stmt->close();
}

// d($course,'$course');
// d($yrweights,'$yrweights');
// d($modules,'$modules');