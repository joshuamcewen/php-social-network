<?php
  require_once "../../config/app.php";
  require_once "../../classes/Connection.php";
  require_once "../../classes/Helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {
    // If a post ID is passed, attempt to 'like' it.
    if(isset($_POST['notification_id'])) {

      // Create a new instance of our database connection.
    	$database = new Connection();

      // Sanitise the notification ID.
    	$notification_id = Helper::sanitise($_POST['notification_id']);
      $username = $_SESSION['username'];

      // Update the 'seen' value in the notify_users table to represent acknowledgement.
    	$query = "UPDATE notify_users
                SET seen = 1
                WHERE username = :username
                AND notification_id = :notification_id";

      // Prepare and execute the statement. Retrieve the result.
    	$database->query($query);
      $database->bind(':username', $username);
      $database->bind(':notification_id', $notification_id);
      $database->execute();

      // Send a 201 created header if inserted successfully or 400 bad request otherwise.
      if($database->rowCount() == 1) {
        header("Content-Type: application/json", NULL, 201);
      } else {
        header("Content-Type: application/json", NULL, 400);
      }

      // Finished with the database. Nullify the database connection.
    	$database = null;
    } else {
      // Send a 400 bad request if no notification ID provided.
      header("Content-Type: application/json", NULL, 400);
    }
  } else {
    // Send a 401 unauthorised header.
    header("Content-Type: application/json", NULL, 401);
  }
?>
