<?php

// Things to notice:
// You need to add code to this script to implement the global feed
// A simple example has been included to show how you might display extra content/functionality to the admin

// execute the header script:
require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton'])) {
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
} else {
	// If the user is logged in, echo ou the page contents
	echo "<h2>Global Feed</h2>";

	echo <<<_END
	<script
		src="https://code.jquery.com/jquery-3.2.1.min.js"
		integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
		crossorigin="anonymous"></script>

		<script type="text/javascript">
			setInterval(function(){
				$.getJSON("return_feed.php", function(json) {
							$('ul#posts').html("");
							$.each(json, function(index, post) {
_END;

		// If the user is an admin, add the mute button on AJAX refresh.
		if($_SESSION['username'] == "admin") {
			echo <<<_END
								$('ul#posts').append("<li><div class='post-details'><span class='username'>" + post.username + "</span><span class='posted'>" + post.posted_at + "</span></div><div class='post-body'>" + post.message + "</div><div class='post-footer'><span class='likes'><span class='icon like'></span>" + post.likes +"</span><a href='/mute_user.php?username=" + post.username + "' class='mute'>Mute</a><a href='/like_post.php?id=" + post.post_id + "'>Like</a></div></li>");
_END;
		} else {
			echo <<<_END
								$('ul#posts').append("<li><div class='post-details'><span class='username'>" + post.username + "</span><span class='posted'>" + post.posted_at + "</span></div><div class='post-body'>" + post.message + "</div><div class='post-footer'><span class='likes'><span class='icon like'></span>" + post.likes +"</span><a href='/like_post.php?id=" + post.post_id + "'>Like</a></div></li>");
_END;
		}

		echo <<<_END
						});
				});
			}, 3000);
		</script>
	 <form class="feed" method="POST" action="">
	 		<textarea name="message" placeholder="Have something to say?"></textarea>
			<div class="input-group">
				<input type="submit" value="Spread the word">
			</div>
	 </form>
_END;

	// connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection) {
		die("Connection failed: " . $mysqli_connect_error);
	}

	// If message post data exists, attempt to add a new message to the feed
	if(isset($_POST['message'])) {

		// Check whether or not the current user is muted.
		$query = "SELECT muted FROM members WHERE username = '{$_SESSION['username']}'";
		$result = mysqli_query($connection, $query);

		$row = mysqli_fetch_assoc($result);

		// If they're not muted, continue with posting. Else, display an error.
		if($row['muted'] == 0) {
			// Sanitise the user input
			$username = sanitise($_SESSION['username'], $connection);
			$message = sanitise($_POST['message'], $connection);

			$query = "INSERT INTO feed(username, message) VALUES('$username', '$message')";
			$result = mysqli_query($connection, $query);

			if($result) {
				echo "<div class='notification'>Post was successful.</div>";
			} else {
				echo "<div class='notification'>Post was unsuccessful.</div>";
			}
		} else {
			echo "<div class='notification'>You can't post, you've been muted.</div>";
		}
	}

	// a little extra text that only the admin will see!:
	if ($_SESSION['username'] == "admin")
	{
		// Retrieve all muted users
		$query = "SELECT username FROM members WHERE muted = 1";
		$result = mysqli_query($connection, $query);

		$n = mysqli_num_rows($result);

		// Display admin panel
		echo "
			<div class='admin-tools'>
				<h2>Muted Users</h2>
				<ul>
		";

		// If there are muted users, display them.
		if($n > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				echo "<li><a href='unmute_user.php?username={$row['username']}'>Unmute {$row['username']}</a></li>";
			}
		} else {
			echo "<li>No muted users</li>";
		}

		echo "
				</ul>
			</div>
		";
	}

	// Retrieve all posts from the feed table, newest first.
	$query = "SELECT post_id, username, message, posted_at, (SELECT COUNT(*) FROM likes WHERE likes.post_id = feed.post_id) AS 'likes' FROM feed ORDER BY posted_at DESC";
	$result = mysqli_query($connection, $query);

	// Count the rows for reference
	$n = mysqli_num_rows($result);

	if($n > 0) {
		echo "<ul id='posts'>";
		// For each message retrieved, fetch as an associative array.
		while($row = mysqli_fetch_assoc($result)){
			// display the message.
			echo "
				<li>
					<div class='post-details'>
						<span class='username'>{$row['username']}</span>
						<span class='posted'>{$row['posted_at']}</span>
					</div>
					<div class='post-body'>
						{$row['message']}
					</div>
					<div class='post-footer'>
						<span class='likes'>
							<span class='icon like'></span>{$row['likes']}
						</span>
			";

			if($_SESSION['username'] == "admin") {
				echo "<a href='/mute_user.php?username={$row['username']}' class='mute'>Mute</a>";
			}

			echo "
						<a href='/like_post.php?id={$row['post_id']}'>Like</a>
					</div>
				</li>
			";
		}
	}
	echo "</ul>";

	// Close the connection, it's no longer required.
	mysqli_close($connection);
}

// finish off the HTML for this page:
require_once "footer.php";
?>
