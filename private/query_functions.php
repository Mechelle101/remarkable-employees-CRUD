<?php

function find_all_employees() {
  global $db;
  $sql = "SELECT * FROM employee ";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function find_only_employees() {
  global $db;
  $sql = "SELECT * FROM employee ";
  $sql .= "WHERE user_level='employee' ";
  // TO CHECK IF YOUR QUERY IS WORKING
  // echo $sql;
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function find_employee_by_id($id) {
  global $db;
  $sql = "SELECT * FROM employee ";
  $sql .= "WHERE employee_id='" . $id . "'";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $subject = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $subject; // return the assoc. array
}

function find_employee_by_username($username) {
  global $db;
  $sql = "SELECT * FROM employee ";
  $sql .= "WHERE username='" . $username . "'";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $employee = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $employee; // return the assoc. array
}

function validate_employee($employee, $options=[]) {
  $password_required = $options['password_required'] ?? true;
  
  if(is_blank($employee['first_name'])) {
    $errors[] = "First name cannot be blank.";
  } 

  if(is_blank($employee['last_name'])) {
    $errors[] = "Last name cannot be blank.";
  }

  if(is_blank($employee['email'])) {
    $errors[] = "Email cannot be blank.";
  } elseif (!has_length($employee['email'], array('max' => 255))) {
    $errors[] = "Email must be less than 255 characters.";
  } elseif (!has_valid_email_format($employee['email'])) {
    $errors[] = "Email must be a valid format.";
  }

  if(is_blank($employee['username'])) {
    $errors[] = "Username cannot be blank.";
  } elseif (!has_length($employee['username'], array('min' => 8, 'max' => 255))) {
    $errors[] = "Username must be between 8 and 255 characters.";
  } elseif (!has_unique_username($employee['username'], $employee['id'] ?? 0)) {
    $errors[] = "Username not allowed. Try another.";
  }

  if($password_required) {
    if(is_blank($employee['password'])) {
      $errors[] = "Password cannot be blank.";
    } elseif (!has_length($employee['password'], array('min' => 8))) {
      $errors[] = "Password must contain 8 or more characters";
    } elseif (!preg_match('/[A-Z]/', $employee['password'])) {
      $errors[] = "Password must contain at least 1 uppercase letter";
    } elseif (!preg_match('/[a-z]/', $employee['password'])) {
      $errors[] = "Password must contain at least 1 lowercase letter";
    } elseif (!preg_match('/[0-9]/', $employee['password'])) {
      $errors[] = "Password must contain at least 1 number";
    } elseif (!preg_match('/[^A-Za-z0-9\s]/', $employee['password'])) {
      $errors[] = "Password must contain at least 1 symbol";
    }

    if(is_blank($employee['confirm_password'])) {
      $errors[] = "Confirm password cannot be blank.";
    } elseif ($employee['password'] !== $employee['confirm_password']) {
      $errors[] = "Password and confirm password must match.";
    }
  }
  return $errors;
}

function insert_employee($employee) {
  global $db;
  // $errors = validate_employee($employee);
  // if (!empty($errors)) {
  //   return $errors;
  // }

  $hashed_password = password_hash($employee['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO employee ";
  $sql .= "(first_name, last_name, user_level, department_initial, email, username, hashed_password) ";
  $sql .= "VALUES (";
  $sql .= "'" . $employee['first_name'] . "',";
  $sql .= "'" . $employee['last_name'] . "',";
  $sql .= "'" . $employee['user_level'] . "',";
  $sql .= "'" . $employee['department_initial'] . "',";
  $sql .= "'" . $employee['email'] . "',";
  $sql .= "'" . $employee['username'] . "',";
  $sql .= "'" . $hashed_password. "'";

  $sql .= ")";
  $result = mysqli_query($db, $sql);
  // FOR INSERT STATEMENTS INSERT RETURNS TRUE/FALSE
  if($result)  {
    return true;
  } else {
    // IF THE INSERT FAILED
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

// CREATE A USER ACCOUNT
function create_user_account($employee) {
  global $db;

  // $errors = validate_employee($employee);
  // if(!empty($errors)) {
  //   return $errors;
  // }

  $hashed_password = password_hash($employee['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO employee ";
  $sql .= "(first_name, last_name, email,  username, hashed_password) ";
  $sql .= "VALUES (";
  $sql .= "'" . $employee['first_name'] . "',";
  $sql .= "'" . $employee['last_name'] . "',";
  $sql .= "'" . $employee['email'] . "',";
  $sql .= "'" . $employee['username'] . "',";
  $sql .= "'" . $hashed_password . "'";
  $sql .= ")";
  $result = mysqli_query($db, $sql);
  // FOR INSERT STATEMENTS INSERT RETURNS TRUE/FALSE
  if($result)  {
    return true;
  } else {
    // IF THE INSERT FAILED
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

// AN ARRAY IS PASSED IN HERE
// function update_employee($employee) {
//   global $db;
//   $password_sent = !is_blank($employee['password']);

//   $errors = validate_employee($employee, ['password_required' => $password_sent]);
//   // if(!empty($errors)) {
//   //   return $errors;
//   // }
//   $hashed_password = password_hash($employee['password'], PASSWORD_DEFAULT);

//   $sql = "UPDATE employee SET ";
//   $sql .= "first_name='" . $employee['first_name'] . "',";
//   $sql .= "last_name='" . $employee['last_name'] . "',";
//   $sql .= "user_level='" . $employee['user_level'] . "',";
//   $sql .= "department_initial='" . $employee['department_initial'] . "',";
//   $sql .= "email='" . $employee['email'] . "',";
//   if($password_sent) {
//     $sql .= "hashed_password='" . $hashed_password . "', ";
//   }
//   $sql .= "username='" . $employee['username'] . "' ";
//   $sql .= "WHERE employee_id='" . $employee['employee_id'] . "' ";
//   $sql .= "LIMIT 1";

//   $result = mysqli_query($db, $sql);
//   // FOR UPDATE STATEMENTS THE RESULT IS TRUE/FALSE
//   if($result) {
//     return true;
//   } else {
//     // THE UPDATE FAILED
//     echo mysqli_error($db);
//     db_disconnect($db);
//     exit;
//   }
// }

function update_employee($employee, $id) {
  global $db;
  $sql = "UPDATE employee SET ";
  $sql .= "first_name='" . $employee['first_name'] . "',";
  $sql .= "last_name='" . $employee['last_name'] . "',";
  $sql .= "user_level='" . $employee['user_level'] . "',";
  $sql .= "department_initial='" . $employee['department_initial'] . "',";
  $sql .= "email='" . $employee['email'] . "',";
  $sql .= "username='" . $employee['username'] . "' ";
  $sql .= "WHERE employee_id='" . $id . "' ";
  $sql .= "LIMIT 1";
  
  $result = mysqli_query($db, $sql);
  if($result === true) {
    $_SESSION['message'] = 'The employee was updated successfully.';
    return true;
  } else {
    echo mysqli_error($db);
  }
}

// I WILL LOOK AT THIS LATER, I AM NOT RUNNING DELETE STATEMENTS YET
function delete_employee($id) {
  global $db;

  $sql = "DELETE FROM employee ";
  $sql .= "WHERE employee_id='" . $id . "' ";
  $sql .= "LIMIT 1";
  $result = mysqli_query($db, $sql);

  // For DELETE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // DELETE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

function find_all_announcements() {
  global $db;
  $sql = "SELECT * FROM announcement ";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  return $result;
}

function find_announcement_by_id($id) {
  global $db;
  $sql = "SELECT * FROM announcement ";
  $sql .= "WHERE announcement_id='" . $id . "'";
  echo $sql;
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  $subject = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $subject; // return the assoc. array
}

function insert_announcement($announcement, $date) {
  global $db; 
  $sql = "INSERT INTO announcement ";
  $sql .= "(announcement, date) ";
  $sql .= "VALUES (";
  $sql .= "'" . $announcement['announcement'] . "',";
  $sql .= "'" . $date['date'] . "'";
  $sql .= ")";

  $result = mysqli_query($db, $sql);
  // FOR INSERT STATEMENTS INSERT RETURNS TRUE/FALSE
  if($result)  {
    return true;
  } else {
    // IF THE INSERT FAILED
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}

function create_new_announcement($announcement) {
  global $db;

  $sql = "INSERT INTO announcement ";
  $sql .= "(announcement) ";
  $sql .= "VALUES (";
  $sql .= "'" . $announcement['announcement'] . "' ";
  $sql .= ")";
  $result = mysqli_query($db, $sql);
  
  // FOR INSERT STATEMENTS INSERT RETURNS TRUE/FALSE
  if($result)  {
    return true;
  } else {
    // IF THE INSERT FAILED
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
}


?>