<?php

  // sanitize_string('abcd')
  function sanitize_string($value='') {
    $value = htmlspecialchars($value);
    $value = strip_tags($value);
    $value = rawurlencode($value);
    $value = mysqli_real_escape_string(db_connect(), $value);
    return $value;
  }

?>

