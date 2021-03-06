<?php
require_once('../../private/initialize.php');

// Until we learn about encryption, we will use an unencrypted
// master password as a stand-in. It should go without saying
// that this should *never* be done in real production code.
$master_password = 'secret';

// Set default values for all variables the page needs.
$errors = array();
$username = '';
$password = '';

if(is_post_request()) {

  if(!csrf_token_is_valid()){
    $errors[] = "Request is not valid.";
    redirect_to("../index.php");
  }

  if(!csrf_token_is_recent()){
    $errors[] = "Request timeout.";
    redirect_to("../index.php");
  }

  if(!request_is_same_domain()){
    $errors[] = "Request is not valid.";
    redirect_to("../index.php");
  }

  // Confirm that values are present before accessing them.
  if(isset($_POST['username'])) { $username = $_POST['username']; }
  if(isset($_POST['password'])) { $password = $_POST['password']; }

  // Validations
  if (is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if (is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }
  xssPrevention($username);

  // If there were no errors, submit data to database
  if (empty($errors)) {

    $users_result = find_users_by_username($username);

    // No loop, only one result
    $user = db_fetch_assoc($users_result);
    if(empty($user)){
      $errors[] = "Username \"".$username."\" is not found.";
    }
    else {
      if($password === $master_password) {
        // Username found, password matches
        log_in_user($user);
        after_successful_login();
        // Redirect to the staff menu after login
        redirect_to('index.php');
        // header("Location: index.php");
        // exit;
      } else {
        // Username found, but password does not match.
        $errors[] = "Password does not match the username.";
      }
    }
  }
}

?>
<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>
<div id="menu">
  <ul>
    <li><a href="../index.php">Public Site</a></li>
  </ul>
</div>

<div id="main-content">
  <h1>Log in</h1>

  <?php echo display_errors($errors); ?>

  <form action="login.php" method="post">
    <?php echo csrf_token_tag(); ?>
    Username:<br />
    <input type="text" name="username" value="<?php echo $username; ?>" /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <input type="submit" name="submit" value="Submit"  />
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
