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
$age_val = "";
$email_val = "";
$dob_val = "";
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

	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}

	// SANITISATION CODE MISSING:

	$firstname = sanitise($_POST['firstname'], $connection);
	$lastname = sanitise($_POST['lastname'], $connection);
	$pets = sanitise($_POST['pets'], $connection);
	$email = sanitise($_POST['email'], $connection);
	$dob = sanitise($_POST['dob'], $connection);

	// SERVER-SIDE VALIDATION CODE MISSING:

	// Validate the first name
	if(!preg_match('/^[A-Za-z\']{1,40}$/', $firstname)) {
		$errors .= "First name must be between 1 and 40 characters in length.<br>";
	}

	// Validate the last name
	if(!preg_match('/^[A-Za-z\']{1,50}$/', $lastname)) {
		$errors .= "Last name must be between 1 and 50 characters in length.<br>";
	}

	// Validate pets
	if(!preg_match('/^[0-9]{1,4}$/', $pets)) {
		$errors .= "Number of pets must be between 1 and 4 digits in length.<br>";
	}

	// Validate email
	if(!preg_match('/^([A-Za-z_.0-9]+@[A-Za-z_.0-9]+\.[A-Za-z.]{2,4}){1,50}$/', $email)) {
		$errors .= "Email address must between 1 and 50 character in length and be of a valid format.<br>";
	}

	// Validate date of birth
	if(!preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/', $dob)) {
		$errors .= "Date of birth must be in the format YYYY-MM-DD.<br>";
	}

	// check that all the validation tests passed before going to the database:
	if ($errors == "") {
		// read their username from the session:
		$username = $_SESSION["username"];

		// now write the new data to our database table...

		// check to see if this user already had a favourite:
		$query = "SELECT * FROM profiles WHERE username='$username'";

		// this query can return data ($result is an identifier):
		$result = mysqli_query($connection, $query);

		// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
		$n = mysqli_num_rows($result);

		// if there was a match then UPDATE their profile data, otherwise INSERT it:
		if($n > 0){
			// we need an UPDATE:
			$query = "UPDATE profiles SET firstname='$firstname',lastname='$lastname',pets=$pets,email='$email',dob='$dob' WHERE username='$username'";
			$result = mysqli_query($connection, $query);
		} else {
			// we need an INSERT:
			$query = "INSERT INTO profiles (username,firstname,lastname,pets,email,dob) VALUES ('$username','$firstname','$lastname',$pets,'$email','$dob')";
			$result = mysqli_query($connection, $query);
		}

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

	// we're finished with the database, close the connection:
	mysqli_close($connection);

} else {
	// arrived at the page for the first time, show any data already in the table:

	// read the username from the session:
	$username = $_SESSION["username"];

	// now read their profile data from the table...

	// connect directly to our database (notice 4th argument):
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
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
		// extract their profile data for use in the HTML:
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$pets = $row['pets'];
		$email = $row['email'];
		$dob = $row['dob'];
	}

	// show the set profile form:
	$show_profile_form = true;

	// we're finished with the database, close the connection:
	mysqli_close($connection);

}

if ($show_profile_form)
{
echo <<<_END
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

<script type="text/javascript">

	var errors = [];

	function validateInput(value, pattern, message){
		if(!value.match(pattern)) {
			errors.push(message);
		}
	}

	$(document).ready(function(){
		$('form').keyup(function(){
			// Clear errors to prevent duplication
			errors = [];

			// Clear errors from display
			$('span.errors').html("");

			// Validate the first name
			validateInput($('input[name=firstname]').val(), /^[A-Za-z']{1,40}$/, "First name must be between 1 and 40 characters in length.");

			// Validate the last name
			validateInput($('input[name=lastname]').val(), /^[A-Za-z']{1,40}$/, "Last name must be between 1 and 50 characters in length.");

			// Validate the number of pets
			validateInput($('input[name=pets]').val(), /^[0-9]{1,4}$/, "Number of pets must be between 1 and 4 digits in length.");

			// Validate the email address
			validateInput($('input[name=email]').val(), /^([A-Za-z_.0-9]+@[A-Za-z_.0-9]+\.[A-Za-z.]{2,4}){1,50}$/, "Email address must between 1 and 50 character in length and be of a valid format.");

			// Validate the date of birth
			validateInput($('input[name=dob]').val(), /^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/, "Date of birth must be in the format YYYY-MM-DD.");

			// For each error that has been presented, display at the top of form
			$.each(errors, function(index, value){
				$('span.errors').append(value + "<br>");
			});
		});

		// When the form is submitted, prevent the submission if there are errors.
		$('form').submit(function(e){
			if(errors.length === 0) {
				return true;
			} else {
				e.preventDefault();
			}
		});
	});
</script>

<h2>Set Profile</h2>

<form action="set_profile.php" id="profile_form" method="post">
	<span class="errors">
		$errors
	</span>
  Update your profile info:<br>
  <div class="input-group">
		<label>First name</label>
		<input type="text" name="firstname" value="$firstname">
	</div>
  <div class="input-group">
		<label>Last name</label>
		<input type="text" name="lastname" value="$lastname">
	</div>
  <div class="input-group">
		<label>Number of pets</label>
		<input type="text" name="pets" value="$pets">
	</div>
  <div class="input-group">
		<label>Email address</label>
		<input type="text" name="email" value="$email">
	</div>
  <div class="input-group">
		<label>Date of birth</label>
		<input type="text" name="dob" value="$dob">
	</div>
	<div class="input-group">
  	<input type="submit" value="Set Profile">
	</div>
</form>
_END;
}

// display our message to the user:
echo $message;

// finish of the HTML for this page:
require_once "footer.php";
?>
