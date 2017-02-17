<?php require_once('../../../private/initialize.php'); ?>

<?php
if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$id = $_GET['id'];
$state_result = find_state_by_id($id);
// No loop, only one result
$state = db_fetch_assoc($state_result);

$country_result = find_country_by_id($state['country_id']);
// No loop, only one result
$country = db_fetch_assoc($country_result);
?>

<?php $page_title = 'Staff: State of ' . $state['name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="../countries/show.php?id=<?php echo $country['id']; ?>">Back to Country Details</a><br />

  <h1>State: <?php echo $state['name']; ?></h1>

  <?php
    echo "<table id=\"state\">";
    echo "<tr>";
    echo "<td>Name: </td>";
    echo "<td>" . $state['name'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Code: </td>";
    echo "<td>" . $state['code'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Country: </td>";
    echo "<td>" . $country['name'] . "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table id=\"actions\">";
    echo "<tr>";
    echo "<td>";
    echo "<a href=\"edit.php?id=". $state['id'] . "\">Edit</a>";
    echo "</td>";
    echo "<td>";
    echo "<a href=\"delete.php?id=". $state['id'] . "\">Delete</a>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
?>
    <hr />

    <h2>Territories</h2>
    <br />
    <a href="../territories/new.php?state_id=<?php echo $state['id'];?>">Add a Territory</a><br />

<?php
    $territory_result = find_territories_for_state_id($state['id']);

    echo "<ul id=\"territories\">";
    while($territory = db_fetch_assoc($territory_result)) {
      echo "<li>";
      echo "<a href=\"../territories/show.php?id=" . $territory['id'] . "\">";
      echo $territory['name'];
      echo "</a>";
      echo "</li>";
    } // end while $territory
    db_free_result($territory_result);
    echo "</ul>"; // #territories

    db_free_result($state_result);
  ?>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
