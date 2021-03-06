<?php

require_once "./autoload.php";

if (isset($_POST['query'])) {
  $keywords = $_POST['query'];
}else{
  die('No keywords are received!');
}

$sphinxSearch = new Sphinx();
$matches = $sphinxSearch->getMatches($keywords);

$sphinxSearch->dbClose();

$htmlToReturn = '';
foreach ($matches as $key => $value) {
  $htmlToReturn .= "<div class='list-item'>";
  $htmlToReturn .= "<div class='list-item-head'>".$value['category']." >>> ".$value['sub_cat']."</div>";
  $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><div class='read-more'><a onclick='showNote(event, this, 0, 1)' href=''>Read More</a></div>";
  $htmlToReturn .= "<div class='note-id'>".$value['id']."</div></div>";
  $htmlToReturn .= "</div>";
}

echo $htmlToReturn;

?>
