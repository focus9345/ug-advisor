<?php
//======================================================================
// STUDENT TERM PAGE
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_student.php');
  /* Page Name */
  $page_name = "term";

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION['year'] = $_POST['term_year'];
    $_SESSION['semester'] = $_POST['term_semester'];
  } else {
    if (!isset($_SESSION['year']) or !isset($_SESSION['semester'])) {
      $error = 'There was a problem, year: ' . $_SESSION['year'] . ' semester: ' . $_SESSION['semester'];
    }
  }

?>
<!doctype html>
<html lang="en">
  <head>
  <?php include_once (ROOT_PATH . '/include/head.php'); ?>
  </head>
  <body class="<?php echo $page_name; ?>">
  <?php include_once (ROOT_PATH . '/include/header.php'); ?>
    <main role="main" class="container text-center">
      <div class="row justify-content-sm-center">
        <div class="col-sm-9">
          <!-- Content for the webpage starts here -->

          <div class="row">
            <div class="col-sm-6">
              <h1>Student <?php echo $user_name ?></h1>
            </div>
            <div class="col-sm-6">
              <a href="<?php echo BASE_URL ?>/student" class="btn btn-info">back <i class="fas fa-undo-alt"></i></a>
            </div>
           </div> 

          <div class="row">
          <!-- CURRENT TERM-->
          <?php
            $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $student_term_detail = $db_connection->prepare("SELECT 
              take_id,
              course_id,
              year_type AS year, 
              semester_type AS semester, 
              take_status_type AS status, 
              dept_id AS dept, 
              course_num AS course, 
              course_name,
              grade_type AS grade,
              credits
              FROM take 
                NATURAL JOIN semester 
                NATURAL JOIN years 
                NATURAL JOIN take_status
                NATURAL JOIN grade 
                NATURAL JOIN course
              WHERE student_id = ? AND year_id = ? AND semester_id = ?;");
            // Check Connection
            if ($student_term_detail === FALSE) {
              $error = "Connection Failed";
              die($db_connection->error);
            }
            // bind
            $student_term_detail->bind_param('iii', $_SESSION['student_id'], $_SESSION['year'], $_SESSION['semester']);
            $student_term_detail->execute(); 

            $result = $student_term_detail->get_result();
            $rowcount = mysqli_num_rows($result);
            $credit_total = 0;
            $counter = 1;

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {

                if ($counter == 1) {
                  echo '<div class="col-sm-6">';
                  echo '<form method="post" action="'.BASE_URL.'/php/student_term_remove.php">';
                  echo '<div class="card">';
                  echo '<div class="card-header">';
                  echo '<strong>' . $row["semester"] . ' - ' . $row["year"] . '</strong><br>';
                  echo '<small>Number of Courses:' . $rowcount . '</small>';
                  echo '</div>';
                  echo '<div class="card-body">';
                }
                
                echo '<div class="form-group form-check text-left">';
                echo '<input type="checkbox" class="form-check-input" id="'.$row["take_id"].'" name="take_id[]" value="'.$row["take_id"].'">';
                echo '<label class="form-check-label small" for="'.$row["course_id"].'">';
                echo $row["dept"] . ' ';
                echo $row["course"] . ' - ';
                echo $row["course_name"] . ' - ';
                echo $row["credits"];
                echo '</label>'; // end form-check-label
                echo '</div>'; // end form-group
                $credit_total += $row["credits"];

                if ($counter == $rowcount) {
                  //echo '</ul>'; // end list-group
                  echo '</div>'; // end card-body
                  echo '<div class="card-footer">';
                  echo 'Total Credits: '. $credit_total . '<br>';
                  echo '<button type="submit" class="btn btn-primary">Remove Class</button>';
                  echo '</div>'; // end card-footer
                  echo '</div>'; // end card
                  echo '</form>'; // end form
                  echo '</div>'; // end col
                }
                
                $counter += 1;
            
            }
          }
            $student_term_detail->close();
          ?>

          <?php

            $course_dept = 'CSC';

            $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $student_term_detail = $db_connection->prepare("SELECT 
              course.course_id,
              course.dept_id AS dept, 
              course.course_num AS course, 
              course.course_name,
              course.credits
              FROM take 
                RIGHT JOIN course 
                ON take.course_id = course.course_id
              WHERE take.student_id IS NULL AND course.dept_id = ?
              ORDER BY course.course_num;");
            // Check Connection
            if ($student_term_detail === FALSE) {
              $error = "Connection Failed";
              die($db_connection->error);
            }
            // bind
            $student_term_detail->bind_param('s', $course_dept);
            $student_term_detail->execute(); 

            $result = $student_term_detail->get_result();
            $rowcount = mysqli_num_rows($result);
            
            $counter = 1;

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {

                if ($counter == 1) {
                  echo '<div class="col-sm-6">';
                  echo '<form method="post" action="'.BASE_URL.'/php/student_term_add.php">';
                  echo '<div class="card">';
                  echo '<div class="card-header">';
                  echo '<strong> Add a Course </strong><br>';
                  echo '<small>Number of Courses:' . $rowcount . '</small>';
                  echo '</div>';
                  echo '<div class="card-body">';
                }
                
                echo '<div class="form-group form-check text-left">';
                echo '<input type="checkbox" class="form-check-input" id="'.$row["course_id"].'" name="course_id[]" value="'.$row["course_id"].'">';
                echo '<label class="form-check-label small" for="'.$row["course_id"].'">';
                echo $row["dept"] . ' ';
                echo $row["course"] . ' - ';
                echo $row["course_name"] . ' - ';
                echo $row["credits"];
                echo '</label>'; // end form-check-label
                echo '</div>'; // end form-group
                

                if ($counter == $rowcount) {
                  //echo '</ul>'; // end list-group
                  echo '</div>'; // end card-body
                  echo '<div class="card-footer">';
                  echo $credit_total . '<br>';
                  if ($credit_total < 21) {
                    echo '<button type="submit" class="btn btn-primary">Add Class</button>';
                  } else {
                    echo '<div class="alert alert-warning" role="warning">Max credit total reached: ' . $credit_total . '</div>';
                  }
                  echo '</div>'; // end card-footer
                  echo '</div>'; // end card
                  echo '</form>'; // end form
                  echo '</div>'; // end col
                }
                
                $counter += 1;
            
            }
          }
            $student_term_detail->close();
          ?>

          </div> <!-- end row -->

          <?php include_once (ROOT_SRC_PATH . '/error_rprt.php'); ?>
          
          <div class="row justify-content-sm-center">
            <div class="col-sm-12">
            <a href="<?php echo BASE_URL ?>/student/check-term.php" class="btn btn-primary">Save Term Update</a>
            </div>
          </div>
          <hr>
        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
