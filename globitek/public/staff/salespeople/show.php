<?php
require_once('../../../private/initialize.php');

if(!isset($_GET['id'])) {
  redirect_to('index.php');
}
$id = $_GET['id'];
$salespeople_result = find_salesperson_by_id($id);
// No loop, only one result
$salesperson = db_fetch_assoc($salespeople_result);
?>

<?php $page_title = 'Staff: Salesperson ' . $salesperson['first_name'] . " " . $salesperson['last_name']; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="index.php">Back to Salespeople List</a><br />

  <h1>Salesperson: <?php echo $salesperson['first_name'] . " " . $salesperson['last_name']; ?></h1>

  <?php
    echo "<table id=\"salesperson\">";
    echo "<tr>";
    echo "<td>Name: </td>";
    echo "<td>" . $salesperson['first_name'] . " " . $salesperson['last_name'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Phone: </td>";
    echo "<td>" . $salesperson['phone'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Email: </td>";
    echo "<td>" . $salesperson['email'] . "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table id=\"actions\">";
    echo "<tr>";
    echo "<td>";
    echo "<a href=\"edit.php?id=". $salesperson['id'] . "\">Edit</a>";
    echo "</td>";
    echo "<td>";
    echo "<a href=\"delete.php?id=". $salesperson['id'] . "\">Delete</a>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    db_free_result($salespeople_result);
  ?>
</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
