<?php
//======================================================================
// COURSE ADMIN
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-course"; 

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
              <h1>Course Administration</h1>
            </div>
            <div class="col-sm-3">
              <form action="" method="post">
              <a href="../admin/course_add.php" class="btn btn-primary">Add Course</a>
              </form>
            </div>
          </div> 
          <div class="row">
            <div class="col-sm-12">
          <!-- table of course list here -->
          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Course ID</th>
                <th scope="col">Course Num</th>
                <th scope="col">Course Name</th>
                <th scope="col">Credits</th>
                <th scope="col">Dept_ID</th>
                <th scope="col">Status</th>
                <th scope="col">Edit</th>
              </tr>
            </thead>
            <tbody>
            <?php
                // Query Reference for Bind
                // Nothing to Reference

                // View Courses
                $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                // SQL statment
                $course_view = $db_connection->prepare("SELECT course_id, course_num, course_name, credits, dept_id, status_id
                FROM course NATURAL JOIN status;");
                // Check Connection
                if ($course_view === FALSE) {
                  $error = "Connection Failed";
                  die($db_connection->error);
                }
                // bind 
                //$course_view->bind_param();
                // execute
                $course_view->execute();
                // results
                $result = $course_view->get_result();

                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>'.$row["course_id"].'</td>';
                    echo '<td>'.$row["course_num"].'</td>';
                    echo '<td>'.$row["course_name"].'</td>';
                    echo '<td>'.$row["credits"].'</td>';
                    echo '<th scope="row">'.$row["dept_id"].'</th>';
                    echo '<td>'.$row["status_id"].'</td>';
                    echo '<td><form method="post" action="'.BASE_URL.'/admin/course_edit.php">';
                    echo '<input type="hidden" name="edit_course_id" value="'.$row["course_id"].'">';
                    echo '<button type="submit" class="btn btn-link btn-sm"><i class="fas fa-archway"></i> edit</button>';
                    echo '</form></td>';
                    echo '</tr>';
                  }
                } else {
                  $error = "There was a problem showing the students list.";
                };

                // Always Close the DB Connection
                $course_view->close();
              ?>

            </tbody>
          </table>
          </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
            <!-- Error Reporting -->
            <?php
              /* Error Message */
              if (isset($error)) {
                // uses bootstrap alert style for error messages
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
              }
              /* Message Information */
              if (isset($message)) {
                // uses bootstrap alert style for error messages
                echo '<div class="alert alert-info" role="alert">' . $message . '</div>';
              }
            ?>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
