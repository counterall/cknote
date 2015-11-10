<?php

include_once "functions.php";

connectDB();
if (isset($_POST['update'])) {
  $content = sanitizeString($_POST['content']);
  $sql = "SELECT id FROM notes ORDER BY datetime DESC LIMIT 1";
  $result = querySql($sql, TRUE);
  $result = $result->fetch_array(MYSQLI_ASSOC);
  $id = $result['id'];
  $sql = "UPDATE notes SET content = '$content', datetime = NOW() WHERE id = $id";
  querySql($sql);
}elseif (isset($_POST['edit_update'])) {
  $content = sanitizeString($_POST['content']);
  $title = sanitizeString($_POST['title']);
  $id = $_POST['id'];
  $sql = "UPDATE notes SET content = '$content', title = '$title', datetime = NOW() WHERE id = $id";
  querySql($sql);
}else{
  $category = formatCatName(sanitizeString($_POST['category']));
  $sub_cat = formatCatName(sanitizeString($_POST['sub_cat']));
  $title = sanitizeString($_POST['title']);
  $content = sanitizeString($_POST['content']);
  // var_dump($content);
  $sql = "INSERT INTO notes (category, sub_cat, title, content) VALUES ('$category','$sub_cat','$title', '$content')";
  querySql($sql);
}

closeDB();

?>
