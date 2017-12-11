<?php

// Things to notice:
// The main job of this script is to execute a SELECT statement to find the user's profile information (then display it)

// execute the header script:
require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	echo "<h2>Show Profile</h2>";

	// If a username is supplied in the req, use it. Else retrieve the currently logged in user.
	$username = isset($_GET['username']) ? Helper::sanitise($_GET['username']) : $_SESSION['username'];

	// Create a new instance of our database connection.
	$database = new Connection();

	// check for a row in our profiles table with a matching username:
	$query = "SELECT *
						FROM profiles
						WHERE username=:username";

	// Prepare and execute the statement. Retrieve the result.
	$database->query($query);
	$database->bind(':username', $username);
	$row = $database->fetch();

	// if there was a match then extract their profile data:
	if ($database->rowCount() > 0) {
		// use the identifier to fetch one row as an associative array (elements named after columns):
		// display their profile data:
		echo "First name: {$row['firstname']}<br>";
		echo "Last name: {$row['lastname']}<br>";
		echo "Number of pets: {$row['pets']}<br>";
		echo "Email address: {$row['email']}<br>";
		echo "Date of birth: {$row['dob']}<br>";
	} else {
		// Prompt user to create a profile if viewing their own and no profile exists...
		if($username == $_SESSION['username']) {
			echo "You still need to set up a profile!<br>";
		} else { // Otherwise, the user their searching for doesn't have a profile.
			echo "This user's profile doesn't exist.";
		}
	}

	// Finished with the database. Nullify the database connection.
	$database = null;

}

// finish off the HTML for this page:
require_once "footer.php";
?>
