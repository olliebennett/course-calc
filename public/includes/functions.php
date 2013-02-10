<?php // includes/functions.php

// Display variable
function d($d,$name = NULL){
	echo '<pre>';
	if ($name !== NULL) echo $name.":\n";
	else echo "Unknown Variable:\n";
	var_dump($d);
	echo '</pre>';
}

// Show errors and die!
function show_error($err,$show_header=TRUE) {
	if($show_header) include('includes/header.php');
	echo '<p style="color:red;" >Error: '.$err.'</p>';
	include('includes/footer.php');
	exit();
}

// Generate a 5-digit shortcode of letters and numbers. 5 should cause only rare collisions, yet is easy to remember
function createShortcode() {
	
	// my old version:
	// return substr(str_replace(array('0','O','o','1','I','i','Q'),'',base64_encode(rand().microtime())),0,5);
	
	// set allowed chars
	$valid_chars = 'abcdefghkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTYVWXYZ23456789';
	
	// length of random string
	$length = 5;
	
	// Create random string, repeating if it matches bad_words
	do {
	
		// start with an empty random string
		$random_string = "";

		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random number between 0 and (one less than) the number of valid chars
			// select this random character out of the string of valid chars
			$random_char = $valid_chars[mt_rand(0, $num_valid_chars-1)];

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}
	
	} while (contains_bad_word($random_string));

    // return our finished random string
    return $random_string;
	
}

// strpos that takes an array of values to match against a string
// note the stupid argument order (to match strpos)
function contains_bad_word($string) {

	$string = strtolower($string);

	// $blacklist
	include('includes/badlist.inc.php');

    foreach($blacklist as $bad_word) {
        if(FALSE !== strpos($string, $bad_word)) return TRUE;
    }

    return FALSE;
}

// ignore this ugly contraption I made - I found a better way I think
function sanitizeArray($arr) {
	foreach ($arr as &$element) {
		if (is_array($element)) {
			$element = sanitizeArray($element);
		} else {
			$element = mysql_real_escape_string($element);
		}
	}
}