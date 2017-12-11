<?php
  require_once "../../../config/app.php";
  require_once "../../../classes/Connection.php";
  require_once "../../../classes/Helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton']) && $_SESSION['username'] == 'admin') {
    // If a message is passed and a valid array of users is, attempt to create a notification
    if(isset($_POST['message']) && isset($_POST['users']) && is_array($_POST['users'])) {

      // Create a new instance of our database connection.
    	$database = new Connection();

      // Sanitise the message contents.
    	$message = Helper::sanitise($_POST['message']);

      // Query to insert the notification into the notifications table.
      $query = "INSERT INTO notifications (message)
                VALUES (:message)";

      // Prepare and execute the statement. Retrieve the result.
      $database->query($query);
      $database->bind(':message', $message);
      $database->execute();

      // Retrieve the ID for this recently inserted notification.
      $notification_id = $database->lastInsertId();

      // For each user that was passed in the users array, create a notify_users entry.
      foreach($_POST['users'] as $user) {
        $query = "INSERT INTO notify_users (username, notification_id)
                  VALUES (:user,:notification_id)";

        // Prepare and execute the statement. Retrieve the result.
        $database->query($query);
        $database->bind(':user', $user);
        $database->bind(':notification_id', $notification_id);
        $database->execute();
      }

      // Send a 201 created header if inserted successfully or 400 bad request otherwise.
      if($database->rowCount() == 1) {
        header("Content-Type: application/json", NULL, 201);
      } else {
        header("Content-Type: application/json", NULL, 400);
      }

      // Finished with the database. Nullify the database connection.
    	$database = null;
    } else {
      // Send a 400 bad request if no message provided.
      header("Content-Type: application/json", NULL, 400);
    }
  } else {
    // Send a 401 unauthorised header.
    header("Content-Type: application/json", NULL, 401);
  }
?>
