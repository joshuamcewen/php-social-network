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
	// connect directly to our database (notice 4th argument):
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}

	// Retrieve URL parameter value for username key.
	if(isset($_GET['username'])) {
		// Sanitise and set the username.
		$username = sanitise($_GET['username'], $connection);
	} else {
		// If there is not username parameter, assume they're viewing their own profile.
		$username = $_SESSION["username"];
	}

	// check for a row in our profiles table with a matching username:
	$query = "SELECT * FROM profiles WHERE username='$username'";

	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);

	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
	$n = mysqli_num_rows($result);

	// if there was a match then extract their profile data:
	if ($n > 0)
	{
		// use the identifier to fetch one row as an associative array (elements named after columns):
		$row = mysqli_fetch_assoc($result);
		// display their profile data:
		echo "First name: {$row['firstname']}<br>";
		echo "Last name: {$row['lastname']}<br>";
		echo "Number of pets: {$row['pets']}<br>";
		echo "Email address: {$row['email']}<br>";
		echo "Date of birth: {$row['dob']}<br>";
	}
	else
	{
		// Prompt user to create a profile if viewing their own and no profile exists...
		if($username == $_SESSION['username']) {
			echo "You still need to set up a profile!<br>";
		} else { // Otherwise, the user their searching for doesn't have a profile.
			echo "This user's profile doesn't exist.";
		}
	}

	// we're finished with the database, close the connection:
	mysqli_close($connection);

}

// finish off the HTML for this page:
require_once "footer.php";
?>
