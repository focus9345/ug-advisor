<?php
//======================================================================
// ADVISOR DASHBOARD PAGE
//======================================================================
  /* Quick Paths */
  /* note the 2 after __FILE__, because it's 2 directories deep */
  include_once (realpath(dirname(__FILE__, 2).'/php/session.php'));
  /* Check Role */
  include_once (ROOT_SRC_PATH .'/check_advisor.php');
  /* Page Name */
  $page_name = "advisor";
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
          <h1>Advisor </h1>
          <!-- Students -->
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Contact</th>
                <th scope="col">Take Status</th>
                <th scope="col">View Record</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  // Query Reference for Bind
                  // Nothing to Reference

                  // View advisor
                  $db_connection->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                  // SQL statemenCourset
                  $advisor_detail = $db_connection->prepare("SELECT student_id, advisor.faculty_id, user.first_name, user.last_name, user.email
                  FROM advisor
                  NATURAL JOIN student
                  NATURAL JOIN user
                  INNER JOIN faculty on advisor.faculty_id = faculty.faculty_id
                  WHERE faculty.user_id = ?");
                  // Check Connection

                //  WHERE advisor.faculty_id = ?

                  if ($advisor_detail === FALSE) {
                    $error = "Connection Failed";
                    die($db_connection->error);
                  }
                  // bind
                  $advisor_detail->bind_param("s", $user_id);
                  // execute
                  $advisor_detail->execute();
                  // results

                    $result = $advisor_detail->get_result();
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      echo '<tr>';
                      echo '<td>'.$row["first_name"].'</td>';
                      echo '<td>'.$row["last_name"].'</td>';
                      echo '<td><a href="mailto:'.$row["email"].'"><i class="far fa-envelope"></i></a></td>';
                      echo '<td><i class="fa fa-flag" aria-hidden="true"></i></td>';
                      echo '<td><form action="//" type="post">';
                      echo '<button type="submit" value="DoIt">View Record</button>';
                      echo '</form>';
                      echo '</td>';
                      echo '</form></td>';
                      echo '</tr>';
                    }
                  } else {
                    $error = "There was a problem showing the advisor list.";
                  };

                  // Always Close the DB Connection
                  $advisor_detail->close();
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
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
