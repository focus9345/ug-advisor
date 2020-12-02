<?php
//======================================================================
// ADMIN DEPARTMENT EDIT
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
 
  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $update_dept = $db_connection->prepare("UPDATE department SET dept_id = ?, dept_name = ?, status_id = ? WHERE dept_id = ?");
  $update_dept->bind_param("ssis", 
    $_POST['dept_id'], 
    $_POST['dept_name'], 
    $_POST['status_id'],
    $_SESSION['edit_dept']);

  $update_dept->execute();
  if($update_dept->affected_rows === 0) exit('No rows updated');
  $update_dept->close();

  header("location: " . BASE_URL . "/admin/department.php");
}

?>