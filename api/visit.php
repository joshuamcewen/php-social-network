<?php
  // Require header
  require_once "../credentials.php";
  require_once "../helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  	// if the connection fails, we need to know, so allow this exit:
  	if (!$connection) {
  		die("Connection failed: " . $mysqli_connect_error);
  	}

  	// Update the last visit time, used for new posts notification
  	$query = "UPDATE members SET last_visit = NOW() WHERE username = '{$_SESSION['username']}'";
  	mysqli_query($connection, $query);

    // If the query succeeds, return a 201 created header else 400 bad request.
    if(mysqli_affected_rows($connection) == 1) {
      header("Content-Type: application/json", NULL, 201);
    } else {
      header("Content-Type: application/json", NULL, 400);
    }

    // Connection is no longer required.
    mysqli_close($connection);
  }
?>
