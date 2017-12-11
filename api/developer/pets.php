<?php
require_once "../config/app.php";
require_once "../classes/Connection.php";
require_once "../classes/Helper.php";
  session_start();

  // Create a new instance of our database connection.
	$database = new Connection();

	// Retrieve pets and number of users for each count.
	$query = "SELECT pets, COUNT(*) AS 'users'
            FROM profiles
            GROUP BY pets";

  // Prepare and execute the statement. Retrieve the result.
  $database->query($query);
  $result = $database->fetchAll();

  // Create an array for columns.
  $cols = [
    ['id' => "",'label' => "Pets",'pattern' => "",'type'=> "string"],
    ['id' => "",'label' => "Users",'pattern' => "",'type'=> "number"]
  ];

  // Create an array for the rows.
  $rows = [];

  // If a user exists, add them to the array in Google Chart's expected format.
	if($database->rowCount() > 0) {
    foreach($result as $row) {
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

  // Finished with the database. Nullify the database connection.
	$database = null;

  // Print the JSON out for parsing by JavaScript.
  header("Content-Type: application/json", NULL, 200);
  echo json_encode([
    'cols' => $cols,
    'rows' => $rows
  ]);
?>
