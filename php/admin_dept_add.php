<?php

include_once (realpath(dirname(__FILE__).'/path.php'));
include_once (ROOT_PATH . '/php/config.php');
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

$dept_id = $_POST['dept_id'];
$dept_name = $_POST['dept_name'];
$status_id = $_POST['status_id'];

$insert_dept = $db_connection->prepare(
	"INSERT INTO department (
		dept_id, 
		dept_name,
		status_id) VALUES(?,?,?);");
$insert_dept->bind_param("ssi", 
  $dept_id,
	$dept_name,
	$status_id);
$insert_dept->execute();
$insert_dept->close();

header("Location: ". BASE_URL ."/admin/department.php");

?>