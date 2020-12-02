<?php
//======================================================================
// ADMIN PREREQUISITE EDIT
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
 
  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $update_prerequisite = $db_connection->prepare("UPDATE prerequisite SET prerequisite_id = ?, course_id = ?, course_prerequisite_id = ? WHERE prerequisite_id = ?");
  $update_prerequisite->bind_param("iiis", 
    $_POST['prerequisite_id'], 
    $_POST['course_id'], 
    $_POST['course_prerequisite_id'],
    $_SESSION['edit_prerequisite']);

  $update_prerequisite->execute();
  if($update_prerequisite->affected_rows === 0) exit('No rows updated');
  $update_prerequisite->close();

  header("location: " . BASE_URL . "/admin/prerequisite.php");
}

?>