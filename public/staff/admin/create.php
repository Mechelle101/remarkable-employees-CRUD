<?php   
require_once('../../../private/initialize.php'); 
require_login();

if(is_post_request()) {
  $subject = [];
  $employee['first_name'] = $_POST['first_name'] ?? '';
  $employee['last_name'] = $_POST['last_name'] ?? '';
  $employee['user_level'] = $_POST['user_level'] ?? '';
  $employee['department_initial'] = $_POST['department_initial'] ?? '';
  $employee['email'] = $_POST['email'] ?? '';
  $employee['username'] = $_POST['username'] ?? '';
  $employee['password'] = $_POST['password'] ?? '';
  $employee['confirm_password'] = $_POST['confirm_password'] ?? '';

  // EDIT THE QUERY TO ADD THE USER CREDENTIALS
  $result = insert_employee($employee);
  if($result === true) {
  $new_id = mysqli_insert_id($db);
  $_SESSION['message'] = 'The employee was created successfully.';
  redirect_to(url_for('staff/admin/show.php?employee_id=' . $new_id));
  } else {
    $errors = $result;
  }

} else {
  redirect_to(url_for('/staff/admin/new.php'));
}

?>