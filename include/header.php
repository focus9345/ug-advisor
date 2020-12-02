<!-- Header is consitant for all webpages 
================================================== -->
<header class="header-breath">
  <!-- Navigation bar is fixed to top page -->
  <nav class="navbar fixed-top navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Undergraduate Advisor</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        

        <?php
          /* User Navigation for general users or administrators */
          if (!isset($_SESSION['user_role'])) {
            echo '<a href="index.php"><span class="navbar-text">Login</span></a>';
          } elseif ($_SESSION['user_role'] === 1) { // Only shows for admin users
            include_once (ROOT_PATH . '/include/nav_admin.php');
          } elseif ($_SESSION['user_role'] === 2) { // Only shows for faculty users
            include_once (ROOT_PATH . '/include/nav_advisor.php');
          } elseif ($_SESSION['user_role'] === 3) { // Only shows for student users
            include_once (ROOT_PATH . '/include/nav_student.php');
          } else {
            echo '<span class="navbar-text">Not a registered user.</span>';
          }
          
        ?>
      </ul>
      <?php 
        if (!isset($user_name)) {
          echo '<span class="navbar-text">Please login.</span>';
        } else {
          echo '<span class="navbar-text"><i class="far fa-user"></i> '.$user_name.'</span> &nbsp;&nbsp;&nbsp;<a class="btn btn-primary" role="button" aria-disabled="true" href="' . SRC_PATH . '/logout.php">Logout</a>';  
        }
      ?>
    </div>
  </nav>
</header>