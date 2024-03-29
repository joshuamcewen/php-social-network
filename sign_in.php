<?php

// Things to notice:
// The main job of this script is to execute a SELECT statement to look for the submitted username and password in the appropriate database table
// If the submitted username and password is found in the table, then the following session variable is set: $_SESSION["loggedInSkeleton"]=true;
// All other scripts check for this session variable before loading (if it doesn't exist then the user isn't logged in and the page doesn't load)
// However... the database table isn't currently being queried (at the moment the code is only checking for a username of "barryg", "mandyb" or "admin") and it's your job to add this query in...
//
// Other notes:
// client-side validation using "password","text" inputs and "required","maxlength" attributes (but we can't rely on it happening!)
// we sanitise the user's credentials - see helper.php (included via header.php) for the sanitisation function
// we validate the user's credentials - see helper.php (included via header.php) for the validation functions
// the validation functions all follow the same rule: return an empty string if the data is valid...
// ... otherwise return a help message saying what is wrong with the data.
// if validation of any field fails then we display the help messages (see previous) when re-displaying the form

// execute the header script:
require_once "header.php";

// default values we show in the form:
$username = "";
$password = "";
// strings to hold any validation error messages:
$username_val = "";
$password_val = "";
$csrf_val = "";

// should we show the signin form:
$show_signin_form = false;
// message to output to user:
$message = "";

if (isset($_SESSION['loggedInSkeleton']))
{
	// user is already logged in, just display a message:
	echo "You are already logged in, please log out first.<br>";

}
elseif (isset($_POST['username']))
{
	// user has just tried to log in:

	// Create a new instance of our database connection.
	$database = new Connection();

	// SANITISATION (see helper.php for the function definition)

	// take copies of the credentials the user submitted and sanitise (clean) them:
	$username = Helper::sanitise($_POST['username']);
	$password = Helper::sanitise($_POST['password']);

	// VALIDATION (see helper.php for the function definitions)

	// now validate the data (both strings must be between 1 and 16 characters long):
	// (reasons: we don't want empty credentials, and we used VARCHAR(16) in the database table)
	$username_val = Helper::validateString($username, 1, 16);
	$password_val = Helper::validateString($password, 1, 32);
	$csrf_val = Helper::validateCSRF();

	// concatenate all the validation results together ($errors will only be empty if ALL the data is valid):
	$errors = $username_val . $password_val . $csrf_val;

	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{
		// Check for a row where username and password both exist, suggesting a correct login.
		$query = "SELECT *
							FROM members
							WHERE username = :username";

		// Prepare and execute the statement. Retrieve the result.
		$database->query($query);
		$database->bind(':username', $username);
		$row = $database->fetch();

		// if there was a match then set the session variables and display a success message:
		if ($database->rowCount() > 0)
		{
			// If the password matches the hash (correct login)...
			if(password_verify($password, $row['password'])) {
				// set a session variable to record that this user has successfully logged in:
				$_SESSION['loggedInSkeleton'] = true;
				// and copy their username into the session data for use by our other scripts:
				$_SESSION['username'] = $username;

				// Redirect to profile
				header('location:show_profile.php');
			} else {
				// no matching credentials found so redisplay the signin form with a failure message:
				$show_signin_form = true;
				// show an unsuccessful signin message:
				$message = "Sign in failed, please try again<br>";
			}
		}
		else
		{
			// no matching credentials found so redisplay the signin form with a failure message:
			$show_signin_form = true;
			// show an unsuccessful signin message:
			$message = "Sign in failed, please try again<br>";
		}

	}
	else
	{
		// validation failed, show the form again with guidance:
		$show_signin_form = true;
		// show an unsuccessful signin message:
		$message = "Sign in failed, please check the errors shown above and try again<br>";
	}

	// Finished with the database. Nullify the database connection.
	$database = null;

}
else
{
	// user has arrived at the page for the first time, just show them the form:

	// show signin form:
	$show_signin_form = true;
}

if ($show_signin_form)
{
// show the form that allows users to log in
// Note we use an HTTP POST request to avoid their password appearing in the URL:
echo <<<_END
<h2>Sign In</h2>
<form action="sign_in.php" method="post">
  Please enter your username and password:<br>
  <div class="input-group">
		<label>Username</label>
		<input type="text" name="username" maxlength="16" value="$username" required> $username_val
	</div>
  <div class="input-group">
		<label>Password</label>
		<input type="password" name="password" maxlength="32" value="$password" required> $password_val
	</div>
	<div class="input-group">
  	<input type="submit" value="Sign In">
	</div>
	<input type="hidden" name="csrf_token" value="{$_SESSION['csrf_token']}">
	$csrf_val
</form>
_END;
}

// display our message to the user:
echo $message;

// finish off the HTML for this page:
require_once "footer.php";
?>
