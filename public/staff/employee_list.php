<?php 
require_once('../../private/initialize.php'); 
require_login();

?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Remarkable Employee List Page</title>
    <link href="../stylesheets/public-styles.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <!-- Header -->
  <body>
    <div id="main-content">
      <header>
        <a href="<?php echo url_for('staff/index.php'); ?>"><img src="../images/ppl-logo.png" alt="circle logo" width="100" height="100"></a>
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
              <l1><a href="<?php echo url_for( '/staff/index.php'); ?>"><?php echo $_SESSION['username']; ?> Home</a></l1>
              <l1><a href="announcements.php">Announcements</a></l1>
              <l1><a href="images.php">Images</a></l1>
              <l1><a href="employee_list.php">Employees</a></l1>
              <l1><a href="<?php echo url_for('../public/logout.php'); ?>">Logout <?php echo $_SESSION['username']; ?></a></l1>
            </ul>
          </nav>
        </aside>
        <!-- Main body -->
        <article id="description">
          <div>
            <div class="action">
            <?php echo display_session_message(); ?>
            <h1>A List of Employees</h1> 
            <p>You can view employees basic information.</p> 
            </div>
            <?php
              $employee_set = find_only_employees();
            ?>
          </div>
          <hr>
          <div>
          <table class="list">
            <tr>
              <th>Contact Information</th>
            </tr>
            <?php while($employee = mysqli_fetch_assoc($employee_set)) { ?>
              <tr>
                <td>
                <div id="add-employee">
                  <a class="action" href="<?php echo url_for('/staff/show.php?employee_id=' . h(u($employee['employee_id']))); ?>"> <?php echo h($employee['first_name']) ." ". h($employee['last_name']); ?></a>
                </div><br>
                </td>
              </tr>
            <?php } ?>
  	      </table>

          <?php
            mysqli_free_result($employee_set);
          ?>

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
<?php //db_disconnect($db); ?>
