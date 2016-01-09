<?php

function myAutoLoad5($class){
  include_once "./classes/$class".".php";
}

spl_autoload_register('myAutoLoad5');

$main = new MainLib();

if (isset($_POST['update'])) {

  $category = $main->formatCatName($main->db->sanitizeQuery($_POST['category']));
  $sub_cat = $main->formatCatName($main->db->sanitizeQuery($_POST['sub_cat']));
  $title = $main->formatCatName($main->db->sanitizeQuery($_POST['title']));
  $content = $main->db->sanitizeQuery($_POST['content']);

  if (isset($_POST['id'])) {
    $id = $_POST['id'];
  }else{
    $sql = "SELECT id FROM notes ORDER BY datetime DESC LIMIT 1";
    $result = $main->db->getQuery($sql);
    $id = $result['id'];
  }
  $sql = "UPDATE notes SET category = '$category', sub_cat = '$sub_cat', content = '$content', title = '$title', datetime = NOW() WHERE id = $id";
  $main->db->setQuery($sql);
}else{
  $category = $main->formatCatName($main->db->sanitizeQuery($_POST['category']));
  $sub_cat = $main->formatCatName($main->db->sanitizeQuery($_POST['sub_cat']));
  $title = $main->formatCatName($main->db->sanitizeQuery($_POST['title']));
  $content = $main->db->sanitizeQuery($_POST['content']);

  $sql = "INSERT INTO notes (category, sub_cat, title, content) VALUES ('$category','$sub_cat','$title', '$content')";
  $main->db->setQuery($sql);
}

$main->dbClose();
?>
