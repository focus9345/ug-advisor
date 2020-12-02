<?php
//======================================================================
// ADMIN COURSE ADD
//======================================================================

include_once (realpath(dirname(__FILE__).'/path.php'));
include_once (ROOT_PATH . '/php/config.php');
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

$course_id = $_POST['course_id'];
$course_num = $_POST['course_num'];
$course_name = $_POST['course_name'];
$credits = $_POST['credits'];
$dept_id = $_POST['dept_id'];
$status_id = $_POST['status_id'];

$insert_course = $db_connection->prepare(
	"INSERT INTO course (
		course_id,
		course_num,
		course_name,
		credits, 
		dept_id,
		status_id) VALUES(?,?,?,?,?,?);");
$insert_course->bind_param("iisisi", 
  $course_id,
  $course_num,
  $course_name,
  $credits,
  $dept_id,
  $status_id);
$insert_course->execute();
$insert_course->close();

header("Location: ". BASE_URL ."/admin/course.php");

?>