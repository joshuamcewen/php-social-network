<?php

// Things to notice:
// The main job of this script is to execute an INSERT or UPDATE statement to create or update a user's profile information...
// ... but only once the data the user supplied has been validated on the client-side, and then sanitised ("cleaned") and validated again on the server-side
// It's your job to add these steps into the code
// Both sign_up.php and sign_in.php do client-side validation, followed by sanitisation and validation again on the server-side -- you may find it helpful to look at how they work
// HTML5 can validate all the profile data for you on the client-side
// The PHP functions in helper.php will allow you to sanitise the data on the server-side and validate *some* of the fields...
// ... but you'll also need to add some new PHP functions of your own to validate email addresses and dates

// execute the header script:
require_once "header.php";

// default values we show in the form:
$firstname = "";
$lastname = "";
$pets = "";
$email = "";
$dob = "";
// strings to hold any validation error messages:
$firstname_val = "";
$lastname_val = "";
$pets_val = "";
$email_val = "";
$dob_val = "";
$csrf_val = "";
// should we show the set profile form?:
$show_profile_form = false;
// message to output to user:
$message = "";

$errors = "";

if (!isset($_SESSION['loggedInSkeleton'])) {
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
} elseif (isset($_POST['firstname'])) {
	// user just tried to update their profile

	// Create a new instance of our database connection.
	$database = new Connection();

	$firstname = Helper::sanitise($_POST['firstname']);
	$lastname = Helper::sanitise($_POST['lastname']);
	$pets = Helper::sanitise($_POST['pets']);
	$email = Helper::sanitise($_POST['email']);
	$dob = Helper::sanitise($_POST['dob']);

	// SERVER-SIDE VALIDATION CODE MISSING:

	// Validate the first name
	$firstname_val = Helper::validatePattern($firstname, '/^[A-Za-z\']{1,40}$/', "First name must be between 1 and 40 characters in length.");

	// Validate the last name
	$lastname_val = Helper::validatePattern($lastname, '/^[A-Za-z\']{1,50}$/', "Last name must be between 1 and 50 characters in length.");

	// Validate pets
	$pets_val = Helper::validatePattern($pets, '/^[0-9]{1,4}$/', "Number of pets must be between 1 and 4 digits in length.<br>");

	// Validate email
	$email_val = Helper::validateEmail($email);

	// Validate date of birth
	$dob_val = Helper::validatePattern($dob,
									'/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/',
									"Date of birth must be in the format YYYY-MM-DD."
								 );

	// Validate CSRF token.
	$csrf_val = Helper::validateCSRF();

	// Concatenate error messages.
	$errors = $firstname_val . $lastname_val . $pets_val . $email_val . $dob_val . $csrf_val;

	// check that all the validation tests passed before going to the database:
	if ($errors == "") {
		// read their username from the session:
		$username = $_SESSION["username"];

		// now write the new data to our database table...

		// check to see if this user already had a favourite:
		$query = "SELECT *
							FROM profiles
							WHERE username = :username";

		// Prepare and execute the statement. Retrieve the result.
		$database->query($query);
		$database->bind(':username', $username);
		$result = $database->fetch();

		// if there was a match then UPDATE their profile data, otherwise INSERT it:
		if($database->rowCount() > 0){
			// we need an UPDATE:
			$query = "UPDATE profiles
								SET firstname=:firstname,lastname=:lastname,pets=:pets,email=:email,dob=:dob
								WHERE username=:username";

		} else {
			// we need an INSERT:
			$query = "INSERT INTO profiles (username,firstname,lastname,pets,email,dob)
								VALUES (:username,:firstname,:lastname,:pets,:email,:dob)";
		}

		$database->query($query);
		$database->bind(':firstname', $firstname);
		$database->bind(':lastname', $lastname);
		$database->bind(':pets', $pets);
		$database->bind(':email', $email);
		$database->bind(':dob', $dob);
		$database->bind(':username', $username);
		$result = $database->execute();

		// no data returned, we just test for true(success)/false(failure):
		if($result){
			// show a successful update message:
			$message = "Profile successfully updated<br>";
		} else {
			// show the set profile form:
			$show_profile_form = true;
			// show an unsuccessful update message:
			$message = "Update failed<br>";
		}
	} else {
		// validation failed, show the form again with guidance:
		$show_profile_form = true;
		// show an unsuccessful update message:
		$message = "Update failed, please check the errors above and try again<br>";
	}

	// Finished with the database. Nullify the database connection.
	$database = null;

} else {
	// arrived at the page for the first time, show any data already in the table:

	// Create a new instance of our database connection.
	$database = new Connection();

	// read the username from the session:
	$username = $_SESSION["username"];

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
		// extract their profile data for use in the HTML:
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$pets = $row['pets'];
		$email = $row['email'];
		$dob = $row['dob'];
	}

	// show the set profile form:
	$show_profile_form = true;

	// Finished with the database. Nullify the database connection.
	$database = null;

}


if ($show_profile_form) {
	// Set date for max value in DOB.
	$date = date('Y-m-d');

echo <<<_END
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

<h2>Set Profile</h2>

<form action="set_profile.php" id="profile_form" method="post">
  Update your profile info:<br>
  <div class="input-group">
		<label>First name</label>
		<input type="text" name="firstname" pattern="[A-Za-z']{1,40}" value="$firstname" required> $firstname_val
	</div>
  <div class="input-group">
		<label>Last name</label>
		<input type="text" name="lastname" pattern="[A-Za-z']{1,50}" value="$lastname" required> $lastname_val
	</div>
  <div class="input-group">
		<label>Number of pets</label>
		<input type="number" min="0" name="pets" value="$pets" required> $pets_val
	</div>
  <div class="input-group">
		<label>Email address</label>
		<input type="email" name="email" value="$email" required> $email_val
	</div>
  <div class="input-group">
		<label>Date of birth</label>
		<input type="date" name="dob" value="$dob" max="$date" required> $dob_val
	</div>
	<div class="input-group">
  	<input type="submit" value="Set Profile">
	</div>
	<input type="hidden" name="csrf_token" value="{$_SESSION['csrf_token']}"> $csrf_val
</form>
_END;
}

// display our message to the user:
echo $message;

// finish of the HTML for this page:
require_once "footer.php";
?>
