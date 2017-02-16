<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}



$users_result = find_user_by_id($_GET['id']);
// No loop, only one result
$user = db_fetch_assoc($users_result);

if(is_post_request()) {
  $result = delete_user($user);
  if($result === true) {
    redirect_to('index.php');
  }
  else {
    $errors = $result;
  }
}
?>
<?php $page_title = 'Staff: Delete User'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo $user['id']; ?>">Back to User Details</a><br />

  <h1>Delete User: <?php echo $user['first_name'] . " " . $user['last_name']; ?></h1>

  <p>Are you sure you want to delete user <?php echo $user['first_name'] . " " . $user['last_name']; ?>?</p>
  <form action="delete.php?id=<?php echo $user['id']; ?>" method="post">
    <input type="submit" name="Yes" value="Yes">
    <input type="submit" name="No" value="No">
  </form>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>

