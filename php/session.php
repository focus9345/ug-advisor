<?php

//======================================================================
// SESSIONS
//======================================================================

  include_once (realpath(dirname(__FILE__).'/path.php'));
  include_once (ROOT_PATH . '/php/config.php');

  session_start();
  
  $user_check = $_SESSION['login_user'];
  // Check user and get roll session from database

  $select_user = $db_connection->prepare(
    /* Need to update this section FROM table */
    "SELECT user_id,username,role_id FROM user WHERE username = ?");
  $select_user->bind_param("s", $user_check);
  $select_user->execute();
  $select_user->bind_result($user_id, $user_name, $user_role);
  $select_user->fetch();

  # session information
  $_SESSION['user_id'] = $user_id;
  $_SESSION['user_name'] = $user_name;
  $_SESSION['user_role'] = $user_role;
  
  // if there is no User logged in
  if(!isset($user_name)){
    header("location: " . BASE_URL);
  }
  if(!isset($user_role)){
    header("location: " . BASE_URL);
  }

  $select_user->close();
  // Close the mysql connection
  //mysqli_close($db_connection); 

?>
