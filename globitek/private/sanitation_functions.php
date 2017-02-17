<?php

  // sanitize_string('abcd')
  function sanitize_string($value='') {
    global $db;
    $value = htmlspecialchars($value, ENT_QUOTES);
    $value = strip_tags($value);
    //$value = rawurlencode($value);
    $value = mysqli_real_escape_string($db, $value);
    return $value;
  }

  // sanitize_number('1234567890')
  // Strip everything from a string except numbers
  function sanitize_number($value='') {
    $value = preg_replace('/[^0-9]/', '', $value);
    return $value;
  }

?>

