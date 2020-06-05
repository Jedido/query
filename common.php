<?php
  /**
   * CSE 154
   * common.php starter code for CP5. You may add more "common" functions to this if you would
   * like, but this will help get started with getting your PDO connection (more information
   * in Friday's lecture and its reading).
   *
   * Remember to use include("common.php") at the top of any PHP file that wants to
   * use these function(s)!
   */

  /**
   * Returns a PDO object connected to the database. If a PDOException is thrown when
   * attempting to connect to the database, responds with a 503 Service Unavailable
   * error.
   * @return {PDO} connected to the database upon a succesful connection.
   */
  function get_PDO() {
    # Variables for connections to the database.
    $host = "localhost";     # fill in with server name (e.g. localhost)
    $port = "3306";      # fill in with a port if necessary (will be different mac/pc)
    $user = "root";     # fill in with user name
    $password = ""; # fill in with password (will be different mac/pc)
    $dbname = "query";   # fill in with db name containing your SQL tables

    # Make a data source string that will be used in creating the PDO object
    $ds = "mysql:host={$host}:{$port};dbname={$dbname};charset=utf8";

    try {
      $db = new PDO($ds, $user, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db;
    } catch (PDOException $ex) {
      header("HTTP/1.1 503 Service Unavailable");
      header("Content-Type: text/plain");
      die("Can not connect to the database. Please try again later.");
    }
  }
?>
