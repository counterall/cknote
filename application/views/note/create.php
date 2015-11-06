<?php

include_once "functions.php";

connectDB();

$category = sanitizeString($_GET['category']);
$table = sanitizeString($_GET['table']);
$title = sanitizeString($_GET['title']);
$content = sanitizeString($_GET['content']);

if (!$_GET['update']) {
  if ($_GET['create']) {
    $sql = "CREATE TABLE $table_name LIKE temp; INSERT INTO $table_name (title, content) VALUES ('$title', '$content')";
    multiQuery($sql);
  }else{
    $sql = "INSERT INTO $table_name (title, content) VALUES ('$title', '$content')";
    querySql($sql);
  }
}else{
  $sql = "SELECT id FROM $table_name ORDER BY datetime DESC LIMIT 1";
  $result = querySql($sql, TRUE);
  $result = $result->fetch_array(MYSQLI_ASSOC);
  $id = $result['id'];
  $sql = "UPDATE $table_name SET title = '$title', content = '$content' WHERE id = $id";
  querySql($sql);
}

closeDB();

?>
