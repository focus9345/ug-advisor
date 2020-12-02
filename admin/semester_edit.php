<?php
//======================================================================
// SEMESTER EDIT
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-semester-add";

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_SESSION['edit_semester'] = $_POST['edit_semester_id'];
  } else {
    $error = 'No Semster ID selected.';
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
              <h1>Semester Edits</h1>
            </div>
            <div class="col-sm-3">
                <a href="<?php echo BASE_URL ?>/admin/semester.php" class="btn btn-primary">Back</a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
            <?php

              $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
              $editsemester = $db_connection->prepare("SELECT semester_id, semester_type FROM semester WHERE semester_id = ?");
              if ($editsemester === FALSE) {
                echo "Connection Failed";
                die($db_connection->error);
              }
              $editsemester->bind_param('s', $_SESSION['edit_semester']);
              $editsemester->execute();
              $result = $editsemester->get_result();
              if($result->num_rows === 0) exit('No rows');
              while($row = $result->fetch_assoc()) {
                $semester_id = $row['semester_id'];
                $semester_type = $row['semester_type'];
              }
              $editsemester->close();
            ?>

              <form action="<?php echo BASE_URL ?>/php/admin_semester_edit.php" method="post">
                <fieldset>
                  <legend>Semester:</legend>

                  <div class="form-group">
                    <label for="dept_id">Semester ID:</label>
                    <input type="text" class="form-control" id="semester_id" name="semester_id" value="<?php echo $semester_id; ?>">
                  </div>

                  <div class="form-group">
                    <label for="dept_id">Semester Type:</label>
                    <input type="text" class="form-control" id="semester_type" name="semester_type" value="<?php echo $semester_type; ?>">
                  </div>

                  <div class="form-group">
                    <label for="private_status">Private Status</label><br>
                    <?php
                      /* Shows either public or private status */
                      $check_active = '';
                      $check_dormant = '';

                      if(isset($semester_id)){
                        if($semester_id === 1) {
                          $check_active = 'checked';
                        }elseif($semester_id === 2) {
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
