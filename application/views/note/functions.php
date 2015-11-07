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

function closeDB(){
  global $con;
  $con->close();
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
  $sql = trim($sql);
  $sql = stripslashes($sql);
  return $con->real_escape_string($sql);
}

function formatCatName($name){
  $nameArray = explode(' ', $name);
  foreach ($nameArray as $key => $value) {
    $nameArray[$key] = ucfirst(strtolower($value));
  }
  return $nameToReturn = implode(' ', $nameArray);
}


function getCatAndSubCats(){
  $sql = "SELECT category, sub_cat, GROUP_CONCAT(title) AS titles FROM notes GROUP BY CONCAT(category, sub_cat) ORDER BY category ASC";
  $results = querySql($sql, true);
  $categoryArray = [];
  while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
    $categoryArray[] = $row;
  }

  $categoryArray2 = [];
  foreach ($categoryArray as $row => $value) {
    $categoryArray2[$value['category']][$value['sub_cat']] = $value['titles'];
  }
  unset($categoryArray);

  $returnArray = [];
  foreach ($categoryArray2 as $cat => $sub_cat) {
    foreach ($sub_cat as $sub_cat => $titles) {
      $titlesArray = explode(',', $titles);
      $returnArray[$cat][$sub_cat] = $titlesArray;
    }
  }
  return $returnArray;
}

function getRecentUpdateAndMostSearch($update = true){
  global $con;
  if ($update) {
    $sql = "SELECT category, sub_cat, title, datetime FROM notes ORDER BY datetime DESC LIMIT 3";
  }else{
    $sql = "SELECT category, sub_cat, title, visits FROM notes ORDER BY visits DESC, category ASC LIMIT 3";
  }
  $results = querySql($sql, TRUE);
  $tmpArray = [];
  while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
    $tmpArray[] = $row;
  }
  unset($results);
  $htmlToReturn = '';
  foreach ($tmpArray as $key => $value) {
    $htmlToReturn .= "<div class='list-item'>";
    $htmlToReturn .= "<div class='list-item-head'>".$value['category']." >>> ".$value['sub_cat']."</div>";
    if ($update) {
      $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><pre class='item-meta'>".$value['datetime']."</pre><div class='read-more'><a href=''>Read More</a></div></div>";
    }else{
      $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><div class='item-meta'>".$value['visits']." Times</div><div class='read-more'><a href=''>Read More</a></div></div>";
    }
    $htmlToReturn .= "</div>";
  }
  return $htmlToReturn;
}


function getMostSearch(){}

?>
