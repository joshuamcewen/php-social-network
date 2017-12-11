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

	// Create a new instance of our database connection.
	$database = new Connection();

	// Retrieve all existing user profiles
	$query = "SELECT username, firstname, lastname
						FROM profiles
						ORDER BY username";

	// Prepare and execute the statement. Retrieve the result.
	$database->query($query);
	$result = $database->fetchAll();

	// if there was a match then list their profile links.
	if ($database->rowCount() > 0) {
		// For each row returned, print the user information.
		foreach($result as $row){
			// display their profile data:
			echo "<li>";
			echo "<a href='show_profile.php?username={$row['username']}'>{$row['firstname']} {$row['lastname']}</a>";
			echo "</li>";
		}
	} else {
		echo "No profiles exist.";
	}

	// Finished with the database. Nullify the database connection.
	$database = null;
}

// finish off the HTML for this page:
require_once "footer.php";
?>
