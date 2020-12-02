<?php
//======================================================================
// ADMIN COURSE EDIT
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
 
  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $update_course = $db_connection->prepare("UPDATE course SET course_id = ?, course_num = ?, course_name = ?, credits = ?, dept_id = ?, status_id = ? WHERE course_id = ?");
  $update_course->bind_param("iisisii", 
    $_POST['course_id'], 
    $_POST['course_num'], 
    $_POST['course_name'], 
    $_POST['credits'], 
    $_POST['dept_id'], 
    $_POST['status_id'],
    $_SESSION['edit_course']);

  $update_course->execute();
  if($update_course->affected_rows === 0) exit('No rows updated');
  $update_course->close();

  header("location: " . BASE_URL . "/admin/course.php");
}


?>