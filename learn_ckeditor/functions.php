<?php

function connectDB(){
  $db_host = 'localhost';
  $db_user = 'root';
  $db_name = 'test';
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

function sanitizeString($sql){
  global $con;
  $sql = stripslashes($sql);
  return $con->real_escape_string($sql);
}

function closeDB(){
  global $con;
  $con->close();
}


?>
