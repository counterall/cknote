<?php

include_once "functions.php";

connectDB();

$title = sanitizeString($_GET['title']);
$content = sanitizeString($_GET['content']);

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "UPDATE ckeditor SET title = '$title', content = '$content' WHERE id = $id";
}else{
  $sql = "INSERT INTO ckeditor (title, content) VALUES ('$title', '$content')";

}

querySql($sql);
closeDB();
?>
