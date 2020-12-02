<?php
//======================================================================
// DATABASE CONNECTION
//======================================================================
DEFINE('DB_HOST', "localhost");
DEFINE('DB_USER', "root");
DEFINE('DB_PASSWORD', ""); //Note: this should be your root password
DEFINE('DB_NAME', "ugadvisor_db");

if ( !class_exists( 'database' )) {
  class database {

    public function __construct() {
    }

    public function connect() {
      try {
        $db_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
          OR die("Connection failed: " . $db_connection->connect_error);
        return $db_connection;
      } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      } 
    }

    public function disconnect() {
      $db_connection->close();
    }

  }
}
?>