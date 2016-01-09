<?php

function myAutoLoad6($class){
  include_once "./classes/$class".".php";
}

spl_autoload_register('myAutoLoad6');

$main = new MainLib();

if (isset($_POST['recentlyUpdated'])) {
  $return_html1 = $main->getRecentUpdateAndMostSearch();
  $return_html2 = $main->getRecentUpdateAndMostSearch(true);
  $return_html = $return_html1 . 'ckseparator' . $return_html2;
  echo $return_html;
}

if (isset($_POST['updateSubCats'])) {
  $cat = $main->db->sanitizeQuery($_POST['cat']);
  $sql = "SELECT DISTINCT(sub_cat) AS c FROM notes WHERE category = '$cat' ORDER BY c ASC";
  $sub_cats = $main->db->getQuery($sql);
  $subCatsHTML = '';
  foreach ($sub_cats as $value) {
    $val = str_replace(' ', '_', $value['c']);
    $subCatsHTML .= '<option value="'.strtolower($val).'">'.$value['c'].'</option>';
  }
  echo $subCatsHTML;
}

if (isset($_POST['id'])) {
  $id = $main->db->sanitizeQuery($_POST['id']);
  if (isset($_POST['visit'])) {
    $sql = "UPDATE notes SET visits = visits + 1 WHERE id = $id";
    $main->db->setQuery($sql);
  }
  $sql = "SELECT category, sub_cat, title, content FROM notes WHERE id = $id";
  $result = $main->db->getQuery($sql);
  $row = $result[0];
  $data = $row['category']."[separator]";
  $data .= $row['sub_cat']."[separator]";
  $data .= $row['title']."[separator]";
  $data .= $row['content'];
  echo $data;
}

$main->dbClose();
?>
