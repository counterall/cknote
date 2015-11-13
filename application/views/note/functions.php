<?php
//functions for manipulating mysql database which saves all notes
$con = '';
function connectDB($db_name = 'cknote'){
  global $con;
  $db_host = 'localhost';
  $db_user = 'root';
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
    $nameArray[$key] = ucfirst(trim($value));
  }
  return $nameToReturn = implode(' ', $nameArray);
}


function getCatAndSubCats(){
  $sql = "SELECT category, sub_cat, GROUP_CONCAT(title) AS titles, GROUP_CONCAT(id) AS ids FROM notes GROUP BY CONCAT(category, sub_cat) ORDER BY category ASC";
  $results = querySql($sql, true);
  $categoryArray = [];
  while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
    $categoryArray[] = $row;
  }

  $categoryArray2 = [];
  foreach ($categoryArray as $row => $value) {
    $titles = explode(',', $value['titles']);
    $ids = explode(',', $value['ids']);
    $notes = array_combine($ids, $titles);
    $categoryArray2[$value['category']][$value['sub_cat']]= $notes;
  }
  unset($categoryArray);

  // $returnArray = [];
  // foreach ($categoryArray2 as $cat => $sub_cat) {
  //   foreach ($sub_cat as $sub_cat => $notes) {
  //     $returnArray[$cat][$sub_cat] = $titlesArray;
  //   }
  // }
  return $categoryArray2;
}

function getRecentUpdateAndMostSearch($update = true){
  global $con;
  if ($update) {
    $sql = "SELECT id, category, sub_cat, title, datetime FROM notes ORDER BY datetime DESC LIMIT 5";
  }else{
    $sql = "SELECT id, category, sub_cat, title, visits FROM notes ORDER BY visits DESC, category ASC LIMIT 5";
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
      $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><pre class='item-meta'>".$value['datetime']."</pre><div class='read-more'><a onclick='showNote(event, this, 0, 0)' href=''>Read More</a></div>";
      $htmlToReturn .= "<div class='note-id'>".$value['id']."</div></div>";
    }else{
      $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><div class='item-meta'>".$value['visits']." Times</div><div class='read-more'><a onclick='showNote(event, this, 0, 0)' href=''>Read More</a></div>";
      $htmlToReturn .= "<div class='note-id'>".$value['id']."</div></div>";
    }
    $htmlToReturn .= "</div>";
  }
  return $htmlToReturn;
}


//functions for manipulating SphinxQL
$sphinx = '';
function connectSphinx(){
  global $sphinx;
  $sphinx_host = '127.0.0.1';
  $port = 9306;

  $sphinx = new mysqli($sphinx_host, '', '', '', $port);
  if ($sphinx->connect_error) {
    die('Connection failed: '.$sphinx->connect_error);
  }
}

function sphinxQuery($keywords, $index = 'note_index'){
  global $sphinx;
  $findMatches = "SELECT * FROM $index WHERE MATCH ('".$keywords."')";
  if (!$results = $sphinx->query($findMatches)) {
    die("Errors (".$sphinx->errno.") found: ".$sphinx->error."\n");
  }else{
    $matches = [];
    while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
      $matches[] = $row;
    }
  }
  return $matches;
}
?>
