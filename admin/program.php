<?php
//======================================================================
// PROGRAM ADMIN
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-program"; 

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
              <h1>Program Administration</h1>
            </div>
            <div class="col-sm-3">
              <form action="" method="post">
                <a href="../admin/program_add.php" class="btn btn-primary">Add Program</a>
              </form>
            </div>
          </div> 
          <div class="row">
            <div class="col-sm-12">
          <!-- table of course list here -->
          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Program Name</th>
                <th scope="col">Creation Date</th>
                <th scope="col">Department ID</th>
                <th scope="col">Status ID</th>
                <th scope="col">Edit</th>
              </tr>
            </thead>
            <tbody>
            <?php
                // Query Reference for Bind
                // Nothing to Reference

                // View Programs
                $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                // SQL Statement
                $program_view = $db_connection->prepare("SELECT program_id, program_name, creation_date, dept_id, status_id
                FROM program;");
                // Check Connection
                if ($program_view === FALSE) {
                  $error = "Connection Failed";
                  die($db_connection->error);
                }
                // bind 
                //$student_view->bind_param();
                // execute
                $program_view->execute();
                // results
                $result = $program_view->get_result();

                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo '<tr>'; 
                    echo '<th scope="row">'.$row["program_id"].'</th>';
                    echo '<td>'.$row["program_name"].'</td>';     
                    echo '<td>'.$row["creation_date"].'</td>';
                    echo '<td>'.$row["dept_id"].'</td>';
                    echo '<td>'.$row["status_id"].'</td>';
                    echo '<td><form method="post" action="'.BASE_URL.'/admin/program_edit.php">';
                    echo '<input type="hidden" name="edit_program_id" value="'.$row["program_id"].'">';
                    echo '<button type="submit" class="btn btn-link btn-sm"><i class="fas fa-archway"></i> edit</button>';
                    echo '</form></td>';
                    echo '</tr>';
                  }
                } else {
                  $error = "There was a problem showing the program list.";
                };

                // Always Close the DB Connection
                $program_view->close();
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
