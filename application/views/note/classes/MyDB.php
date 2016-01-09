<?php
class MyDB {

  private $connection;
  private $host;
  private $username;
  private $passwd;
  private $db;
  private $port;

  public function __construct($host='localhost', $username='root', $passwd='', $db='', $port=3306){
    $this->host = $host;
    $this->username = $username;
    $this->passwd = $passwd;
    $this->db = $db;
    $this->port = $port;

    if(!$this->connection = new mysqli($this->host, $this->username, $this->passwd, $this->db, $this->port)){
      die("Error(".$this->connection->connect_errno."): ".$this->connection->connect_error);
    }
  }

  public function close(){
    $this->connection->close();
  }

  private function sanitizeQuery($sql){
    $sql = trim($sql);
    $sql = stripslashes($sql);
    return $sql;
  }

  public function getQuery($sql, $returnType=MYSQLI_ASSOC){
    $sql = $this->sanitizeQuery($sql);
    $sql = $this->connection->real_escape_string($sql);
    if (!$return = $this->connection->query($sql)) {
      die("Error(".$this->connection->errno."): ".$this->connection->error);
    }
    $array = $return->fetch_all($returnType);
    $return->free();
    if (count($array)) {
      return $array;
    }else{
      die("No results found in database!\n");
    }
  }

  public function setQuery($sql){
    $sql = $this->sanitizeQuery($sql);
    $sql = $this->connection->real_escape_string($sql);
    if (!$return = $this->connection->query($sql)) {
      die("Error(".$this->connection->errno."): ".$this->connection->error);
    }
  }

  public function getMultiQuery($sql, $returnType=MYSQLI_ASSOC){
    $sql = $this->sanitizeQuery($sql);
    $sql = $this->connection->real_escape_string($sql);
    if ($this->connection->multi_query($sql)) {
      $return = [];
      $n = 0;
      $continue = true;
      do {
        $resultSet = $this->connection->store_result();
        $return[$n] = $resultSet->fetch_all($returnType);
        $resultSet->free();
        $n++;
        if (!$this->connection->next_result()) {
          if ($this->connection->errno) {
            $m = $n + 1;
            if ($m == 2) {
              $whichSql = "2nd";
            }elseif ($m == 3) {
              $whichSql = "3rd";
            }else{
              $whichSql = $m."th";
            }
            echo "Error(".$this->connection->errno.") for $whichSql query statement: ".$this->connection->error."\n";
          }
          $continue = false;
        }
      } while ($continue);
    }else{
      die("Error(".$this->connection->errno."): ".$this->connection->error);
    }
    $empty = true;
    foreach ($return as $resultSet) {
      if (count($resultSet)) {
        $empty = false;
      }
    }
    if (!$empty) {
      return $return;
    }else{
      die("No results found!\n");
    }
  }

}
?>
