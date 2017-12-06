<?php

// Things to notice:
// You need to add code to this script to implement the developer tools
// Notice that the code not only checks whether the user is logged in, but also whether they are the admin, before it displays the page content
// You can implement all the developer tools functionality from this script, or...
// ... You may wish to add admin-only features to other pages as well - e.g., global_feed.php (where a simple example has been included)

// execute the header script:
require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	// only display the page content if this is the admin account (all other users get a "you don't have permission..." message):
	if ($_SESSION['username'] == "admin")
	{
		echo <<<_END
		<script
			src="https://code.jquery.com/jquery-3.2.1.min.js"
			integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
			crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="resources/js/developer.js"></script>

		<h2>Developer Tools</h2>

		<div class="dashboard" id="likes_dashboard">
			<h3>Top 5 Users by Likes Received</h3>
			<div id="likes_slider"></div>
			<div id="likes_chart"></div>

			<h3>Notify Users</h3>
			<form id="form_notify" class="notification">
				<div id="users"></div>
				<textarea id="message" placeholder="Enter a message"></textarea>
				<div class="input-group">
					<input type="submit" value="Notify Users">
				</div>
				<div id="errors"></div>
			</form>
		</div>

		<div class="dashboard" id="pets_dashboard">
			<h3>Pets Demographic</h3>
			<div id="pets_slider"></div>
			<div id="pets_chart"></div>
		</div>

		<div class="dashboard" id="posts_dashboard">
			<h3>Top 5 Users by Posts Created</h3>
			<div id="posts_slider"></div>
			<div id="posts_chart"></div>
		</div>

		<div class="dashboard" id="days_dashboard">
			<h3>Posts by Day</h3>
			<div id="days_slider"></div>
			<div id="days_chart"></div>
		</div>
_END;
	}
	else
	{
		echo "You don't have permission to view this page...<br>";
	}
}

// finish off the HTML for this page:
require_once "footer.php";
?>
