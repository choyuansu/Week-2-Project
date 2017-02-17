<?php require_once('../../../private/initialize.php'); ?>

<?php
if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$id = $_GET['id'];
$country_result = find_country_by_id($id);
// No loop, only one result
$country = db_fetch_assoc($country_result);
?>

<?php $page_title = 'Staff: Country of ' . $country['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Countries List</a><br />

  <h1>Country: <?php echo $country['name']; ?></h1>

  <?php
    echo "<table id=\"country\">";
    echo "<tr>";
    echo "<td>Name: </td>";
    echo "<td>" . $country['name'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Code: </td>";
    echo "<td>" . $country['code'] . "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table id=\"actions\">";
    echo "<tr>";
    echo "<td>";
    echo "<a href=\"edit.php?id=". $country['id'] . "\">Edit</a>";
    echo "</td>";
    echo "<td>";
    echo "<a href=\"delete.php?id=". $country['id'] . "\">Delete</a>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
?>
    <hr />

    <h2>States</h2>
    <br />
    <a href="../states/new.php?country_id=<?php echo $country['id'];?>">Add a State</a><br />

<?php
    $state_result = find_states_for_country_id($country['id']);

    echo "<ul id=\"states\">";
    while($state = db_fetch_assoc($state_result)) {
      echo "<li>";
      echo "<a href=\"../states/show.php?id=" . $state['id'] . "\">";
      echo $state['name'];
      echo "</a>";
      echo "</li>";
    } // end while $state
    db_free_result($state_result);
    echo "</ul>"; // #states

    db_free_result($country_result);
  ?>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
