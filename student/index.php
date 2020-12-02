<?php
//======================================================================
// STUDENT DASHBOARD PAGE
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_student.php');

  /* Page Name */
  $page_name = "student";

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
          <h1>Graduation Map</h2>

          <?php
            //-----------------------------------------------------
            // Student Graduation Map
            //-----------------------------------------------------

            $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $student_detail = $db_connection->prepare("SELECT first_name, last_name, student_id
                FROM user NATURAL JOIN student
                WHERE user_id = ?;");
            // Check Connection
            if ($student_detail === FALSE) {
              $error = "Connection Failed";
              die($db_connection->error);
            }
            // bind
            $student_detail->bind_param("s", $user_id);
            $student_detail->execute();
            // results
            $result = $student_detail->get_result();

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $_SESSION['student_id'] = $row["student_id"] ;

                echo '<div class="row justify-content-sm-center">';
                echo '<div class="col-md-6">';
                echo '<h3 class="student_name">'. $row["first_name"] . ' ' . $row["last_name"] . '</h3>';
                echo '</div>';
                echo '<div class="col-md-6">';
                echo 'Advisor: <a href="mailto:#"><i class="far fa-envelope"></i></a>';
                echo '<br> Studnet Id: '. $_SESSION['student_id'];
                echo '</div>';
                echo '</div>';

              }
            }

            $student_detail->close();

            // create the first term map
            $student_term_map = $db_connection->prepare("SELECT 
              year_id,
              semester_id,
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
              WHERE student_id = ?
              ORDER BY year_id ASC, semester_id DESC;");
            // Check Connection
            if ($student_term_map === FALSE) {
              $error = "Connection Failed";
              die($db_connection->error);
            }
            // bind
            $student_term_map->bind_param("s", $_SESSION['student_id']);
            // $student_term_map->bind_param("s", $user_id);
            $student_term_map->execute();
            // results
            $result = $student_term_map->get_result();
            $rowcount = mysqli_num_rows($result);

            $array = array();
            if ($result->num_rows > 0) {
              //$row = $result->fetch_assoc();
              while($row = $result->fetch_assoc()) {
                $array[] = $row;
              }
            }
            
            // krsort($array);

            echo 'Current number of courses: ' . count($array) . '<br>'; 
            echo 'Total number of credits: ' . '<br>';

            //$previousYear = null;
            //$previousSemester = null;
            

            for($i=0; $i<=count($array); $i++){

              // seperate years
              if ($i == 0) {
                echo '<div class="row justify-content-sm-center">';
              } elseif (isset($array[$i-1]["year"]) and isset($array[$i-1]["semester"]) and isset($array[$i+1]["year"])) {
                if ($array[$i-1]["year"] == $array[$i]["year"] and $array[$i-1]["semester"] != $array[$i]["semester"]) {
                  echo '<div class="row justify-content-sm-center">';
                }
              }

              // seperate terms
              if ($i == 0) {
                echo '<div class="col-sm-6">';
                echo '<div class="card">';
                echo '<div class="card-header">';
                echo $array[$i]["year"] . ' | ' . $array[$i]["semester"] . ' | <span class="badge badge-primary badge-pill text-uppercase">' . $array[$i]["status"] . '</span>' ;
                echo '</div>'; // end card-header
                echo '<ul class="list-group list-group-flush">';
              } elseif (isset($array[$i-1]["semester"]) and isset($array[$i+1]["semester"])) {
                if ($array[$i-1]["semester"] != $array[$i]["semester"]) {
                  echo '<div class="col-sm-6">';
                  echo '<div class="card">';
                  echo '<div class="card-header">';
                  echo $array[$i]["year"] . ' | ' . $array[$i]["semester"] . ' | <span class="badge badge-primary badge-pill text-uppercase">' . $array[$i]["status"] . '</span>' ;
                  echo '</div>'; // end card-header
                  echo '<ul class="list-group list-group-flush">';
                }
              }

              // always print out
              if (isset($array[$i])) {
                echo '<li class="list-group-item">';
                echo '<div class="d-flex flex-nowrap justify-content-between">';
                echo '<div class="p-2 align-self-center">' . $array[$i]["dept"] . '</div>';
                echo '<div class="p-2 align-self-center">' . $array[$i]["course"] . '</div>';
                echo '<div class="p-2 align-self-center">' . $array[$i]["course_name"] . '</div>'; 
                echo '<div class="p-2 align-self-center"><span class="badge badge-info badge-pill pill-big">' . $array[$i]["credits"] . '</span></div>';
                echo '<div class="p-2 align-self-center"><span class="badge badge-secondary badge-pill pill-big ">' . $array[$i]["grade"] . '</span></div>';
                echo '</div>'; // end d-flex
                echo '</li>'; // end list-group-item
                $year = $array[$i]["year_id"];
                $semester = $array[$i]["semester_id"];
              }

              // end seperate terms
              if (empty($array[$i]["semester"] )) {
                echo '</ul>'; // end list-group
                echo '<div class="card-footer">';
                // view the term to make edits
                echo '<form method="post" action="'.BASE_URL.'/student/term.php">';
                echo '<input type="hidden" name="term_year" value="'.$year.'">';
                echo '<input type="hidden" name="term_semester" value="'.$semester.'">';
                echo '<button type="submit" class="btn btn-primary"><i class="far fa-eye"></i> View</button>';
                echo '</form>';

                echo '</div>'; // end card-footer
                echo '</div>'; // end card
                echo '</div>'; // end col
              } elseif (isset($array[$i+1]["semester"])) {
                if ($array[$i+1]["semester"] != $array[$i]["semester"]) {
                  echo '</ul>'; // end list-group
                  echo '<div class="card-footer">';
                  echo '<form method="post" action="'.BASE_URL.'/student/term.php">';
                  echo '<input type="hidden" name="term_year" value="'.$year.'">';
                  echo '<input type="hidden" name="term_semester" value="'.$semester.'">';
                  echo '<button type="submit" class="btn btn-primary"><i class="far fa-eye"></i> View</button>';
                  echo '</form>';
                  echo '</div>'; // end card-footer
                  echo '</div>'; // end card
                  echo '</div>'; // end col
                }
              }
              
              // end seperate years
              if (empty($array[$i]["year"] )) {
                echo '</div>'; // end row
              } elseif (isset($array[$i+1]["year"]) and isset($array[$i+1]["semester"])) {
                if ($array[$i+1]["year"] == $array[$i]["year"] and $array[$i+1]["semester"] != $array[$i]["semester"]) {
                  echo '</div>'; // end row
                }
              }

            } 
             
            // Close the mysql connection
            mysqli_close($db_connection);
            
          ?>

          <?php include_once (ROOT_SRC_PATH . '/error_rprt.php'); ?>

          <!-- GRADUATION  MAP -->

          
          <hr>
        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
