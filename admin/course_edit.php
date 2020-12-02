<?php
//======================================================================
// COURSE EDIT
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-course-edit"; 

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION['edit_course'] = $_POST['edit_course_id'];
  } else {
    $error = 'No Course ID selected.';
  }

?>
<!doctype html>
<html lang="en">
  <head>
  <?php include_once (ROOT_PATH . '/include/head.php'); ?>
  </head>
  <body class="<?php echo $page_name; ?>">
  <?php include_once (ROOT_PATH . '/include/header.php'); ?>
    <main role="main" class="container">
      <div class="row justify-content-sm-center">
        <div class="col-sm-9">
          <!-- Content for the webpage starts here -->
          <div class="row">
            <div class="col-sm-9">
              <h1>Course Edit</h1>
            </div>
            <div class="col-sm-3">
                <a href="<?php echo BASE_URL ?>/admin/course.php" class="btn btn-primary">Back</a>
            </div>
          </div> 
          <div class="row">
            <div class="col-sm-12">
            <?php

              $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
              $editcourse = $db_connection->prepare("SELECT course_id, course_num, course_name, credits, dept_id, status_id FROM course WHERE course_id = ?");
              if ($editcourse === FALSE) {
                echo "Connection Failed";
                die($db_connection->error);
              }
              $editcourse->bind_param('s', $_SESSION['edit_course']);
              $editcourse->execute();
              $result = $editcourse->get_result();
              if($result->num_rows === 0) exit('No rows');
              while($row = $result->fetch_assoc()) {
                $course_id = $row['course_id'];
                $course_num = $row['course_num'];
                $course_name = $row['course_name'];
                $credits = $row['credits'];
                $dept_id = $row['dept_id'];
                $status_id = $row['status_id'];

              }
              $editcourse->close();
            ?>

              <form action="<?php echo BASE_URL ?>/php/admin_course_edit.php" method="post">
                <fieldset>
                  <legend>Course Information:</legend>

                  <div class="form-group">
                    <label for="course_id">Course ID:</label>
                    <input type="text" class="form-control" id="course_id" name="course_id" value="<?php echo $course_id; ?>">
                  </div>

                  <div class="form-group">
                    <label for="course_num">Course Num:</label>
                    <input type="text" class="form-control" id="course_num" name="course_num" value="<?php echo $course_num; ?>">
                  </div>

                  <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo $course_name; ?>">
                  </div>

                  <div class="form-group">
                    <label for="credits">Credits:</label>
                    <input type="text" class="form-control" id="credits" name="credits" value="<?php echo $credits; ?>">
                  </div>

                  <div class="form-group">
                    <label for="dept_id">Department ID:</label>
                    <input type="text" class="form-control" id="dept_id" name="dept_id" value="<?php echo $dept_id; ?>">
                  </div>

                  <div class="form-group">
                    <label for="private_status">Private Status:</label><br>
                    <?php
                      /* Shows either public or private status */
                      $check_active = '';
                      $check_dormant = '';

                      if(isset($status_id)){
                        if($status_id === 1) {
                          $check_active = 'checked';
                        }elseif($status_id === 2) {
                          $check_dormant = 'checked';
                        }
                      } else {
                        $error = 'error detected: nothing is selected';
                      }
                    ?>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status_id" id="inlineRadio1" value="1" <?php echo $check_active; ?>>
                      <label class="form-check-label" for="inlineRadio1">active</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status_id" id="inlineRadio2" value="2" <?php echo $check_dormant; ?>>
                      <label class="form-check-label" for="inlineRadio2">dormant</label>
                    </div>
                  </div>
                  
                </fieldset>
                <button type="submit" class="btn btn-primary">UPDATE</button>
              </form>

              <?php include_once (ROOT_SRC_PATH . '/error_rprt.php'); ?>
           
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
