<?php
  // Require header
  require_once "../../../credentials.php";
  require_once "../../../helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton']) && $_SESSION['username'] == 'admin') {
    // If a message is passed and a valid array of users is, attempt to create a notification
    if(isset($_POST['message']) && isset($_POST['users']) && is_array($_POST['users'])) {

      // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    	// if the connection fails, we need to know, so allow this exit:
    	if (!$connection)
    	{
        header("Content-Type: application/json", NULL, 500);
        exit;
    	}

      // Sanitise the message contents.
    	$message = sanitise($_POST['message'], $connection);

      $query = "INSERT INTO notifications (message) VALUES ('$message')";
      $result = mysqli_query($connection, $query);

      $notification_id = mysqli_insert_id($connection);

      foreach($_POST['users'] as $user) {
        $query = "INSERT INTO notify_users (username, notification_id) VALUES ('$user','$notification_id')";
        $result = mysqli_query($connection, $query);
      }

      // Send a 201 created header if inserted successfully or 400 bad request otherwise.
      if(mysqli_affected_rows($connection) == 1) {
        header("Content-Type: application/json", NULL, 201);
      } else {
        header("Content-Type: application/json", NULL, 400);
      }

      // Close the connection, it's no longer required.
      mysqli_close($connection);
    } else {
      // Send a 400 bad request if no message provided.
      header("Content-Type: application/json", NULL, 400);
    }
  } else {
    // Send a 401 unauthorised header.
    header("Content-Type: application/json", NULL, 401);
  }
?>
