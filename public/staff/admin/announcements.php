<?php 
require_once('../../../private/initialize.php'); 
require_login();
is_admin();

$logged_in_employee = $_SESSION['logged_employee_id'];

//CREATING A NEW ANNOUNCEMENT
if(is_post_request()) {
  $announcement = [];
  $announcement['announcement_id'] = $_POST['announcement_id'] ?? '';
  $announcement['announcement'] = $_POST['announcement'] ?? '';
  $announcement['employee_id'] = $logged_in_employee ?? '';


  $result = insert_announcement($announcement);
  //$result = mysqli_query($db, $sql);

  if($result == true) {
    $new_id = mysqli_insert_id($db);
    $_SESSION['message'] = 'You have created your announcement successfully.';
  
  } else {
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }

}

$id = $_GET['announcement_id'] ?? '1';
$announcement = find_announcement_by_id($id);

?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Remarkable Employee Announcements</title>
    <link href="../../stylesheets/public-styles.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="../../images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <!-- Main header -->
  <body>
    <div id="main-content">
      <header>
        <a href="<?php echo url_for( '/staff/admin/index.php'); ?>"><img src="../../images/ppl-logo.png" alt="circle logo" width="100" height="100"></a>
        <div id="header-content">
          <h1>Remarkable Employees</h1>
          <h4>Where We Come Together As A Team</h4>
        </div>
        <div id="user-info">
          <p>Welcome <?php echo $_SESSION['username']; ?></p>
          <p>You are logged in as - <?php echo $_SESSION['user_level']; ?></p>
        </div>
      </header>
      <!-- Navigation -->
      <main id="page-content">
        <aside id="navigation">
          <nav id="main-nav">
            <ul>
              <l1><a href="index.php">Admin Home</a></l1>
              <l1><a href="announcements.php">Announcements</a></l1>
              <l1><a href="images.php">Images</a></l1>
              <l1><a href="employee_list.php">Employees</a></l1>
              <l1><a href="<?php echo url_for('../public/logout.php') ?>">Logout <?php echo $_SESSION['username']; ?></a></l1>
            </ul>
          </nav>
        </aside>
        
        <!--  Main body -->  
        <article id="description">
          <div>
            <!-- This re-displays the message and resubmits the announcement each time you refresh the page -->
            <?php echo display_session_message(); ?>
            <div class="attributes">
              <h1>Reminders &amp; Announcements</h1>
              <p><?php echo h($announcement['announcement']); ?></p>
            </div>
            <hr>
            <h1>Post Your Announcements Here</h1>
            <!-- Updating the announcement table... Then pulling from that tbl date('F j, Y, g:i a');-->
            <form action="<?php echo url_for('/staff/admin/announcements.php'); ?>" method="post">
              <input type='hidden' id="date" name='date' value="<?php  ?>"><br>
              <label for="announcement">Post Announcement Here</label>
              <input type='hidden' name="announcement" value="<?php  ?>"><br>
              <textarea id="announcement" name='announcement' rows="5" cols="30"></textarea><br>
              <button type='submit' name='submit'>Add Comment</button>
            </form>
          </div>
          <hr>
          <div>
            <h1>The live calendar goes here</h1>
            <p>--------------------------------</p>
            <p>--------------------------------</p>
          </div>
        </article> 
      </main>

      <!-- PAGE FOOTER -->
      <footer id="footer">
        <div id="my-info">
          <h4>Created By</h4>
          &copy; <?php echo date('Y'); ?> Mechelle &#9774; Presnell &hearts;
        </div>
        <div id="chamber">
          <h4>Chamber of Commerce Links</h4>
          <p><a href="https://www.ashevillechamber.org/news-events/events/wnc-career-expo/?gclid=EAIaIQobChMI--vY9Jfk9gIVBLLICh1_2gFFEAAYASAAEgJtifD_BwE" target="_blank">Asheville Chamber of Commerce</a></p>
          <p><a href="https://www.uschamber.com/" target="_blank">US Chamber of Commerce</a></p>
        </div>
      </footer>
    </div>
  </body>
</html>
