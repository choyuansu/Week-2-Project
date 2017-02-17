<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}

$territories_result = find_territory_by_id($_GET['id']);
// No loop, only one result
$territory = db_fetch_assoc($territories_result);

if(is_post_request()) {
  if(isset($_POST['Yes'])) {
    $result = delete_territory_by_id($territory['id']);
    if($result === true) {
      redirect_to('../states/show.php?id=' . $territory['state_id']);
    }
    else {
      echo 'Something went wrong!'; 
    }
  }
  elseif(isset($_POST['No'])) {
    redirect_to('show.php?id=' . $territory['id']);
  }
}
?>
<?php $page_title = 'Staff: Delete Territory'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo $territory['id']; ?>">Back to Territory Details</a><br />

  <h1>Delete Territory: <?php echo $territory['name']; ?></h1>

  <p>Are you sure you want to delete territory <?php echo $territory['name']; ?>?</p>
  <form action="delete.php?id=<?php echo $territory['id']; ?>" method="post">
    <input type="submit" name="Yes" value="Yes">
    <input type="submit" name="No" value="No">
  </form>

</div>

<?php db_free_result($territories_result); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>
