<?php

// Things to notice:
// This script holds the sanitisation function that we pass all our user data to
// This script holds the validation functions that double-check our user data is valid
// You can add new PHP functions to validate different kinds of user data (e.g., emails, dates) by following the same convention:
// if the data is valid return an empty string, if the data is invalid return a help message

// function to sanitise (clean) user data:
function sanitise($str, $connection)
{
	if (get_magic_quotes_gpc())
	{
		// just in case server is running an old version of PHP with "magic quotes" running:
		$str = stripslashes($str);
	}
	// escape any dangerous characters, e.g. quotes:
	$str = mysqli_real_escape_string($connection, $str);
	// ensure any html code is safe by converting reserved characters to entities:
	$str = htmlentities($str);
	// return the cleaned string:
	return $str;
}

// if the data is valid return an empty string, if the data is invalid return a help message
function validateString($field, $minlength, $maxlength)
{
    if (strlen($field)<$minlength)
    {
		// wasn't a valid length, return a help message:
        return "Minimum length: " . $minlength;
    }
	elseif (strlen($field)>$maxlength)
    {
		// wasn't a valid length, return a help message:
        return "Maximum length: " . $maxlength;
    }
	// data was valid, return an empty string:
    return "";
}

// if the data is valid return an empty string, if the data is invalid return a help message
function validateInt($field, $min, $max)
{
	// see PHP manual for more info on the options: http://php.net/manual/en/function.filter-var.php
	$options = array("options" => array("min_range"=>$min,"max_range"=>$max));

	if (!filter_var($field, FILTER_VALIDATE_INT, $options))
    {
		// wasn't a valid integer, return a help message:
        return "Not a valid number (must be whole and in the range: " . $min . " to " . $max . ")";
    }
	// data was valid, return an empty string:
    return "";
}

// A function to validate a CSRF token and return a boolean.
function validateCSRF() {
	if(!(isset($_SESSION['csrf_token']) && isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']))) {
		generateCSRF();
		return "CSRF token is invalid.";
	}
	generateCSRF();
	return "";
}

// A function to generate a new CSRF token.
function generateCSRF() {
	echo "dog.";
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// A function to validate Regular Expressions against given data.
// A custom error message is passed due to the differing nature of patterns.
function validatePattern($field, $pattern, $error) {
	if(!preg_match($pattern, $field)) {
		return $error;
	}
	return "";
}

// A function to validate email addresses and check their length.
function validateEmail($email) {
	if(!(filter_var($email, FILTER_VALIDATE_EMAIL) && validateString($email, 1, 50) == "")) {
		return "Email address must between 1 and 50 character in length and be of a valid format.";
	}
	return "";
}

// all other validation functions should follow the same rule:
// if the data is valid return an empty string, if the data is invalid return a help message
// ...

function getUnseenCount() {
	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection) {
		die("Connection failed: " . $mysqli_connect_error);
	}

	$query = "SELECT COUNT(*) AS 'Total' FROM feed WHERE posted_at > (SELECT last_visit FROM members WHERE username = '{$_SESSION['username']}')";
	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);

	return mysqli_fetch_assoc($result)['Total'];
}

?>
