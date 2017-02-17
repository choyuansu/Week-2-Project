<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}

$countries_result = find_country_by_id($_GET['id']);
// No loop, only one result
$country = db_fetch_assoc($countries_result);

if(is_post_request()) {
  if(isset($_POST['Yes'])) {
    $result = delete_country_by_id($country['id']);
    if($result === true) {
      redirect_to('index.php');
    }
    else {
      echo 'Something went wrong!'; 
    }
  }
  elseif(isset($_POST['No'])) {
    redirect_to('show.php?id=' . $country['id']);
  }
}
?>
<?php $page_title = 'Staff: Delete Country'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo $country['id']; ?>">Back to Country Details</a><br />

  <h1>Delete Country: <?php echo $country['name']; ?></h1>

  <p>
    Are you sure you want to delete country <?php echo $country['name']; ?>? 
    This will delete all states and territories in <?php echo $country['name']; ?>.
  </p>
  <form action="delete.php?id=<?php echo $country['id']; ?>" method="post">
    <input type="submit" name="Yes" value="Yes">
    <input type="submit" name="No" value="No">
  </form>

</div>

<?php db_free_result($countries_result); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>





