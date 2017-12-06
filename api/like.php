<?php
  // Require header
  require_once "../credentials.php";
  require_once "../helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {
    // If a post ID is passed, attempt to 'like' it.
    if(isset($_POST['post_id'])) {
      // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    	// if the connection fails, we need to know, so allow this exit:
    	if (!$connection)
    	{
        header("Content-Type: application/json", NULL, 500);
        exit;
    	}

      // Sanitise the post ID.
    	$post_id = sanitise($_POST['post_id'], $connection);
      $username = $_SESSION['username'];

      // Determine whether or not the user has already liked this post.
    	$query = "SELECT COUNT(*)
                FROM likes
                WHERE username = '$username'
                AND post_id = '$post_id'";

    	$result = mysqli_query($connection, $query);

    	// Count the rows for reference
    	$n = mysqli_num_rows($result);

      // If no rows exist, insert a new like else, display an error message.
    	if($n > 0) {
        $query = "INSERT INTO likes (post_id, username)
                  VALUES ('$post_id','$username')";
                  
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
      // Send a 400 bad request if no post ID provided.
      header("Content-Type: application/json", NULL, 400);
    }
  } else {
    // Send a 401 unauthorised header.
    header("Content-Type: application/json", NULL, 401);
  }
?>
