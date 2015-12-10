<?php

include_once "functions.php";

if (isset($_POST['recentlyUpdated'])) {
  connectDB();
  $return_html1 = getRecentUpdateAndMostSearch();
  $return_html2 = getRecentUpdateAndMostSearch(false);
  closeDB();
  $return_html = $return_html1 . 'ckseparator' . $return_html2;
  echo $return_html;
}

if (isset($_POST['updateSubCats'])) {
  connectDB();
  $sql = "SELECT DISTINCT(sub_cat) AS c FROM notes WHERE category = '".$_POST['cat']."' ORDER BY c ASC";
  $results = querySql($sql, true);
  closeDB();
  $sub_cats = [];
  $subCatsHTML = '';
  while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
    $sub_cats[] = $row['c'];
  }
  foreach ($sub_cats as $key => $value) {
    $val = str_replace(' ', '_', $value);
    $subCatsHTML .= '<option value="'.strtolower($val).'">'.$value.'</option>';
  }
  echo $subCatsHTML;
}

if (isset($_POST['id'])) {
  connectDB();
  $id = $_POST['id'];
  if (isset($_POST['visit'])) {
    $sql = "UPDATE notes SET visits = visits + 1 WHERE id = $id";
    querySql($sql);
  }
  $sql = "SELECT category, sub_cat, title, content FROM notes WHERE id = $id";
  $result = querySql($sql, true);
  closeDB();
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $data = $row['category']."[separator]";
  $data .= $row['sub_cat']."[separator]";
  $data .= $row['title']."[separator]";
  $data .= $row['content'];
  echo $data;
}
?>
