<?php
  // database connection details:
  require_once "../../credentials.php";
  session_start();

  // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		header("Content-Type: application/json", NULL, 500);
    exit;
	}

	// Retrieve pets and number of users for each count.
	$query = "SELECT pets, COUNT(*) AS 'users'
            FROM profiles
            GROUP BY pets";

	$result = mysqli_query($connection, $query);

	// Count the rows for reference
	$n = mysqli_num_rows($result);

  // Create an array for columns.
  $cols = [
    ['id' => "",'label' => "Pets",'pattern' => "",'type'=> "string"],
    ['id' => "",'label' => "Users",'pattern' => "",'type'=> "number"]
  ];

  // Create an array for the rows.
  $rows = [];

  // If a user exists, add them to the array in Google Chart's expected format.
	if($n > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $rows[] = [
          'c' => [
                  [
                    'v' => $row['pets'],
                    'f' => null
                  ],
                  [
                    'v' => (int) $row['users'],
                    'f' => null
                  ]
                ]
      ];
    }
	}

  // Close the connection, it's no longer required.
  mysqli_close($connection);

  // Print the JSON out for parsing by JavaScript.
  header("Content-Type: application/json", NULL, 200);
  echo json_encode([
    'cols' => $cols,
    'rows' => $rows
  ]);
?>
