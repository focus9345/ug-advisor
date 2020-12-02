<?php

include_once (realpath(dirname(__FILE__).'/path.php'));
include_once (ROOT_PATH . '/php/config.php');
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

$prerequisite_id = $_POST['prerequisite_id'];
$course_id = $_POST['course_id'];
$course_prerequisite_id = $_POST['course_prerequisite_id'];

$insert_prerequisite = $db_connection->prepare(
	"INSERT INTO prerequisite (
		prerequisite_id, 
		course_id,
		course_prerequisite_id) VALUES(?,?,?);");
$insert_prerequisite->bind_param("iii", 
  	$prerequisite_id,
	$course_id,
	$course_prerequisite_id);
$insert_prerequisite->execute();
$insert_prerequisite->close();

header("Location: ". BASE_URL ."/admin/prerequisite.php");

?>