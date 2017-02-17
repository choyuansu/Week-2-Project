<?php

  // is_blank('abcd')
  function is_blank($value='') {
    return !isset($value) || trim($value) == '';
  }

  // has_length('abcd', ['min' => 3, 'max' => 5])
  function has_length($value, $options=array()) {
    $length = strlen($value);
    if(isset($options['max']) && ($length > $options['max'])) {
      return false;
    } elseif(isset($options['min']) && ($length < $options['min'])) {
      return false;
    } elseif(isset($options['exact']) && ($length != $options['exact'])) {
      return false;
    } else {
      return true;
    }
  }

  // has_valid_email_format('test@test.com')
  function has_valid_email_format($value) {
    return preg_match('/^[A-Za-z0-9(@)\._-]+$/', $value) &&
           filter_var($value, FILTER_VALIDATE_EMAIL);
  }

  // has_valid_username_format('username')
  function has_valid_username_format($value) {
    return preg_match('/^[A-Za-z0-9_]+$/', $value);
  }

  // has_valid_phone_format('phone')
  function has_valid_phone_format($value) {
    return preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}+$/', $value);
  }

  // My custom validation 1
  // has_valid_name_format('name')
  function has_valid_name_format($value) {
    return preg_match('/^[A-Za-z\s\-,\.\']+$/', $value);
  }

  // My custom validation 2
  // has_valid_state_name_format('state_name')
  function has_valid_state_name_format($value) {
    return preg_match('/^[A-Za-z\s]+$/', $value);
  }

  // My custom validation 3
  // has_valid_state_code_format('state_code')
  function has_valid_state_code_format($value) {
    return preg_match("/^[A-Z]{2}+$/", $value);
  }

  // My custom validation 4
  // has_valid_territory_name_format('territory_name')
  function has_valid_territory_name_format($value) {
    return preg_match("/^[A-Za-z\s]+$/", $value);
  }

  // My custom validation 5
  // has_valid_territory_position_format('territory_position')
  function has_valid_territory_position_format($value) {
    return preg_match("/^[0-9]+$/", $value);
  }
  
  // My custom validation 6
  // has_duplicate_username('username')
  function has_duplicate_username($user=array()) {
    global $db;
    $sql = "SELECT username FROM users ";
    $sql .= "WHERE username='" . $user['username'];
    $sql .= "' AND id!='" . $user['id'] . "';";
    $result = db_query($db, $sql);
    if( db_num_rows($result) > 0) return true;
    return false;
  }

  // My custom validation 7
  // has_valid_country_name_format('country_name')
  function has_valid_country_name_format($value) {
    return preg_match('/^[A-Za-z\s]+$/', $value);
  }

  // My custom validation 8
  // has_valid_country_code_format('country_code')
  function has_valid_country_code_format($value) {
    return preg_match("/^[A-Z]{2}+$/", $value);
  }

  // My custom validation 9
  // has_duplicate_country_name
  function has_duplicate_country_name($value) {
    global $db;
    $sql = "SELECT name FROM countries ";
    $sql .= "WHERE name='" . $country['name'];
    $sql .= "' AND id!='" . $country['id'] . "';";
    $result = db_query($db, $sql);
    if( db_num_rows($result) > 0) return true;
    return false;
  }

  // My custom validation 10
  // has_duplicate_country_code
  function has_duplicate_country_code($value) {
    global $db;
    $sql = "SELECT code FROM countries ";
    $sql .= "WHERE code='" . $country['code'];
    $sql .= "' AND id!='" . $country['id'] . "';";
    $result = db_query($db, $sql);
    if( db_num_rows($result) > 0) return true;
    return false;
  }
?>
