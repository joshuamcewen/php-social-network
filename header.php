<?php

// Things to notice:
// This script is called by every other script (via require_once)
// It starts the session and displays a different set of menu links depending on whether the user is logged in or not...
// ... And, if they are logged in, whether or not they are the admin
// It also reads in the credentials for our database connection from credentials.php
// database connection details:
require_once "config/app.php";

require_once "classes/Connection.php";
require_once "classes/Helper.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// start/restart the session:
session_start();

// Set a single use CSRF token to prevent forgery/multiple form submission.
if(!isset($_SESSION['csrf_token'])) {
	Helper::generateCSRF();
}

if (isset($_SESSION['loggedInSkeleton']))
{

	// Create a new instance of our database connection.
	$database = new Connection();

	// Retrieve number of posts made since last login (or 0000-00-00 00:00:00 if never logged in).
	$query = "SELECT COUNT(*) AS 'Total'
						FROM feed
						WHERE posted_at > IFNULL((SELECT last_visit FROM members WHERE username = '{$_SESSION['username']}'), '0000-00-00 00:00:00')";

	$database->query($query);
	$result = $database->fetch();

	$unseen = $result['Total'];

	// Finished with the database. Nullify the database connection.
	// Can't assume that a connection will be required in the pages header.php is used?
	$database = null;

	// If there's more than 'new' post, display a badge.
	$notification = ($unseen > 0 ? "<span class='badge'>$unseen</span>" : "");

	// THIS PERSON IS LOGGED IN
	// show the logged in menu options:

echo <<<_END
<!DOCTYPE html>
<html>
<head>
	<title>2CWK50 - Joshua McEwen</title>
	<link rel="stylesheet" href="resources/css/style.css">
</head>
<body>
<nav>
	<div class="pull-left">
		<a href='about.php' class='brand'>2CWK50</a>
		<a href='about.php'>about</a>
		<a href='set_profile.php'>set profile</a>
		<a href='show_profile.php'>show profile</a>
		<a href='browse_profiles.php'>browse profiles</a>
		<a href='global_feed.php'>global feed$notification</a>
		<a href='libraries.php'>video sharing</a>
	</div>
	<div class="pull-right">
_END;

if($_SESSION['username'] == "admin") {
	// add an extra menu option if this was the admin:
	echo "  <a href='developer_tools.php'>developer tools</a>";
}

echo <<<_END
		<a href='sign_out.php'>sign out ({$_SESSION['username']})</a>
	</div>
</nav>
_END;
}
else
{
	// THIS PERSON IS NOT LOGGED IN
	// show the logged out menu options:

echo <<<_END
<!DOCTYPE html>
<html>
<head>
	<title>2CWK50 - Joshua McEwen</title>
	<link rel="stylesheet" href="resources/css/style.css">
</head>
<body>
<nav>
	<div class="pull-left">
		<a href='about.php' class='brand'>2CWK50</a>
		<a href='about.php'>about</a>
	</div>
	<div class="pull-right">
	<a href='sign_up.php'>sign up</a>
	<a href='sign_in.php'>sign in</a>
	</div>
</nav>
_END;
}
echo <<<_END
<div class="content">
<h1>2CWK50: A Social Network</h1>
_END;
?>
