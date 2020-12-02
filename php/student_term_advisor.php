<?php
//======================================================================
// STUDENT TERM TO ADVISOR
//======================================================================

/*
  Sends the student term to the advisor for approval
*/

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_student.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $take_status_id = 3;
  $take_row = $_POST['take_id'];
  
  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  for($i=0;$i<count($take_row);$i++){
    $update_take = $db_connection->prepare("UPDATE take SET take_status_id = ? WHERE take_id = ?;");
    $update_take->bind_param("ii", 
      $take_status_id,
      $take_row[$i]
      );
    $update_take->execute();

    if($update_take->affected_rows === 0) exit('No rows updated');
  }
  
  $update_take->close();
  header("location: " . BASE_URL . "/student");
  
}
?>