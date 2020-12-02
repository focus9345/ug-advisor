<?php
//======================================================================
// STUDENT TERM ADD COURSE
//======================================================================

include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
/* Check Role */
include_once (ROOT_SRC_PATH .'/check_student.php');
if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $grade_id = 14;
  $state_id = 3;
  $take_status_id = 1;

  $course_row = $_POST['course_id'];

  //Check if form is empty
  if (!isset($course_row)) {
    $_SESSION['error'] = 'You must select a course to add';
    header("location: " . BASE_URL . "/student/term.php");

  } else {
    $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    for($i=0;$i<count($course_row);$i++){
      $insert_tkcourse = $db_connection->prepare("INSERT INTO take (
        course_id,
        grade_id,
        semester_id,
        year_id,
        state_id,
        take_status_id, 
        student_id) VALUES(?,?,?,?,?,?,?);");
      $insert_tkcourse->bind_param("iiiiiii", 
        $course_row[$i],
        $grade_id,
        $_SESSION['semester'],
        $_SESSION['year'],
        $state_id,
        $take_status_id,
        $_SESSION['student_id']);
      $insert_tkcourse->execute();

      if($insert_tkcourse->affected_rows === 0) exit('No rows updated');
    }
  }
  
  $insert_tkcourse->close();
  header("location: " . BASE_URL . "/student/term.php");

}
?>