<?php

require_once "./autoload.php";

class MainLib {

  public $db;

  public function __construct(){
    $this->db = new MyDB('localhost', 'root', 'mj23kb8i3', 'cknote');
  }

  public function dbClose(){
    $this->db->close();
  }

  public function getCatAndSubCats(){
    $sql = "SELECT category, sub_cat, GROUP_CONCAT(title) AS titles, GROUP_CONCAT(id) AS ids FROM notes GROUP BY CONCAT(category, sub_cat) ORDER BY category ASC";
    $results = $this->db->getQuery($sql);
    $categoryArray = [];
    foreach ($results as $row => $value) {
      $titles = explode(',', $value['titles']);
      $ids = explode(',', $value['ids']);
      $notes = array_combine($ids, $titles);
      $categoryArray[$value['category']][$value['sub_cat']]= $notes;
    }
    unset($results);
    return $categoryArray;
  }

  public function getRecentUpdateAndMostSearch($visits = false){
    if ($visits) {
      $sql = "SELECT id, category, sub_cat, title, visits FROM notes ORDER BY visits DESC, category ASC LIMIT 5";
    }else{
      $sql = "SELECT id, category, sub_cat, title, datetime FROM notes ORDER BY datetime DESC LIMIT 5";
    }
    $results = $this->db->getQuery($sql);

    $htmlToReturn = '';
    foreach ($results as $key => $value) {
      $htmlToReturn .= "<div class='list-item'>";
      $htmlToReturn .= "<div class='list-item-head'>".$value['category']." >>> ".$value['sub_cat']."</div>";
      if (!$visits) {
        $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><pre class='item-meta'>".$value['datetime']."</pre><div class='read-more'><a onclick='showNote(event, this, 0, 0)' href=''>Read More</a></div>";
        $htmlToReturn .= "<div class='note-id'>".$value['id']."</div></div>";
      }else{
        $htmlToReturn .= "<div class='list-item-content'><div class='item-content'>".$value['title']."</div><div class='item-meta'>".$value['visits']." Times</div><div class='read-more'><a onclick='showNote(event, this, 0, 0)' href=''>Read More</a></div>";
        $htmlToReturn .= "<div class='note-id'>".$value['id']."</div></div>";
      }
      $htmlToReturn .= "</div>";
    }
    unset($results);
    return $htmlToReturn;
  }

  public function formatCatName($name){
    $nameArray = explode(' ', $name);
    foreach ($nameArray as $key => $value) {
      $nameArray[$key] = ucfirst(trim($value));
    }
    return $nameToReturn = implode(' ', $nameArray);
  }

}
?>
