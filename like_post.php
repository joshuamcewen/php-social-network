<?php
  // Require header
  require_once "header.php";

  if (isset($_SESSION['loggedInSkeleton'])) {
    // If a post ID is passed, attempt to 'like' it.
    if(isset($_GET['id'])) {
      // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
    	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    	// if the connection fails, we need to know, so allow this exit:
    	if (!$connection)
    	{
    		die("Connection failed: " . $mysqli_connect_error);
    	}

      // Sanitise the post ID.
    	$post_id = sanitise($_GET['id'], $connection);
      $username = $_SESSION['username'];

    	// Determine whether or not the user has already liked this post.
    	$query = "SELECT COUNT(*) FROM likes WHERE username = '$username' AND post_id = '$post_id'";
    	$result = mysqli_query($connection, $query);

    	// Count the rows for reference
    	$n = mysqli_num_rows($result);

      // If no rows exist, insert a new like else, display an error message.
    	if($n > 0) {
        $query = "INSERT INTO likes (post_id, username) VALUES ('$post_id','$username')";
  			$result = mysqli_query($connection, $query);

    	} else {
        echo "You've already liked this post.";
      }

    	// Close the connection, it's no longer required.
    	mysqli_close($connection);

      // Redirect the user to the feed.
      header('location:global_feed.php');
    }

    // After an invalid or valid request, redirect the user to the feed.
    //header('location:/global_feed.php');
  } else {
    echo "You must be logged in to access this page.";
  }

  require_once "footer.php";
?>
