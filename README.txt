SETUP:
...
- To begin, visit /create_data.php to initialise the database and corresponding tables and rows.
- Login details for standard and administrative users can be found below:
	- Standard: barryg, letmein
	- Administrative: admin, secret

DOCUMENTATION:
...
Testing the feed API
- Functionality is demonstrated in /global_feed.php
- Additionally, to retrieve the raw JSON, use /api/recent.php?limit=10
- Authentication is required when accessing the URI.

Changelog

- Implemented account creation /sign_up.php
- Implemented account login /sign_in.php
	- Validated on server/client-side
- Implemented password hashing (bcrypt)
- Implemented profile creation /set_profile.php
	- Validated on server/client-side
- Implemented profile browsing /browse_profiles.php and /show_profile.php
- Implemented global feed /global_feed.php
	- Validation on server/client-side
	- AJAX used for refreshing feed.
	- AJAX used for retrieving additional posts (5 limit default)
	- AJAX used for calling liking/unliking scripts.
	- Muting for administrators.
- Implemented basic CSRF token in forms. In header.php, a csrf_token session is
  set to a random value. A hidden input in each form echoes out this value. This is then
  validated using the validateCSRF() helper function as with other types of data.
- Sign in/Registration CSRF tested in Postman sending valid form-data to these pages.
