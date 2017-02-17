<?php require_once('../../../private/initialize.php'); ?>

<?php $page_title = 'Staff: Countries'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <a href="../index.php">Back to Menu</a><br />

  <h1>Countries</h1>

  <a href="new.php">Add a Country</a><br />
  <br />

  <?php
    $country_result = find_all_countries();

    echo "<table id=\"countries\" style=\"width: 500px;\">";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Code</th>";
    echo "<th></th>";
    echo "<th></th>";
    echo "<th></th>";
    echo "</tr>";
    while($country = db_fetch_assoc($country_result)) {
      echo "<tr>";
      echo "<td>" . $country['name'] . "</td>";
      echo "<td>" . $country['code'] . "</td>";
      echo "<td>";
      echo "<a href=\"show.php?id=" . $country['id'] . "\">Show</a>";
      echo "</td>";
      echo "<td>";
      echo "<a href=\"edit.php?id=" . $country['id'] . "\">Edit</a>";
      echo "</td>";
      echo "<td>";
      echo "<a href=\"delete.php?id=" . $country['id'] . "\">Delete</a>";
      echo "</td>";
      echo "</tr>";
    } // end while $countries
    db_free_result($country_result);
    echo "</table>"; // #countries
  ?>

</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
