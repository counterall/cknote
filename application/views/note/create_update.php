<?php

include_once "functions.php";

connectDB();
if (isset($_POST['update'])) {
  $category = formatCatName(sanitizeString($_POST['category']));
  $sub_cat = formatCatName(sanitizeString($_POST['sub_cat']));
  $content = sanitizeString($_POST['content']);
  $title = formatCatName(sanitizeString($_POST['title']));
  if (isset($_POST['id'])) {
    $id = $_POST['id'];
  }else{
    $sql = "SELECT id FROM notes ORDER BY datetime DESC LIMIT 1";
    $result = querySql($sql, TRUE);
    $result = $result->fetch_array(MYSQLI_ASSOC);
    $id = $result['id'];
  }
  $sql = "UPDATE notes SET category = '$category', sub_cat = '$sub_cat', content = '$content', title = '$title', datetime = NOW() WHERE id = $id";
  querySql($sql);
}else{
  $category = formatCatName(sanitizeString($_POST['category']));
  $sub_cat = formatCatName(sanitizeString($_POST['sub_cat']));
  $title = formatCatName(sanitizeString($_POST['title']));
  $content = sanitizeString($_POST['content']);
  // var_dump($content);
  $sql = "INSERT INTO notes (category, sub_cat, title, content) VALUES ('$category','$sub_cat','$title', '$content')";
  querySql($sql);
}

closeDB();

?>
