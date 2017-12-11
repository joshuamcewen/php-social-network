<?php
  require_once "../../config/app.php";
  require_once "../../classes/Connection.php";
  require_once "../../classes/Helper.php";
  session_start();

  // Create a new instance of our database connection.
	$database = new Connection();

	// Retrieve users and the number of posts.
	$query = "SELECT m.username AS 'username', COUNT(f.post_id) AS 'posts'
            FROM members m
            JOIN feed f USING(username)
            GROUP BY m.username
            LIMIT 5";

  // Prepare and execute the statement. Retrieve the result.
  $database->query($query);
  $result = $database->fetchAll();

  // Create an array for columns.
  $cols = [
    ['id' => "",'label' => "Username",'pattern' => "",'type'=> "string"],
    ['id' => "",'label' => "Total Posts",'pattern' => "",'type'=> "number"]
  ];

  // Create an array for the rows.
  $rows = [];

  // If a user exists, add them to the array in Google Chart's expected format.
	if($database->rowCount() > 0) {
    foreach($result as $row) {
      $rows[] = [
          'c' => [
                  [
                    'v' => $row['username'],
                    'f' => null
                  ],
                  [
                    'v' => (int) $row['posts'],
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
