<?php
//======================================================================
// SEMESTER ADMIN
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-semester"; 

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
              <h1>Semester Administration</h1>
            </div>
            <div class="col-sm-3">
              <form action="" method="post">
              <!--  <a href="../admin/semester_add.php" class="btn btn-primary">Add Semester</a> -->
              </form>
            </div>
          </div> 
          <div class="row">
            <div class="col-sm-12">
          <!-- table of course list here -->
          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Semester ID</th>
                <th scope="col">Semester Type</th>
                <th scope="col">Edit</th>
              </tr>
            </thead>
            <tbody>
            <?php
                // Query Reference for Bind
                // Nothing to Reference

                // View Semeseter
                $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                // SQL Statement
                $semester_view = $db_connection->prepare("SELECT semester_id, semester_type
                FROM semester;");
                // Check Connection
                if ($semester_view === FALSE) {
                  $error = "Connection Failed";
                  die($db_connection->error);
                }
                // bind 
                //$student_view->bind_param();
                // execute
                $semester_view->execute();
                // results
                $result = $semester_view->get_result();

                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo '<tr>'; 
                    echo '<th scope="row">'.$row["semester_id"].'</th>';     
                    echo '<td>'.$row["semester_type"].'</td>';
                    echo '<td><form method="post" action="'.BASE_URL.'/admin/semester_edit.php">';
                    echo '<input type="hidden" name="edit_semester_id" value="'.$row["semester_id"].'">';
                    echo '<button type="submit" class="btn btn-link btn-sm"><i class="fas fa-archway"></i> edit</button>';
                    echo '</form></td>';
                    echo '</tr>';
                  }
                } else {
                  $error = "There was a problem showing the semester list.";
                };

                // Always Close the DB Connection
                $semester_view->close();
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
