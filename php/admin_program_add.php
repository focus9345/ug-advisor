<?php

include_once (realpath(dirname(__FILE__).'/path.php'));
include_once (ROOT_PATH . '/php/config.php');
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

$program_id = $_POST['program_id'];
$program_name = $_POST['program_name'];
$dept_id = $_POST['dept_id'];
$status_id = $_POST['status_id'];

$insert_program = $db_connection->prepare(
	"INSERT INTO program
		(program_id,
		program_name,
		dept_id,
		status_id) VALUES(?,?,?,?);");
$insert_program->bind_param("issi",
$program_id,
$program_name,
$dept_id,
$status_id);
$insert_program->execute();
$insert_program->close();

header("Location: ". BASE_URL ."/admin/program.php");

?>
