<?php
  require_once "header.php";

  if (isset($_SESSION['loggedInSkeleton'])) {
    // If a username is passed and the user is an admin, mute the user.
    if(isset($_GET['username']) && $_SESSION['username'] == "admin") {
      // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    	// if the connection fails, we need to know, so allow this exit:
    	if (!$connection)
    	{
    		die("Connection failed: " . $mysqli_connect_error);
    	}

      // Sanitise the username.
    	$username = sanitise($_GET['username'], $connection);

    	// Update the members table, altering the value of the muted column.
    	$query = "UPDATE members SET muted = 1 WHERE username = '$username'";
    	$result = mysqli_query($connection, $query);

      if($result) {
				echo "<h2>$username was successfully muted.</h2>";
			} else {
				echo "<h2>$username couldn't be muted.</h2>";
			}

    	// Close the connection, it's no longer required.
    	mysqli_close($connection);
    } else {
      // If an invalid request is made, redirect the user to the feed.
      header('location:global_feed.php');
    }
  } else {
    echo "You must be logged in to access this page.";
  }

  require_once "footer.php";
?>
