<?php

// Default TimeZone
date_default_timezone_set('Europe/London');

// Functions
require 'includes/functions.php';

// Save data if submitted
// it might be a bad idea to rely on "save" being sent...
if (isset($_POST) && isset($_POST['save'])) {
	include 'includes/db.php';
	include 'includes/save.php';
}

// Load data if required
// (use .htaccess to redirect "/xxx" to "/index.php?sc=xxx")
elseif ((isset($_GET)) && (isset($_GET['sc'])) && $_GET['sc'] != '') {
	include 'includes/db.php';
	include 'includes/load.php';
}

// Otherwise create empty arrays for use in foreach below.
else {
	$yrweights = array();
	$modules = array();
}

// Header
require 'includes/header.php';

// Shortcode and share links
if (isset($shortcode)) include 'includes/shortcode.php';

// Display the table
require 'includes/display.php';

// Display results - only after loading
if (isset($shortcode)) {
	include 'includes/calc.php';
}

// Show footer
require 'includes/footer.php';
