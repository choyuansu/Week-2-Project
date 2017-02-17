<?php

  date_default_timezone_set('America/Los_Angeles');

  //
  // COUNTRY QUERIES
  //

  // Find all countries, ordered by name
  function find_all_countries() {
    global $db;
    $sql = "SELECT * FROM countries ORDER BY name ASC;";
    $country_result = db_query($db, $sql);
    return $country_result;
  }

  function find_country_by_id($id='') {
    global $db;

    $id = sanitize_number($id);

    $sql = "SELECT * FROM countries ";
    $sql .= "WHERE id='" . $id . "' LIMIT 1;";
    $country_result = db_query($db, $sql);
    return $country_result;
  }

  function validate_country($country, $errors=array()) {
    if (is_blank($country['name'])) {
      $errors[] = "Country name cannot be blank.";
    } elseif (!has_length($country['name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Country name must be between 2 and 255 characters.";
    } elseif (!has_valid_country_name_format($country['name'])) {
      $errors[] = "Country name must be a valid format";
    } elseif (has_duplicate_country_name($country['name'])) {
      $errors[] = "The country name is already in use.";
    }

    if (is_blank($country['code'])) {
      $errors[] = "Country code cannot be blank.";
    } elseif (!has_valid_state_code_format($country['code'])) {
      $errors[] = "Country code must be a valid format";
    } elseif (has_duplicate_country_code($country['code'])) {
      $errors[] = "The country code is already in use.";
    }

    return $errors;
  }

  function insert_country($country) {
    global $db;

    $errors = validate_country($country);
    if (!empty($errors)) {
      return $errors;
    }

    $country['name'] = sanitize_string($country['name']);
    $country['code'] = sanitize_string($country['code']);

    $sql = "INSERT INTO countries ";
    $sql .= "(name, code) ";
    $sql .= "VALUES (";
    $sql .= "'" . $country['name'] . "',";
    $sql .= "'" . $country['code'] . "'";
    $sql .= ");";
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  function update_country($country) {
    global $db;

    $errors = validate_country($country);
    if (!empty($errors)) {
      return $errors;
    }

    $country['name'] = sanitize_string($country['name']);
    $country['code'] = sanitize_string($country['code']);
    $country['id'] = sanitize_number($country['id']);

    $sql = "UPDATE countries SET ";
    $sql .= "name='" . $country['name'] . "', ";
    $sql .= "code='" . $country['code'] . "' ";
    $sql .= "WHERE id='" . $country['id'] . "' ";
    $sql .= "LIMIT 1;";
    // For update_state statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Delete a country record
  function delete_country_by_id($id) {
    global $db;

    $id = sanitize_number($id);

    $result = delete_states_by_country_id($id);
    if($result === true) {
      $sql = "DELETE FROM countries WHERE id='" . $id . "' LIMIT 1;";
      $countries_result = db_query($db, $sql);
      return $countries_result;
    }
    else {
      return $result; 
    }
  }

  //
  // STATE QUERIES
  //

  // Find all states, ordered by name
  function find_all_states() {
    global $db;
    $sql = "SELECT * FROM states ";
    $sql .= "ORDER BY name ASC;";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  // Find all states, ordered by name
  function find_states_for_country_id($country_id=0) {
    global $db;

    $country_id = sanitize_number($country_id);

    $sql = "SELECT * FROM states ";
    $sql .= "WHERE country_id='" . $country_id . "' ";
    $sql .= "ORDER BY name ASC;";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  // Find state by ID
  function find_state_by_id($id='') {
    global $db;
    
    $id = sanitize_number($id);
    
    $sql = "SELECT * FROM states ";
    $sql .= "WHERE id='" . $id . "';";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  function validate_state($state, $errors=array()) {
    if (is_blank($state['name'])) {
      $errors[] = "State name cannot be blank.";
    } elseif (!has_length($state['name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "State name must be between 2 and 255 characters.";
    } elseif (!has_valid_state_name_format($state['name'])) {
      $errors[] = "State name must be a valid format";
    }

    if (is_blank($state['code'])) {
      $errors[] = "State code cannot be blank.";
    } elseif (!has_valid_state_code_format($state['code'])) {
      $errors[] = "State code must be a valid format";
    }

    if (!country_exists($state['country_id'])) {
      redirect_to('../error.php');
    }

    return $errors;
  }

  // Add a new state to the table
  // Either returns true or an array of errors
  function insert_state($state) {
    global $db;

    $errors = validate_state($state);
    if (!empty($errors)) {
      return $errors;
    }

    $state['name'] = sanitize_string($state['name']);
    $state['code'] = sanitize_string($state['code']);
    $state['country_id'] = sanitize_number($state['country_id']);

    $sql = "INSERT INTO states ";
    $sql .= "(name, code, country_id) ";
    $sql .= "VALUES (";
    $sql .= "'" . $state['name'] . "',";
    $sql .= "'" . $state['code'] . "',";
    $sql .= "'" . $state['country_id'] . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a state record
  // Either returns true or an array of errors
  function update_state($state) {
    global $db;

    $errors = validate_state($state);
    if (!empty($errors)) {
      return $errors;
    }
    
    $state['name'] = sanitize_string($state['name']);
    $state['code'] = sanitize_string($state['code']);
    $state['country_id'] = sanitize_number($state['country_id']);
    $state['id'] = sanitize_number($state['id']);

    $sql = "UPDATE states SET ";
    $sql .= "name='" . $state['name'] . "', ";
    $sql .= "code='" . $state['code'] . "' ";
    $sql .= "WHERE id='" . $state['id'] . "' ";
    $sql .= "LIMIT 1;";
    // For update_state statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Delete a state record
  function delete_state_by_id($id) {
    global $db;

    $id = sanitize_number($id);

    $result = delete_territories_by_state_id($id);
    if($result === true) {
      $sql = "DELETE FROM states WHERE id='" . $id . "' LIMIT 1;";
      $states_result = db_query($db, $sql);
      return $states_result;
    }
    else {
      return $result; 
    }
  }

  // Delete all states records in a country
  function delete_states_by_country_id($country_id) {
    global $db;

    $country_id = sanitize_number($country_id);

    $states_result = find_states_for_country_id($country_id);
    while($state = db_fetch_assoc($states_result)) {
      $result = delete_state_by_id($state['id']);
      if($result === false) {
        return $result;
      }
    }
    return true;
  }

  //
  // TERRITORY QUERIES
  //

  // Find all territories, ordered by state_id
  function find_all_territories() {
    global $db;
    $sql = "SELECT * FROM territories ";
    $sql .= "ORDER BY state_id ASC, position ASC;";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  // Find all territories whose state_id (foreign key) matches this id
  function find_territories_for_state_id($state_id=0) {
    global $db;
    
    $state_id = sanitize_number($state_id);

    $sql = "SELECT * FROM territories ";
    $sql .= "WHERE state_id='" . $state_id . "' ";
    $sql .= "ORDER BY position ASC;";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  // Find territory by ID
  function find_territory_by_id($id='') {
    global $db;

    $id = sanitize_number($id);

    $sql = "SELECT * FROM territories ";
    $sql .= "WHERE id='" . $id . "';";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  function validate_territory($territory, $errors=array()) {
    // TODO add validations
    if (is_blank($territory['name'])) {
      $errors[] = "Territory name cannot be blank.";
    } elseif (!has_length($territory['name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Territory name must be between 2 and 255 characters.";
    } elseif (!has_valid_territory_name_format($territory['name'])) {
      $errors[] = "Territory name must be a valid format";
    }

    if (is_blank($territory['position'])) {
      $errors[] = "Territory position cannot be blank.";
    } elseif (!has_valid_territory_position_format($territory['position'])) {
      $errors[] = "Territory position must be a valid format";
    }

    if (!state_exists($territory['state_id'])) {
      redirect_to('../error.php');
    }

    return $errors;
  }

  // Add a new territory to the table
  // Either returns true or an array of errors
  function insert_territory($territory) {
    global $db;

    $errors = validate_territory($territory);
    if (!empty($errors)) {
      return $errors;
    }

    $territory['name'] = sanitize_string($territory['name']);
    $territory['state_id'] = sanitize_number($territory['state_id']);
    $territory['position'] = sanitize_number($territory['position']);

    $sql = "INSERT INTO territories ";
    $sql .= "(name, state_id, position) ";
    $sql .= "VALUES (";
    $sql .= "'" . $territory['name'] . "',";
    $sql .= "'" . $territory['state_id'] . "',";
    $sql .= "'" . $territory['position'] . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT territoryment failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a territory record
  // Either returns true or an array of errors
  function update_territory($territory) {
    global $db;

    $errors = validate_territory($territory);
    if (!empty($errors)) {
      return $errors;
    }

    $territory['name'] = sanitize_string($territory['name']);
    $territory['state_id'] = sanitize_number($territory['state_id']);
    $territory['position'] = sanitize_number($territory['position']);
    $territory['id'] = sanitize_number($territory['id']);

    $sql = "UPDATE territories SET ";
    $sql .= "name='" . $territory['name'] . "', ";
    $sql .= "position='" . $territory['position'] . "' ";
    $sql .= "WHERE id='" . $territory['id'] . "' ";
    $sql .= "LIMIT 1;";
    // For update_territory statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Delete a territory record
  function delete_territory_by_id($id) {
    global $db;

    $id = sanitize_number($id);
    
    $sql = "DELETE FROM territories WHERE id='" . $id . "' LIMIT 1;";
    $territories_result = db_query($db, $sql);
    return $territories_result;
  }

  // Delete all territory records in a state
  function delete_territories_by_state_id($state_id) {
    global $db;

    $state_id = sanitize_number($state_id);
    
    $sql = "DELETE FROM territories WHERE state_id='" . $state_id . "';";
    $territories_result = db_query($db, $sql);
    return $territories_result;
  }

  //
  // SALESPERSON QUERIES
  //

  // Find all salespeople, ordered last_name, first_name
  function find_all_salespeople() {
    global $db;
    $sql = "SELECT * FROM salespeople ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // To find salespeople, we need to use the join table.
  // We LEFT JOIN salespeople_territories and then find results
  // in the join table which have the same territory ID.
  function find_salespeople_for_territory_id($territory_id=0) {
    global $db;

    $territory_id = sanitize_number($territory_id);

    $sql = "SELECT * FROM salespeople ";
    $sql .= "LEFT JOIN salespeople_territories
              ON (salespeople_territories.salesperson_id = salespeople.id) ";
    $sql .= "WHERE salespeople_territories.territory_id='" . $territory_id . "' ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // Find salesperson using id
  function find_salesperson_by_id($id='') {
    global $db;
    
    $id = sanitize_number($id);

    $sql = "SELECT * FROM salespeople ";
    $sql .= "WHERE id='" . $id . "';";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  function validate_salesperson($salesperson, $errors=array()) {
    // TODO: update validation function
    if (is_blank($salesperson['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($salesperson['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    } elseif (!has_valid_name_format($salesperson['first_name'])) {
      $errors[] = "First name must be a valid format";
    }

    if (is_blank($salesperson['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($salesperson['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    } elseif (!has_valid_name_format($salesperson['last_name'])) {
      $errors[] = "Last name must be a valid format";
    }

    if (is_blank($salesperson['phone'])) {
      $errors[] = "Phone cannot be blank.";
    } elseif (!has_valid_phone_format($salesperson['phone'])) {
      $errors[] = "Phone number must have format xxx-xxx-xxxx.";
    }

    if (is_blank($salesperson['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($salesperson['email'])) {
      $errors[] = "Email must be a valid format.";
    }

    return $errors;
  }

  // Add a new salesperson to the table
  // Either returns true or an array of errors
  function insert_salesperson($salesperson) {
    global $db;

    $errors = validate_salesperson($salesperson);
    if (!empty($errors)) {
      return $errors;
    }

    $salesperson['first_name'] = sanitize_string($salesperson['first_name']);
    $salesperson['last_name'] = sanitize_string($salesperson['last_name']);
    $salesperson['phone'] = sanitize_string($salesperson['phone']);
    $salesperson['email'] = sanitize_string($salesperson['email']);

    $sql = "INSERT INTO salespeople ";
    $sql .= "(first_name, last_name, phone, email) ";
    $sql .= "VALUES (";
    $sql .= "'" . $salesperson['first_name'] . "',";
    $sql .= "'" . $salesperson['last_name'] . "',";
    $sql .= "'" . $salesperson['phone'] . "',";
    $sql .= "'" . $salesperson['email'] . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a salesperson record
  // Either returns true or an array of errors
  function update_salesperson($salesperson) {
    global $db;

    $errors = validate_salesperson($salesperson);
    if (!empty($errors)) {
      return $errors;
    }

    $salesperson['first_name'] = sanitize_string($salesperson['first_name']);
    $salesperson['last_name'] = sanitize_string($salesperson['last_name']);
    $salesperson['phone'] = sanitize_string($salesperson['phone']);
    $salesperson['email'] = sanitize_string($salesperson['email']);
    $salesperson['id'] = sanitize_number($salesperson['id']);

    $sql = "UPDATE salespeople SET ";
    $sql .= "first_name='" . $salesperson['first_name'] . "', ";
    $sql .= "last_name='" . $salesperson['last_name'] . "', ";
    $sql .= "phone='" . $salesperson['phone'] . "', ";
    $sql .= "email='" . $salesperson['email'] . "' ";
    $sql .= "WHERE id='" . $salesperson['id'] . "' ";
    $sql .= "LIMIT 1";
    // For update_salesperson statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Delete a salesperson record
  function delete_salesperson_by_id($id) {
    global $db;

    $id = sanitize_number($id);
    
    $sql = "DELETE FROM salespeople WHERE id='" . $id . "' LIMIT 1;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // To find territories, we need to use the join table.
  // We LEFT JOIN salespeople_territories and then find results
  // in the join table which have the same salesperson ID.
  function find_territories_by_salesperson_id($id='') {
    global $db;
    
    $id = sanitize_number($id);
    
    $sql = "SELECT * FROM territories ";
    $sql .= "LEFT JOIN salespeople_territories
              ON (territories.id = salespeople_territories.territory_id) ";
    $sql .= "WHERE salespeople_territories.salesperson_id='" . $id . "' ";
    $sql .= "ORDER BY territories.name ASC;";
    $territories_result = db_query($db, $sql);
    return $territories_result;
  }

  //
  // USER QUERIES
  //

  // Find all users, ordered last_name, first_name
  function find_all_users() {
    global $db;
    $sql = "SELECT * FROM users ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $users_result = db_query($db, $sql);
    return $users_result;
  }

  // Find user using id
  function find_user_by_id($id='') {
    global $db;

    $id = sanitize_number($id);
    
    $sql = "SELECT * FROM users WHERE id='" . $id . "' LIMIT 1;";
    $users_result = db_query($db, $sql);
    return $users_result;
  }

  function validate_user($user, $errors=array()) {
    // TODO: update validation function
    if (is_blank($user['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($user['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    } elseif (!has_valid_name_format($user['first_name'])) {
      $errors[] = "First name must be a valid format.";
    }

    if (is_blank($user['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($user['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    } elseif (!has_valid_name_format($user['last_name'])) {
      $errors[] = "Last name must be a valid format.";
    }

    if (is_blank($user['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($user['email'])) {
      $errors[] = "Email must be a valid format.";
    }

    if (is_blank($user['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($user['username'], array('max' => 255))) {
      $errors[] = "Username must be less than 255 characters.";
    } elseif (!has_valid_username_format($user['username'])) {
      $errors[] = "Username must contain only alphabets, numbers, and underscores.";
    } elseif (has_duplicate_username($user)) {
      $errors[] = "The username is already in use.";
    }
    return $errors;
  }

  // Add a new user to the table
  // Either returns true or an array of errors
  function insert_user($user) {
    global $db;

    $errors = validate_user($user);
    if (!empty($errors)) {
      return $errors;
    }

    $user['first_name'] = sanitize_string($user['first_name']);
    $user['last_name'] = sanitize_string($user['last_name']);
    $user['email'] = sanitize_string($user['email']);
    $user['username'] = sanitize_string($user['username']);

    $created_at = date("Y-m-d H:i:s");
    $sql = "INSERT INTO users ";
    $sql .= "(first_name, last_name, email, username, created_at) ";
    $sql .= "VALUES (";
    $sql .= "'" . $user['first_name'] . "',";
    $sql .= "'" . $user['last_name'] . "',";
    $sql .= "'" . $user['email'] . "',";
    $sql .= "'" . $user['username'] . "',";
    $sql .= "'" . $created_at . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a user record
  // Either returns true or an array of errors
  function update_user($user) {
    global $db;

    $errors = validate_user($user);
    if (!empty($errors)) {
      return $errors;
    }

    $user['first_name'] = sanitize_string($user['first_name']);
    $user['last_name'] = sanitize_string($user['last_name']);
    $user['email'] = sanitize_string($user['email']);
    $user['username'] = sanitize_string($user['username']);
    $user['id'] = sanitize_number($user['id']);

    $sql = "UPDATE users SET ";
    $sql .= "first_name='" . $user['first_name'] . "', ";
    $sql .= "last_name='" . $user['last_name'] . "', ";
    $sql .= "email='" . $user['email'] . "', ";
    $sql .= "username='" . $user['username'] . "' ";
    $sql .= "WHERE id='" . $user['id'] . "' ";
    $sql .= "LIMIT 1;";
    // For update_user statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Delete a user record
  function delete_user_by_id($id) {
    global $db;
    
    $id = sanitize_number($id);
    
    $sql = "DELETE FROM users WHERE id='" . $id . "' LIMIT 1;";
    $users_result = db_query($db, $sql);
    if($users_result) {
      return true;
    } else {
      // The SQL DELETE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

?>
