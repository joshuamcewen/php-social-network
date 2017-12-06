<?php
  // Require header
  require_once "../../credentials.php";
  require_once "../../helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {
    // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
  	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  	// if the connection fails, we need to know, so allow this exit:
  	if (!$connection)
  	{
  		header("Content-Type: application/json", NULL, 500);
      exit;
  	}

  	// Retrieve all notifications from using the notify_users pivot table, newest first.
    $query = "SELECT notification_id, message
  						FROM notify_users
  						JOIN notifications n USING(notification_id)
  						WHERE username = '{$_SESSION['username']}' AND seen = 0
  						ORDER BY n.notification_id DESC
              LIMIT 5";

  	$result = mysqli_query($connection, $query);

  	// Count the rows for reference
  	$n = mysqli_num_rows($result);

    // Create an array for the notifications.
    $notifications = [];

    // If notifications exist, add them to the array.
  	if($n > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
      }
  	}

    // Close the connection, it's no longer required.
    mysqli_close($connection);

    // Print the JSON out for parsing by JavaScript.
    header("Content-Type: application/json", NULL, 200);
    echo json_encode([
        'notifications' => $notifications
      ]);
  } else {
    // Send a 401 unauthorised header.
    header("Content-Type: application/json", NULL, 401);
  }
?>
