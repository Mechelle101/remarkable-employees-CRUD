<?php   
require_once('../../private/initialize.php'); 
require_login();

if(is_post_request()) {

  // Handle form values sent by new.php
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $birthday = $_POST['birthday'] ?? '';
  $email = $_POST['email'] ?? '';
  $start_date = $_POST['start_date'] ?? '';
  $user_level = $_POST['user_level'] ?? '';
  $department = $_POST['department'] ?? '';

  $result = insert_employee($first_name, $last_name, $birthday, $email, $start_date, $user_level, $department);
  $new_id = mysqli_insert_id($db);
  // THIS IS REDIRECTING TO THE DEFAULT ID OF 1
  redirect_to(url_for('staff/show.php?employee_id=' . $new_id));
  
} else {
  redirect_to(url_for('/staff/new.php'));
}

?>