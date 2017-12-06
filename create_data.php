<?php

// Things to notice:
// This file is the first one we will run when we mark your submission
// Its job is to:
// Create your database (currently called "skeleton", see credentials.php)...
// Create all the tables you will need inside your database (currently it makes "members" and "profiles" tables, you will probably need more)...
// Create suitable test data for each of those tables
// NOTE: this last one is VERY IMPORTANT - you need to include test data that enables the markers to test all of your site's functionality

// read in the details of our MySQL server:
require_once "credentials.php";

// We'll use the procedural (rather than object oriented) mysqli calls

// connect to the host:
$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

// exit the script with a useful message if there was an error:
if (!$connection)
{
	die("Connection failed: " . $mysqli_connect_error);
}

// build a statement to create a new database:
$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Database created successfully, or already exists<br>";
}
else
{
	die("Error creating database: " . mysqli_error($connection));
}

// connect to our database:
mysqli_select_db($connection, $dbname);


// Drop tables

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS notify_users";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Dropped existing table: notify_users<br>";
}
else
{
	die("Error checking for existing table: " . mysqli_error($connection));
}

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS notifications";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Dropped existing table: notifications<br>";
}
else
{
	die("Error checking for existing table: " . mysqli_error($connection));
}

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS likes";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Dropped existing table: likes<br>";
}
else
{
	die("Error checking for existing table: " . mysqli_error($connection));
}

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS feed";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Dropped existing table: feed<br>";
}
else
{
	die("Error checking for existing table: " . mysqli_error($connection));
}

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS members";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Dropped existing table: members<br>";
}
else
{
	die("Error checking for existing table: " . mysqli_error($connection));
}

// if there's an old version of our table, then drop it:
$sql = "DROP TABLE IF EXISTS profiles";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Dropped existing table: profiles<br>";
}
else
{
	die("Error checking for existing table: " . mysqli_error($connection));
}

///////////////////////////////////////////
////////////// MEMBERS TABLE //////////////
///////////////////////////////////////////

// make our table:
$sql = "CREATE TABLE members (username VARCHAR(16), password VARCHAR(255), muted TINYINT(1) DEFAULT 0, last_visit DATETIME, PRIMARY KEY(username))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Table created successfully: members<br>";
}
else
{
	die("Error creating table: " . mysqli_error($connection));
}

// put some data in our table:
$usernames[] = 'barryg'; $passwords[] = password_hash("letmein", PASSWORD_DEFAULT);
$usernames[] = 'mandyb'; $passwords[] = password_hash("abc123", PASSWORD_DEFAULT);
$usernames[] = 'mathman'; $passwords[] = password_hash("secret95", PASSWORD_DEFAULT);
$usernames[] = 'brianm'; $passwords[] = password_hash("test", PASSWORD_DEFAULT);
$usernames[] = 'a'; $passwords[] = password_hash("test", PASSWORD_DEFAULT);
$usernames[] = 'b'; $passwords[] = password_hash("test", PASSWORD_DEFAULT);
$usernames[] = 'c'; $passwords[] = password_hash("test", PASSWORD_DEFAULT);
$usernames[] = 'd'; $passwords[] = password_hash("test", PASSWORD_DEFAULT);
$usernames[] = 'admin'; $passwords[] = password_hash("secret", PASSWORD_DEFAULT);

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	$sql = "INSERT INTO members (username, password) VALUES ('$usernames[$i]', '$passwords[$i]')";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql))
	{
		echo "row inserted<br>";
	}
	else
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

///////////////////////////////////////////
//////////////  FEED  TABLE  //////////////
///////////////////////////////////////////

// make our table:
$sql = "CREATE TABLE feed (post_id SERIAL, username VARCHAR(16), message VARCHAR(140), posted_at TIMESTAMP, PRIMARY KEY(post_id), FOREIGN KEY(username) REFERENCES members(username))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Table created successfully: feed<br>";
}
else
{
	die("Error creating table: " . mysqli_error($connection));
}

$usernames = [];
$usernames[] = "mandyb"; $messages[] = "This is my first post.";
$usernames[] = "brianm"; $messages[] = "This is my first post as well!";
$usernames[] = "admin"; $messages[] = "Don\'t do bad things.";
$usernames[] = "admin"; $messages[] = "I\'m an admin.";

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	$sql = "INSERT INTO feed (username, message) VALUES ('$usernames[$i]', '$messages[$i]')";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql))
	{
		echo "row inserted<br>";
	}
	else
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

///////////////////////////////////////////
//////////////  LIKES TABLE  //////////////
///////////////////////////////////////////

// make our table:
$sql = "CREATE TABLE likes (post_id BIGINT UNSIGNED, username VARCHAR(16), PRIMARY KEY(post_id, username), FOREIGN KEY(username) REFERENCES members(username), FOREIGN KEY(post_id) REFERENCES feed(post_id))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Table created successfully: likes<br>";
}
else
{
	die("Error creating table: " . mysqli_error($connection));
}

$usernames = [];
$usernames[] = "mandyb"; $posts[] = 1;
$usernames[] = "mandyb"; $posts[] = 2;
$usernames[] = "brianm"; $posts[] = 1;
$usernames[] = "brianm"; $posts[] = 3;
$usernames[] = "admin"; $posts[] = 1;
$usernames[] = "admin"; $posts[] = 3;
$usernames[] = "admin"; $posts[] = 4;

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	$sql = "INSERT INTO likes (username, post_id) VALUES ('$usernames[$i]', '$posts[$i]')";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql))
	{
		echo "row inserted<br>";
	}
	else
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

////////////////////////////////////////////
////////////// PROFILES TABLE //////////////
////////////////////////////////////////////

// make our table:
$sql = "CREATE TABLE profiles (username VARCHAR(16), firstname VARCHAR(40), lastname VARCHAR(50), pets TINYINT, email VARCHAR(50), dob DATE, PRIMARY KEY (username))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Table created successfully: profiles<br>";
}
else
{
	die("Error creating table: " . mysqli_error($connection));
}

// put some data in our table:
$usernames = array(); // clear this array (as we already used it above)
$usernames[] = 'barryg'; $firstnames[] = 'Barry'; $lastnames[] = 'Grimes'; $pets[] = 5; $emails[] = 'baz_g@outlook.com'; $dobs[] = '1961-09-25';
$usernames[] = 'mandyb'; $firstnames[] = 'Mandy'; $lastnames[] = 'Brent'; $pets[] = 3; $emails[] = 'mb3@gmail.com'; $dobs[] = '1988-05-20';

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	$sql = "INSERT INTO profiles (username, firstname, lastname, pets, email, dob) VALUES ('$usernames[$i]', '$firstnames[$i]', '$lastnames[$i]', $pets[$i], '$emails[$i]', '$dobs[$i]')";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql))
	{
		echo "row inserted<br>";
	}
	else
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

////////////////////////////////////////////
//////////// NOTIFICATION TABLE ////////////
////////////////////////////////////////////

// make our table:
$sql = "CREATE TABLE notifications (notification_id SERIAL, message varchar(255))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Table created successfully: notifications<br>";
}
else
{
	die("Error creating table: " . mysqli_error($connection));
}

$sql = "INSERT INTO notifications (message) VALUES ('This is a notification, visible to all. Acknowledge me and I\'ll be gone forever.')";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "row inserted<br>";
}
else
{
	die("Error inserting row: " . mysqli_error($connection));
}

////////////////////////////////////////////
//////////// NOTIFY_USERS TABLE ////////////
////////////////////////////////////////////

// make our table:
$sql = "CREATE TABLE notify_users (notification_id BIGINT UNSIGNED, username varchar(16), seen tinyint(1) DEFAULT '0', PRIMARY KEY(username, notification_id), FOREIGN KEY(username) REFERENCES members(username), FOREIGN KEY(notification_id) REFERENCES notifications(notification_id))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql))
{
	echo "Table created successfully: notify_users<br>";
}
else
{
	die("Error creating table: " . mysqli_error($connection));
}

$usernames = [];
$usernames[] = "a";
$usernames[] = "admin";
$usernames[] = "b";
$usernames[] = "brianm";
$usernames[] = "barryg";
$usernames[] = "c";
$usernames[] = "d";
$usernames[] = "mandyb";
$usernames[] = "mathman";

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	$sql = "INSERT INTO notify_users (username, notification_id) VALUES ('$usernames[$i]', 1)";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql))
	{
		echo "row inserted<br>";
	}
	else
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

// we're finished, close the connection:
mysqli_close($connection);
?>
