<?php

/*
  PDO Connection Wrapper
  Based on Philip Brown's PDO Article @ http://culttt.com/
*/

class Connection {

  private $host = DB_HOST;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbname = DB_NAME;

  private $dbh; // Database handler
  private $error;

  private $stmt; // Statement

  public function __construct() {

    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

    $options = [
      //PDO::ATTR_PERSISTENT => true, // Persistent connections
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Allow exceptions
    ];

    // Attempt to create a new PDO instance.
    try {
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    } catch(PDOException $e) {
      $this->error = $e->getMessage();
    }
  }

  // Prepare the statement and set value of $this->stmt
  public function query($query) {
    $this->stmt = $this->dbh->prepare($query);
  }

  // Bind value to parameter in the
  public function bind($param, $value, $type = NULL) {

    // Determine what data type we're binding
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    // Bind using PDO's bindValue() function.
    $this->stmt->bindValue($param, $value, $type);
  }

  // Executes the prepared statement, good for INSERT, UPDATE, DELETE.
  public function execute() {
    return $this->stmt->execute();
  }

  // Executes the prepared statements, returns an associative array for individual row.
  public function fetch() {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Executes the prepared statements, returns an associative array for result set.
  public function fetchAll() {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Returns number of affected rows from last query.
  public function rowCount(){
    return $this->stmt->rowCount();
  }

  // Returns the ID of the last inserted row.
  public function lastInsertId(){
    return $this->dbh->lastInsertId();
  }
}
