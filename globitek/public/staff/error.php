<?php
  require_once('../../private/initialize.php');
?>

<?php $page_title = 'Error!'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Something's wrong!</h1>
  <p>Taking you back to Menu...</p>
</div>
<?php header('Refresh: 3; URL=index.php'); ?>
<?php include(SHARED_PATH . '/header.php'); ?>

