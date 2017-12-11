<?php
  require_once "../config/app.php";
  require_once "../classes/Connection.php";
  require_once "../classes/Helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {

    // Create a new instance of our database connection.
  	$database = new Connection();

  	// Update the last visit time, used for new posts notification
  	$query = "UPDATE members
              SET last_visit = NOW()
              WHERE username = :username";

    // Prepare and execute the statement. Retrieve the result.
  	$database->query($query);
    $database->bind(':username', $_SESSION['username']);
    $database->execute();

    // If the query succeeds, return a 201 created header else 400 bad request.
    if($database->rowCount() == 1) {
      header("Content-Type: application/json", NULL, 201);
      echo "Yes.";
    } else {
      header("Content-Type: application/json", NULL, 400);
      echo "No";
    }

    // Finished with the database. Nullify the database connection.
  	$database = null;
  }
?>
