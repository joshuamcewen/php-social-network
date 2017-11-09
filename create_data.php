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

///////////////////////////////////////////
////////////// MEMBERS TABLE //////////////
///////////////////////////////////////////

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

// make our table:
$sql = "CREATE TABLE members (username VARCHAR(16), password VARCHAR(16), PRIMARY KEY(username))";

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
$usernames[] = 'barryg'; $passwords[] = 'letmein';
$usernames[] = 'mandyb'; $passwords[] = 'abc123';
$usernames[] = 'mathman'; $passwords[] = 'secret95';
$usernames[] = 'brianm'; $passwords[] = 'test';
$usernames[] = 'a'; $passwords[] = 'test';
$usernames[] = 'b'; $passwords[] = 'test';
$usernames[] = 'c'; $passwords[] = 'test';
$usernames[] = 'd'; $passwords[] = 'test';

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

////////////////////////////////////////////
////////////// PROFILES TABLE //////////////
////////////////////////////////////////////

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

// we're finished, close the connection:
mysqli_close($connection);
?>