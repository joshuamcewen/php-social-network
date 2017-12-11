<?php
  require_once "../config/app.php";
  require_once "../classes/Connection.php";
  require_once "../classes/Helper.php";
  session_start();

  if (isset($_SESSION['loggedInSkeleton'])) {
    // If a post ID is passed, attempt to 'like' it.
    if(isset($_POST['post_id'])) {

      // Create a new instance of our database connection.
    	$database = new Connection();

      // Sanitise the post ID.
    	$post_id = Helper::sanitise($_POST['post_id']);
      $username = $_SESSION['username'];

      // Determine whether or not the user has already liked this post.
    	$query = "SELECT COUNT(*) AS 'like'
                FROM likes
                WHERE username = :username
                AND post_id = :post_id";

    	$database->query($query);
      $database->bind(':username', $username);
      $database->bind(':post_id', $post_id);
      $row = $database->fetch();

      // If no rows exist, insert a new like else, display an error message.
    	if($row['like'] == 0) {
        $query = "INSERT INTO likes (post_id, username)
                  VALUES (:post_id,:username)";

        // Prepare and execute the statement. Retrieve the result.
        $database->query($query);
        $database->bind(':post_id', $post_id);
        $database->bind(':username', $username);
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
      // Send a 400 bad request if no post ID provided.
      header("Content-Type: application/json", NULL, 400);
    }
  } else {
    // Send a 401 unauthorised header.
    header("Content-Type: application/json", NULL, 401);
  }
?>
