<?php

// You need to add code to this script that allows users to browse other user profiles
// Hint: get started by echoing out all the other usernames

// execute the header script:
require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	echo "<h2>Browse Profiles</h2>";
	// Make a connection
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}

	// Retrieve all existing user profiles
	$query = "SELECT * FROM profiles ORDER BY username";

	// Execute the query and return the results.
	$result = mysqli_query($connection, $query);

	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
	$n = mysqli_num_rows($result);

	// if there was a match then list their profile links.
	if ($n > 0)
	{
		// For each row that is returned, fetch as an associative array for printing.
		while($row = mysqli_fetch_assoc($result)){
			// display their profile data:
			echo "<li>";
			echo "<a href='show_profile.php?username={$row['username']}'>{$row['firstname']} {$row['lastname']}</a>";
			echo "</li>";
		}
	}
	else
	{
		echo "No profiles exist.";
	}

	// we're finished with the database, close the connection:
	mysqli_close($connection);
}

// finish off the HTML for this page:
require_once "footer.php";
?>
