<?php

include_once "functions.php";

connectDB();
if (isset($_GET['update'])) {
  $content = sanitizeString($_GET['content']);
  $sql = "SELECT id FROM notes ORDER BY datetime DESC LIMIT 1";
  $result = querySql($sql, TRUE);
  $result = $result->fetch_array(MYSQLI_ASSOC);
  $id = $result['id'];
  $sql = "UPDATE notes SET content = '$content' WHERE id = $id";
  querySql($sql);
}else{
  $category = formatCatName(sanitizeString($_GET['category']));
  $sub_cat = formatCatName(sanitizeString($_GET['sub_cat']));
  $title = sanitizeString($_GET['title']);
  $content = sanitizeString($_GET['content']);
  $sql = "INSERT INTO notes (category, sub_cat, title, content) VALUES ('$category','$sub_cat','$title', '$content')";
  querySql($sql);
}

closeDB();

?>
