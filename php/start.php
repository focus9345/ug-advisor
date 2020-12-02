<?php
//======================================================================
// CREATE THE DATABASE 
//======================================================================

/* the first connection to the database */
DEFINE('DB_HOST', "localhost");
DEFINE('DB_USER', "root");
DEFINE('DB_PASSWORD', ""); //Note: this should be your root password


try {
  $db_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD)
    OR die("Connection failed: " . $db_connection->connect_error);
} catch (Exception $e) {
  echo 'Caught exception: ',  $e->getMessage(), "\n";
} 

/* Creates the Database */
$create_stmt = "CREATE OR REPLACE DATABASE ugadvisor_db";
$prep_stmt = $db_connection -> prepare($create_stmt);
$prep_stmt->execute();
$prep_stmt->close();

/* Select the New Database */
$db_connection->select_db("ugadvisor_db");
$salt = 'graduate';

//-----------------------------------------------------
// Create Database Tables
//-----------------------------------------------------

$create_role = $db_connection->prepare(
	"CREATE OR REPLACE TABLE role
       (role_id int NOT NULL AUTO_INCREMENT,
	role_type varchar(255) NOT NULL,
	PRIMARY KEY(role_id));");
$create_role->execute();
$create_role->close();

$create_status = $db_connection->prepare(
	"CREATE OR REPLACE TABLE status
       (status_id int NOT NULL AUTO_INCREMENT,
       status_type varchar(255) NOT NULL,
	PRIMARY KEY(status_id));");
$create_status->execute();
$create_status->close();

/* Below Are Tables That Have Forigen Keys */ 

$create_sysadmin = $db_connection->prepare(
	"CREATE OR REPLACE TABLE administrator
	(admin_id int NOT NULL AUTO_INCREMENT,
	username varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	email varchar(255),
	first_name varchar(255),
	last_name varchar(255),
	role_id int NOT NULL,
	creation_date timestamp,
  status_id int NOT NULL,
	PRIMARY KEY(admin_id),
  FOREIGN KEY(status_id) REFERENCES status(status_id),
	FOREIGN KEY(role_id) REFERENCES role(role_id));");
$create_sysadmin->execute();
$create_sysadmin->close();

//-----------------------------------------------------
// Populate Database Tables
//-----------------------------------------------------

/* Role */
$insert_role = $db_connection->prepare(
	"INSERT INTO role 
	(role_id, role_type) VALUES(?,?);");

$insert_role->bind_param("is", $role_id, $role_title);

$role_id = 1;
$role_title = "administrator";
$insert_role->execute();

$role_id = 2;
$role_title = "faculty";
$insert_role->execute();

$role_id = 3;
$role_title = "student";
$insert_role->execute();

$role_id = 4;
$role_title = "guest";
$insert_role->execute();

$insert_role->close();

/* Status */
$insert_status = $db_connection->prepare(
	"INSERT INTO status 
	(status_id, status_type) VALUES(?,?);");

$insert_status->bind_param("is", $status_id, $status_title);

$status_id = 1;
$status_title = "active";
$insert_status->execute();

$status_id = 2;
$status_title = "dormant";
$insert_status->execute();

$insert_status->close();

/* Administrator */
$insert_admin = $db_connection->prepare(
	"INSERT INTO administrator
	(admin_id,
  role_id,
  status_id,
	username,
	password,
	email,
	first_name,
	last_name) VALUES(?,?,?,?,?,?,?,?);");
$insert_admin->bind_param("iiisssss",
  $admin_id,
  $role_id,
  $status_id,
	$username,
	$password,
	$email,
	$first_name,
	$last_name);

$admin_id = 1;	
$role_id = 1;
$status_id = 1;
$username = "snow";
$password = crypt("winter", $salt);
$email = "snow@winter.edu";
$first_name = "john";
$last_name = "snow";
//$creation_date = date("Y-m-d H:i:s");
$insert_admin->execute();

$insert_admin->close();

//-----------------------------------------------------
// Finish Database Creation
//-----------------------------------------------------

/* Status Display */
echo 'The Database was successfully created';
/* Return to homepage after 5 seconds */
header( "refresh:5;url=/ug-advisor" );

/* ALWAYS CLOSE THE DB CONNECTION */
$db_connection->close();


?>