<?php require_once('../../../private/initialize.php'); ?>

<?php
if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$id = $_GET['id'];
$territory_result = find_territory_by_id($id);
// No loop, only one result
$territory = db_fetch_assoc($territory_result);

$state_result = find_state_by_id($territory['state_id']);
// No loop, only one result
$state = db_fetch_assoc($state_result);
?>

<?php $page_title = 'Staff: Territory of ' . $territory['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="../states/show.php?id=<?php echo $state['id']; ?>">Back to State Details</a>
  <br />

  <h1>Territory: <?php echo $territory['name']; ?></h1>

  <?php
    echo "<table id=\"territory\">";
    echo "<tr>";
    echo "<td>Name: </td>";
    echo "<td>" . $territory['name'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>State: </td>";
    echo "<td>" . $state['name'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Position: </td>";
    echo "<td>" . $territory['position'] . "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table id=\"actions\">";
    echo "<tr>";
    echo "<td>";
    echo "<a href=\"edit.php?id=". $territory['id'] . "\">Edit</a>";
    echo "</td>";
    echo "<td>";
    echo "<a href=\"delete.php?id=". $territory['id'] . "\">Delete</a>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    
    db_free_result($territory_result);
  ?>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
