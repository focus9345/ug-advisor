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
          
              <h1>Student <?php echo $user_name ?></h1>
            
              <a href="<?php echo BASE_URL ?>/student/term.php" class="btn btn-info">back <i class="fas fa-undo-alt"></i></a>
            

          <!-- CURRENT TERM-->

          <div class="row justify-content-sm-center">
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

            $take_id_array = array();

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {

                if ($counter == 1) {
                  echo '<div class="col-sm-6">';
                  //echo '<form method="post" action="'.BASE_URL.'/php/student_term_remove.php">';
                  echo '<div class="card">';
                  echo '<div class="card-header">';
                  echo '<strong>' . $row["semester"] . ' - ' . $row["year"] . '</strong><br>';
                  echo '<small>Number of Courses:' . $rowcount . '</small>';
                  echo '</div>';
                  echo '<ul class="list-group list-group-flush">';
                }
                $take_id_array[] = $row["take_id"];
                echo '<li class="list-group-item">';
                echo '<div class="d-flex flex-nowrap justify-content-between">';
                echo '<div class="p-2 align-self-center">' . $row["dept"] . '</div>';
                echo '<div class="p-2 align-self-center">' . $row["course"] . '</div>';
                echo '<div class="p-2 align-self-center">' . $row["course_name"] . '</div>'; 
                echo '<div class="p-2 align-self-center"><span class="badge badge-info badge-pill pill-big">' . $row["credits"] . '</span></div>';
                echo '<div class="p-2 align-self-center"><span class="badge badge-secondary badge-pill pill-big ">' . $row["grade"] . '</span></div>';
                echo '</div>'; // end d-flex
                echo '</li>'; // end list-group-item
                $credit_total += $row["credits"];

                if ($counter == $rowcount) {
                  //echo '</ul>'; // end list-group
                  echo '</ul>'; // end card-body
                  echo '<div class="card-footer">';
                  echo 'Total Credits: '. $credit_total . '<br>';
                  // Form will change status

                  
                  echo '<form method="post" action="'.BASE_URL.'/php/student_term_advisor.php">';
                  foreach($take_id_array as $value)
                  {
                    echo '<input type="hidden" name="take_id[]" value="'. $value. '">';
                  }
                  echo '<button type="submit" class="btn btn-primary">Send to Advisor</button>';
                  echo '</form>';
            
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
          </div>

        </div>
        <?php include_once (ROOT_SRC_PATH . '/error_rprt.php'); ?>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
