<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}

$salespeople_result = find_salesperson_by_id($_GET['id']);
// No loop, only one result
$salesperson = db_fetch_assoc($salespeople_result);

if(is_post_request()) {
  if(isset($_POST['Yes'])) {
    $result = delete_salesperson_by_id($salesperson['id']);
    if($result === true) {
      redirect_to('index.php');
    }
    else {
      echo 'Something went wrong!'; 
    }
  }
  elseif(isset($_POST['No'])) {
    redirect_to('show.php?id=' . $salesperson['id']);
  }
}
?>
<?php $page_title = 'Staff: Delete Salesperson'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo $salesperson['id']; ?>">Back to Salesperson Details</a><br />

  <h1>Delete Salesperson: <?php echo $salesperson['first_name'] . " " . $salesperson['last_name']; ?></h1>

  <p>Are you sure you want to delete salesperson <?php echo $salesperson['first_name'] . " " . $salesperson['last_name']; ?>?</p>
  <form action="delete.php?id=<?php echo $salesperson['id']; ?>" method="post">
    <input type="submit" name="Yes" value="Yes">
    <input type="submit" name="No" value="No">
  </form>

</div>

<?php db_free_result($salespeople_result); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>


