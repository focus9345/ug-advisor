<?php
//======================================================================
// LOGIN PAGE
//======================================================================
  /* Quick Paths */
  include_once (realpath(dirname(__FILE__).'/php/path.php'));


  /* Page Name */
  $page_name = "home";

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
    <main role="main" class="container">
      <div class="row justify-content-sm-center">
        <div class="col-sm-4">
          <h1>Login</h1>
          <form action="<?php echo BASE_URL; ?>/php/authenticate.php" method="post">
            <div class="form-group">
              <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="username" placeholder="Enter username" name="username" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter Password" name="password" required>
            </div>
            <?php include_once (ROOT_PATH . '/php/error_rprt.php'); ?>
            <button type="submit" class="btn btn-primary">Log In</button>
          </form>
          
        </div>
      </div>
    </main>
    <?php include_once (ROOT_PATH . '/include/footer.php'); ?>
  </body>
</html>
