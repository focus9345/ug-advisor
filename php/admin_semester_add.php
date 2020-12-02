<?php

include_once (realpath(dirname(__FILE__).'/path.php'));
include_once (ROOT_PATH . '/php/config.php');
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

$semester_id = $_POST['semester_id'];
$semester_type = $_POST['semester_type'];

$insert_semester = $db_connection->prepare(
	"INSERT INTO semester (
		semester_id,
		semester_type) VALUES(?,?);");
$insert_semester->bind_param("is",
  $semester_id,
	$semester_type);
$insert_semester->execute();
$insert_semester->close();

header("Location: ". BASE_URL ."/admin/semester.php");

?>
