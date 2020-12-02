<?php
//======================================================================
// STUDENT TERM REMOVE COURSE
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_student.php');


if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $take_row = $_POST['take_id'];

    //Check if form is empty
    if (!isset($take_row)) {
      $_SESSION['error'] = 'You must select a course to remove';
      header("location: " . BASE_URL . "/student/term.php");
  
    } else {
      $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      for($i=0;$i<count($take_row);$i++){
        $del_course = $db_connection->prepare("DELETE FROM take WHERE take_id = ? AND student_id = ?");
        $del_course->bind_param("ii", 
          $take_row[$i],
          $_SESSION['student_id']);

        $del_course->execute();
        if($del_course->affected_rows === 0) exit('No rows updated');
      }
    }
  $del_course->close();

  header("location: " . BASE_URL . "/student/term.php");
}
?>