<?php

class Database
{
  private $conn;
  private static  $host = CONFIG['db_host'];
  private static  $user = CONFIG['db_user'];
  private static  $password = CONFIG['db_password'];
  private static  $database = CONFIG['db_database'];

  public function __construct()
  {
    $this->connect();
  }

  private function connect()
  {
    $mysqli = new mysqli(self::$host, self::$user, self::$password, self::$database);
    if ($mysqli->connect_errno) {
      throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }
    $this->conn = $mysqli;
  }

  public function get($table, $fields=["*"], $where=array())
  {
    $query = "SELECT ".implode(",", $fields)." FROM $table";
    if (!empty($where)) {
      $query .= " WHERE ";
      foreach ($where as $key => $value) {
        $query .= $key."=".$value." AND ";
      }
      $query = substr($query, 0, -4);
    }
    $result = $this->conn->query($query);
    if (!$result) {
      throw new Exception($this->conn->error);
    }
    if ($result->num_rows < 1) {
      return null;
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function getCount($table, $field, $where=array())
  {
    $query = "SELECT  count(".$field.") as count FROM $table";
    if (!empty($where)) {
      $query .= " WHERE ";
      foreach ($where as $key => $value) {
      	if (is_string($value)) {
        	$query .= $key."='".$value."' AND ";
      	} else {
        	$query .= $key."=".$value." AND ";
      	}
      }
      $query = substr($query, 0, -4);
    }

    $result = $this->conn->query($query);

    if (!$result) {
      throw new Exception($this->conn->error);
    }

    if ($result->num_rows < 1) {
      return null;
    }

    return $result->fetch_row();
  }

  public function getDistinct($table, $field)
  {
    $query = "SELECT DISTINCT ".$field." FROM $table";
    $result = $this->conn->query($query);
    if (!$result) {
      throw new Exception(mysqli_error($this->conn));
    }
    if ($result->num_rows < 1) {
      return null;
    }
    $data = [];
      foreach ($result->fetch_all() as $row) {
          $data[] = $row[0];
      }
    return $data;
  }

  public function insert ($table, $fields=array(), $values=array())
  {
    if (count($fields) > 1) {
      $query = "INSERT INTO ". $table ." (".implode(",", $fields).") values (".implode(",", $values).")";
    } else {
      $query = "INSERT INTO ". $table ." (".$fields[0].") values (".$values[0].")";
    }
    $result = $this->conn->query($query);
    if (!$result) {
      throw new Exception($this->conn->error);
    }

    return $this->conn->insert_id;
  }

  public function update ($table, $fields=array(), $values=array(), $where=array())
  {
    $query = "UPDATE ". $table . " set ";
    foreach ($fields as $key => $f) {
      if ($key == 0) {
        $query .= "$f = '".$values[$key]. "' ";
      } else {
        $query .= ",$f = '".$values[$key]. "' ";
      }
    }

    if (!empty($where)) {
      $query .= " WHERE ";
      foreach ($where as $key => $value) {
        if (is_string($value)) {
          $query .= $key."='".$value."' AND ";
        } else {
          $query .= $key."=".$value." AND ";
        }
      }
      $query = substr($query, 0, -4);
    }

    $result = $this->conn->query($query);
    if (!$result) {
      throw new Exception($this->conn->error);
    }
  }
}

?>
