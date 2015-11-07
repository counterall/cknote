<?php

include_once "functions.php";

if (isset($_GET['updateSubCats'])) {
  connectDB();
  $sql = "SELECT DISTINCT(sub_cat) AS c FROM notes WHERE category = '".$_GET['cat']."' ORDER BY c ASC";
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

?>
