<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}

$states_result = find_state_by_id($_GET['id']);
// No loop, only one result
$state = db_fetch_assoc($states_result);

if(is_post_request()) {
  if(isset($_POST['Yes'])) {
    $result = delete_state_by_id($state['id']);
    if($result === true) {
      redirect_to('../countries/show.php?id=' . $state['country_id']);
    }
    else {
      echo 'Something went wrong!'; 
    }
  }
  elseif(isset($_POST['No'])) {
    redirect_to('show.php?id=' . $state['id']);
  }
}
?>
<?php $page_title = 'Staff: Delete State'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo $state['id']; ?>">Back to State Details</a><br />

  <h1>Delete State: <?php echo $state['name']; ?></h1>

  <p>
    Are you sure you want to delete state <?php echo $state['name']; ?>? 
    This will delete all territories in <?php echo $state['name']; ?>.
  </p>
  <form action="delete.php?id=<?php echo $state['id']; ?>" method="post">
    <input type="submit" name="Yes" value="Yes">
    <input type="submit" name="No" value="No">
  </form>

</div>

<?php db_free_result($states_result); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>




