<?php

function connectDB($db_name = 'cknote'){
  $db_host = 'localhost';
  $db_user = 'root';
  global $con;
  $con = new mysqli($db_host, $db_user, '', $db_name);
  if ($con->connect_error) {
    die('Connection failed: '.$con->connect_error);
  }
}

function querySql($sql, $return=false){
  global $con;
  if (!$results = $con->query($sql)) {
    die("Errors (".$con->errno.") found: ".$con->error."\n");
  }
  if ($return) {
    return $results;
  }
}

function multiQuery($sql, $return=false){
  global $con;
  if (!$results = $con->multi_query($sql)) {
    die("Errors (".$con->errno.") found: ".$con->error."\n");
  }
  if ($return) {
    return $results;
  }
}

function sanitizeString($sql){
  global $con;
  $sql = stripslashes($sql);
  return $con->real_escape_string($sql);
}

function createTableName($cat, $tab){
  $cat_name = ucfirst(strtolower(trim($cat)));
  $tab_array = explode(' ', strtolower(trim($tab)));
  foreach($tab_array as $key => $name){
    $tab_array[$key] = ucfirst($name);
  }

  return $table_name = $cat_name.'_'.implode('', $tab_array);
}

function getCatAndTab(){
  $sql = "SELECT table_name FROM tables WHERE table_schema = 'cknote' AND table_name != 'temp'";
  $results = querySql($sql, true);
  $table_names = [];
  while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
    $table_names[] = $row['table_name'];
  }
  $cats = [];
  foreach ($table_names as $key => $value) {
    $cats[] = substr($value, 0, strpos($value, '_'));
  }
  $unique_cats = array_unique($cats);
  $unique_cats2 = [];
  foreach ($unique_cats as $key1 => $value1) {
    foreach ($table_names as $key2 => $value2) {
      if (preg_match("#^".$value1."\_#", $value2)) {
        $value2 = substr($value2, strpos($value2, '_')+1);
        $unique_cats2[$value1][]= $value2;
        unset($table_names[$key2]);
      }
    }
  }
  return $unique_cats2;
}

function closeDB(){
  global $con;
  $con->close();
}


?>
