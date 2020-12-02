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

  /* Start The Session */
  session_start();

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
          <h1>Student [NAME]</h1>
          <h2>[ YYYY - Semester ]</h2>

          <!-- CURRENT TERM-->

          <div class="row">
            <div class="col-sm-6">
              <div class="card">
                <div class="card-header">
                   Term [total credits]
                </div>
                <div class="card-body">
                <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1">
                  <label class="form-check-label" for="exampleCheck1">Course Name 3</label>
                </div>
                </div>
                <div class="card-footer">
                <a href="#" class="btn btn-primary">Remove Class</a>
                </div>
              </div><!-- /card -->
            </div><!-- /col 6 -->
           

            <div class="row justify-content-sm-center">
            <div class="col-sm-3">
            
           </div>
            </div>
          <hr>
        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
