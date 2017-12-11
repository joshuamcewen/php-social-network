<?php
  require_once "header.php";

  if (isset($_SESSION['loggedInSkeleton'])) {
    // If a username is passed and the user is an admin, mute the user.
    if(isset($_GET['username']) && $_SESSION['username'] == "admin") {

      // Create a new instance of our database connection.
    	$database = new Connection();

      // Sanitise the username.
    	$username = Helper::sanitise($_GET['username']);

    	// Update the members table, altering the value of the muted column.
    	$query = "UPDATE members
                SET muted = 1
                WHERE username = :username";

      // Prepare and execute the statement.
    	$database->query($query);
      $database->bind(':username', $username);
    	$result = $database->execute();

      if($result) {
				echo "<h2>$username was successfully muted.</h2>";
			} else {
				echo "<h2>$username couldn't be muted.</h2>";
			}

      // Finished with the database. Nullify the database connection.
    	$database = null;
    } else {
      // If an invalid request is made, redirect the user to the feed.
      header('location:global_feed.php');
    }
  } else {
    echo "You must be logged in to access this page.";
  }

  require_once "footer.php";
?>
