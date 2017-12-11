<?php
  require_once "../../config/app.php";
  require_once "../../classes/Connection.php";
  require_once "../../classes/Helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {

    // Create a new instance of our database connection.
  	$database = new Connection();

  	// Retrieve all notifications from using the notify_users pivot table, newest first.
    $query = "SELECT notification_id, message
  						FROM notify_users
  						JOIN notifications n USING(notification_id)
  						WHERE username = :username AND seen = 0
  						ORDER BY n.notification_id DESC
              LIMIT 5";

    $database->query($query);
    $database->bind(':username', $_SESSION['username']);
  	$result = $database->fetchAll();

    // Create an array for the notifications.
    $notifications = [];

    // If notifications exist, add them to the array.
  	if($database->rowCount() > 0) {
      foreach($result as $row) {
        $notifications[] = $row;
      }
  	}

    // Finished with the database. Nullify the database connection.
  	$database = null;

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
