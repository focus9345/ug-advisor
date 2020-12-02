<?php
//======================================================================
// FORGOT PASSWORD PAGE
//======================================================================

  /* Start The Session */
  session_start(); 

?>
<!doctype html>
<html lang="en">
  <head>
  <?php include_once (realpath(dirname(__FILE__).'/include/head.php')); ?>
  </head>
  <body>
  <?php include_once (realpath(dirname(__FILE__).'/include/header.php')); ?>
    <main role="main" class="container">
      <div class="row justify-content-sm-center">
        <div class="col-sm-4">
          <h1>Account Recovery</h1>
          <form action="./db/recovery.php" method="post">
            <div class="form-group">
              <input type="email" class="form-control" id="email" aria-describedby="email" placeholder="Enter Email" name="email" required>
            </div>
            <?php
              /* Error Message */
              if (isset($error)) {
                // uses bootstrap alert style for error messages
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
              }
            ?>
            <button type="submit" class="btn btn-primary">Next</button>
          </form>
          <hr />
          <p class="text-center">
            <small>
              <a href="<?php echo BASE_URL; ?>/create_account.php">Create An Account</a> | <a href="<?php echo BASE_URL; ?>">Login</a>
            </small>
          </p>
        </div>
      </div>
    </main>
    <?php include_once (realpath(dirname(__FILE__).'/include/footer.php')); ?>
  </body>
</html>
