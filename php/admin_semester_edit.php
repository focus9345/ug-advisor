<?php
//======================================================================
// ADMIN SEMESTER EDIT
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $update_semester = $db_connection->prepare("UPDATE semester SET semester_id = ?, semester_type = ? WHERE semester_id = ?");
  $update_semester->bind_param("iss",
    $_POST['semester_id'],
    $_POST['semester_type'],
    $_SESSION['edit_semester']);

  $update_semester->execute();
  if($update_semester->affected_rows === 0) exit('No rows updated');
  $update_semester->close();

header("Location: ". BASE_URL ."/admin/semester.php");
}

?>
