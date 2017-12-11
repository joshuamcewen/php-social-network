<?php
  require_once "../config/app.php";
  require_once "../classes/Connection.php";
  session_start();

  // Create a new instance of our database connection.
	$database = new Connection();

  // Starting pointer for the SQL query.
  if(isset($_GET['limit']) && is_numeric($_GET['limit'])) {
    $limit = $_GET['limit'];
  } else {
    $limit = 5;
  }

  // If accessing as a user, send the username for auth checking.
  // If not, nullify the username.
  $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

	// Retrieve all posts from the feed table, newest first.
	$query = "SELECT post_id, username, message, posted_at, (SELECT COUNT(*) FROM likes WHERE likes.post_id = feed.post_id) AS 'likes', (SELECT COUNT(*) FROM likes WHERE likes.post_id = feed.post_id AND likes.username = :username) AS 'liked'
            FROM feed
            ORDER BY posted_at DESC
            LIMIT :limit";

  $database->query($query);
  $database->bind(':username', $username);
  $database->bind(':limit', (int) $limit); // Explicitly cast as integer
  $result = $database->fetchAll();

  // Create an array for the posts.
  $posts = [];

  // If posts exist, add them to the array.
	if($database->rowCount() > 0) {
    foreach($result as $row) {
      $posts[] = $row;
    }
	}

  // Retrieve total number of posts in feed.
	$query = "SELECT COUNT(*) AS 'Total'
            FROM feed";
  $database->query($query);
  $result = $database->fetch();

  // Get number of rows.
  $total = $result['Total'];

  // Finished with the database. Nullify the database connection.
	$database = null;

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
