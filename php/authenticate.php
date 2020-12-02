<?php
//======================================================================
// USER AUTHENTICATE
//======================================================================

include_once (realpath(dirname(__FILE__).'/path.php'));
include_once (realpath(dirname(__FILE__).'/config.php'));


//-----------------------------------------------------
// Authenticate
//-----------------------------------------------------
if (isset($_POST['submit'])) {
  if (empty($_POST['username']) || empty($_POST['password'])) {
    $error = "Username or Password is empty!";
    return $error;
  }
} else {
/* Check the Username and Password */
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $pass = crypt($_POST["password"], SALT );
    $user_roll = 0;
  
    // Protect against MYSQL injection
    $username = stripslashes($username);
    $pass = stripslashes($pass);
    $username = mysqli_real_escape_string($db_connection, $username);
    $pass = mysqli_real_escape_string($db_connection, $pass);

    // SQL query to fetch information and find match user
    $select_user = $db_connection->prepare(
      "SELECT user_id, username, role_id FROM user WHERE username = ? AND password = ? LIMIT 1");
    $select_user->bind_param("ss", $username, $pass);
    $select_user->execute();
    $select_user->bind_result($user_id, $user_name, $user_role);
    $select_user->store_result();

    if($select_user->num_rows == 1) {
      if($select_user->fetch()) {

        session_start(); 
        # This area needs to send users and admins to there own directory
        $_SESSION['user_id'] = $user_id;
        $_SESSION['login_user']=$username;
        $_SESSION['user_role'] = $user_role;


        if ($_SESSION['user_role'] == 1) {
          header("location: " . BASE_URL . "/admin");
        } elseif ($_SESSION['user_role'] == 2) {
          header("location:" . BASE_URL . "/advisor");
        } elseif ($_SESSION['user_role'] == 3) {
          header("location:" . BASE_URL . "/student");
        } else {
          $_SESSION['error'] = "Login Failed";
          
        }
      }
  } else {
    $_SESSION['message'] = "Username or Password did not match!";
    header("location: " . SRC_PATH . "/logout.php"); 
    exit();
  }
  // close the mysql connection
  $select_user->close();
} else {
  header("location: " . SRC_PATH . "/logout.php"); 
}
}