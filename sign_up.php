<?php

// Things to notice:
// The main job of this script is to execute an INSERT statement to add the submitted username and password
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

// should we show the signup form?:
$show_signup_form = false;
// message to output to user:
$message = "";

if (isset($_SESSION['loggedInSkeleton']))
{
	// user is already logged in, just display a message:
	echo "You are already logged in, please log out first<br>";

}
elseif (isset($_POST['username']))
{
	// user just tried to sign up:

	// Create a new instance of our database connection.
	$database = new Connection();

	// SANITISATION (see helper.php for the function definition)

	// take copies of the credentials the user submitted, and sanitise (clean) them:
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

	// Hash the password
	$password = password_hash($password, PASSWORD_DEFAULT);

	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{
		// try to insert the new details:
		$query = "INSERT INTO members (username, password)
							VALUES (:username, :password);";

		// Prepare and execute the statement.
		$database->query($query);
		$database->bind(':username', $username);
		$database->bind(':password', $password);
		$result = $database->execute();

		// no data returned, we just test for true(success)/false(failure):
		if ($result)
		{
			// show a successful signup message:
			$message = "Signup was successful, please sign in<br>";
		}
		else
		{
			// show the form:
			$show_signup_form = true;
			// show an unsuccessful signup message:
			$message = "Sign up failed, please try again<br>";
		}

	}
	else
	{
		// validation failed, show the form again with guidance:
		$show_signup_form = true;
		// show an unsuccessful signin message:
		$message = "Sign up failed, please check the errors shown above and try again<br>";
	}

	// Finished with the database. Nullify the database connection.
	$database = null;

}
else
{
	// just a normal visit to the page, show the signup form:
	$show_signup_form = true;

}

if ($show_signup_form)
{
// show the form that allows users to sign up
// Note we use an HTTP POST request to avoid their password appearing in the URL:
echo <<<_END
<h2>Sign Up</h2>
<form action="sign_up.php" method="post">
  Please choose a username and password:<br>
	<div class="input-group">
	  <label>Username</label>
		<input type="text" name="username" maxlength="16" value="$username" required> $username_val
	</div>
	<div class="input-group">
  	<label>Password</label>
		<input type="password" name="password" maxlength="32" required> $password_val
	</div>
	<div class="input-group">
  	<input type="submit" value="Sign Up">
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
