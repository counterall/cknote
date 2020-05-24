<?php
function myAutoLoad3($class){
  include_once "$class".".php";
}

spl_autoload_register('myAutoLoad3');


class Sphinx {

  private $db;

  public function __construct(){
    $this->db = new MyDB('note_sphinxsearch', '', '', '', 9306);
  }

  public function getMatches($keywords, $index = 'note_index'){
    $keywords = $this->db->sanitizeQuery($keywords);
    $findMatches = "SELECT * FROM $index WHERE MATCH ('".$keywords."')";
    $matches = $this->db->getQuery($findMatches);
    return $matches;
  }

  public function dbClose(){
    $this->db->close();
  }
}




?>
