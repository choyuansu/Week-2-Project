<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$countries_result = find_country_by_id($_GET['id']);
// No loop, only one result
$country = db_fetch_assoc($countries_result);

// Set default values for all variables the page needs.
$errors = array();
$name = $country['name'];

if(is_post_request()) {

  // Confirm that values are present before accessing them.
  if(isset($_POST['name'])) { $country['name'] = $_POST['name']; }
  if(isset($_POST['code'])) { $country['code'] = $_POST['code']; }

  $result = update_country($country);
  if($result === true) {
    redirect_to('show.php?id=' . $country['id']);
  } else {
    $errors = $result;
  }
}
?>
<?php $page_title = 'Staff: Edit Country ' . $name; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="show.php?id=<?php echo $country['id']; ?>">Cancel</a><br />

  <h1>Edit Country: <?php echo $name; ?></h1>

  <?php echo display_errors($errors); ?>

  <form action="edit.php?id=<?php echo $country['id']; ?>" method="post">
    Name:<br />
    <input type="text" name="name" value="<?php echo $country['name']; ?>" /><br />
    Code:<br />
    <input type="text" name="code" value="<?php echo $country['code']; ?>" /><br />
    <br />
    <input type="submit" name="submit" value="Update"  />
  </form>

</div>

<?php db_free_result($countries_result); ?>
<?php include(SHARED_PATH . '/footer.php'); ?>
