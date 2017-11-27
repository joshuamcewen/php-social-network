<?php
  // database connection details:
  require_once "../credentials.php";
  session_start();

  // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		header("Content-Type: application/json", NULL, 500);
    exit;
	}

  // Starting pointer for the SQL query.
  if(isset($_GET['limit']) && is_numeric($_GET['limit'])) {
    $limit = $_GET['limit'];
  } else {
    $limit = 5;
  }

  // If accessing as a user, send the username for auth checking.
  // If not, nullify the username.
  if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
  } else {
    $username = null;
  }

	// Retrieve all posts from the feed table, newest first.
	$query = "SELECT post_id, username, message, posted_at, (SELECT COUNT(*) FROM likes WHERE likes.post_id = feed.post_id) AS 'likes', (SELECT COUNT(*) FROM likes WHERE likes.post_id = feed.post_id AND likes.username = '$username') AS 'liked' FROM feed ORDER BY posted_at DESC LIMIT $limit";
	$result = mysqli_query($connection, $query);

	// Count the rows for reference
	$n = mysqli_num_rows($result);

  // Create an array for the posts.
  $posts = [];

  // If posts exist, add them to the array.
	if($n > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $posts[] = $row;
    }
	}

  // Retrieve total number of posts in feed.
	$query = "SELECT COUNT(*) as 'Total' FROM feed";
	$result = mysqli_query($connection, $query);

  // Get number of rows.
  $total = mysqli_fetch_assoc($result)['Total'];

  // Close the connection, it's no longer required.
  mysqli_close($connection);

  // Boolean for administration rights. Used to determine what's appended.
  $admin = ($username == "admin" ? 1 : 0);

  // Print the JSON out for parsing by JavaScript.
  header("Content-Type: application/json", NULL, 200);
  echo json_encode([
      'total' => $total,
      'admin' => $admin,
      'posts' => $posts
  ]);
?>
