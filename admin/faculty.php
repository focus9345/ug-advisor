<?php
//======================================================================
// FACULTY ADMIN
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_admin.php');

  /* Page Name */
  $page_name = "admin-faculty";

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
              <h1>Faculty Administration</h1>
            </div>        
          </div> 
          <div class="row">
            <div class="col-sm-12">
          <!-- table of faculty list here -->
          <table class="table table-striped">
            <thead class="thead-dark">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Faculty Name</th>
                <th scope="col">User ID</th>
                <th scope="col">Dept ID</th>
                <th scope="col">Status ID</th>
              <tr>
            <thead>
            <tbody>

              <?php
                // Query Reference for Bind
                $role = 2;
                $status = 1;

                // View Faculty
                $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                // SQL statment
                $faculty_view = $db_connection->prepare("SELECT faculty_id, user_id, dept_id, status_id 
                  FROM faculty NATURAL JOIN user 
                  WHERE role_id = ? AND status_id = ?;");
                // Check Connection
                if ($faculty_view === FALSE) {
                  $error = "Connection Failed";
                  die($db_connection->error);
                }
                // bind 
                $faculty_view->bind_param('ii', $role, $status);
                // execute
                $faculty_view->execute();
                // results
                $result = $faculty_view->get_result();

                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<th scope="row">'.$row["faculty_id"].'</th>';
                    echo '<td scope="row"> [Faculty Name Holder] </td>'; 
                    echo '<td>'.$row["user_id"].'</td>';
                    echo '<td scope="row">'.$row["dept_id"].'</td>';
                    echo '<td>'.$row["status_id"].'</td>';

                    echo '</tr>';
                  }
                } else {
                  $error = "There was a problem showing the students list.";
                };

                // Always Close the DB Connection
                $faculty_view->close();
                
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

        </div> <!-- end col-sm-9 -->
      </div> <!-- end row -->
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
