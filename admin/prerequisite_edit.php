<?php
//======================================================================
// PREREQUISITE EDIT
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-prerequisite-edit"; 

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION['edit_prerequisite'] = $_POST['edit_prerequisite_id'];
  } else {
    $error = 'No Prerequisite ID selected.';
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
              <h1>Prerequisite Edits</h1>
            </div>
            <div class="col-sm-3">
                <a href="<?php echo BASE_URL ?>/admin/prerequisite.php" class="btn btn-primary">Back</a>
            </div>
          </div> 
          <div class="row">
            <div class="col-sm-12">
            <?php

              $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
              $editprerequisite = $db_connection->prepare("SELECT prerequisite_id, course_id, course_prerequisite_id FROM prerequisite WHERE prerequisite_id = ?");
              if ($editprerequisite === FALSE) {
                echo "Connection Failed";
                die($db_connection->error);
              }
              $editprerequisite->bind_param('s', $_SESSION['edit_prerequisite']);
              $editprerequisite->execute();
              $result = $editprerequisite->get_result();
              if($result->num_rows === 0) exit('No rows');
              while($row = $result->fetch_assoc()) {
                $prerequisite_id = $row['prerequisite_id'];
                $course_id = $row['course_id'];
                $course_prerequisite_id = $row['course_prerequisite_id'];
              }
              $editprerequisite->close();
            ?>

              <form action="<?php echo BASE_URL ?>/php/admin_prerequisite_edit.php" method="post">
                <fieldset>
                  <legend>Prerequisite:</legend>

                  <div class="form-group">
                    <label for="prerequisite_id">Prerequisite ID:</label>
                    <input type="text" class="form-control" id="prerequisite_id" name="prerequisite_id" value="<?php echo $prerequisite_id; ?>">
                  </div>

                  <div class="form-group">
                    <label for="course_id">Course ID:</label>
                    <input type="text" class="form-control" id="course_id" name="course_id" value="<?php echo $course_id; ?>">
                  </div>

                  <div class="form-group">
                    <label for="course_prerequisite_id">Course Prerequisite ID:</label>
                    <input type="text" class="form-control" id="course_prerequisite_id" name="course_prerequisite_id" value="<?php echo $course_prerequisite_id; ?>">
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
