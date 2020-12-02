<?php
//======================================================================
// ADMIN PROGRAM EDIT
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_admin.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $update_program = $db_connection->prepare("UPDATE program SET program_id = ?, program_name = ?, dept_id = ?, status_id = ? WHERE program_id = ?");
  $update_program->bind_param("issis",
    $_POST['program_id'],
    $_POST['program_name'],
    $_POST['dept_id'],
    $_POST['status_id'],
    $_SESSION['edit_program']);

  $update_program->execute();
  if($update_program->affected_rows === 0) exit('No rows updated');
  $update_program->close();

header("Location: ". BASE_URL ."/admin/program.php");
}

?>
