<?php
//======================================================================
// DATABASE CONNECTION
//======================================================================

/* Important - Comment Error Reporting Section out before going live!!!  */
error_reporting(E_ALL);
ini_set('display_errors', 1);
// End of Error Reporting
/* --------------------------------------------------------------------- */

DEFINE('DB_HOST', "localhost");
DEFINE('DB_USER', "root");
DEFINE('DB_PASSWORD', ""); //Note: this should be your root password
DEFINE('DB_NAME', "ugadvisor_db");

try {
  $db_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    OR die("Connection failed: " . $db_connection->connect_error);
} catch (Exception $e) {
  echo 'Caught exception: ',  $e->getMessage(), "\n";
} 
  
?>