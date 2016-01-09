<?php
function myAutoLoad3($class){
  include_once "$class".".php";
}

spl_autoload_register('myAutoLoad3');


class Sphinx {

  private $db;

  public function __construct(){
    $this->db = new MyDB('127.0.0.1', '', '', '', 9306);
  }

  public function getMatches($keywords, $index = 'note_index'){
    $findMatches = "SELECT * FROM $index WHERE MATCH ('".$keywords."')";
    $matches = $this->db->getSphinxMatches($findMatches);
    return $matches;
  }

}




?>
