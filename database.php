<?php

class Database
{

  private $db_host = "localhost";
  private $db_user = "root";
  private $db_pass = "";
  private $db_name = "newtest";

  private $mysqli = "";
  private $result = array();
  private $conn = false;

  public function __construct()
  {
    if (!$this->conn) {
      $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
      $this->conn = true;
      if ($this->mysqli->connect_error) {
        array_push($this->result, $this->mysqli->connect_error);
      }
    } else {
    }
  }

  // Function To Insert Into The Database 
  public function insert($table, $params = array())
  {
    // Check To See If The Table Exists 
    if ($this->tableExists($table)) {
      // Seperate $params's Array Keys And Values And Convert Them To String Value 
      $table_columns = implode(', ', array_keys($params));
      $table_value = implode("', '", $params);

      $sql = "INSERT INTO $table ($table_columns) VALUES ('$table_value')";
      // Make The Query To Insert To The Database
      if ($this->mysqli->query($sql)) {
        array_push($this->result, $this->mysqli->insert_id);
        return true;  // The Data Has Been Inserted
      } else {
        array_push($this->result, $this->mysqli->error);
        return false; // The Data Has Not Been Inserted 
      }

    } else {
      return false;   // Table Does Not Exist
    }
  }

  // Function To Update Row In Database 
  public function update($table, $params = array(), $where = null)
  {
    // Check To See If Table Exists
    if ($this->tableExists($table)) {
      // Create Array To Hold All The Columns To Update 
      $args = array();
      foreach ($params as $key => $value) {
        $args[] = "$key = '$value'"; // Seperate Each Column Out With It's Corresponding Value
      }

      $sql = "UPDATE $table SET " . implode(', ', $args);
      if ($where != null) {
        $sql .= " WHERE $where";
      }
      // Make Query To Database 
      if ($this->mysqli->query($sql)) {
        array_push($this->result, $this->mysqli->affected_rows);
        return true; // Update Has Been Successful
      } else {
        array_push($this->result, $this->mysqli->error);
        return false;  // Update Has Not Been Successful 
      }
    } else {
      return false; // The Table Does Not Exist
    }
  }

  // Function To Delete Table Or Row From Database
  public function delete($table, $where = null)
  {
    // Check To See If Table Exists 
    if ($this->tableExists($table)) {
      $sql = "DELETE FROM $table";  // Create Query To Delete Rows 
      if ($where != null) {
        $sql .= " WHERE $where";
      }
      // Submit Query To Database 
      if ($this->mysqli->query($sql)) {
        array_push($this->result, $this->mysqli->affected_rows);
        return true; // The Query Exectued Correctly 
      } else {
        array_push($this->result, $this->mysqli->error);
        return false; // The Query Did Not Execute Correctly
      }

    } else {
      return false; // The Table Does Not Exist
    }
  }

  // Function To Select From The Database
  public function select($table, $rows = "*", $join = null, $where = null, $order = null, $limit = null)
  {
    // Check To See If The Table Exists 
    if ($this->tableExists($table)) {
      // Create Query From The Variables Passed To The Function 
      $sql = "SELECT $rows FROM $table";
      if ($join != null) {
        $sql .= " JOIN $join";
      }
      if ($where != null) {
        $sql .= " WHERE $where";
      }
      if ($order != null) {
        $sql .= " ORDER BY $order";
      }
      if ($limit != null) {
        if (isset($_GET['page'])) {
          $page = $_GET['page'];
        } else {
          $page = 1;
        }
        $start = ($page - 1) * $limit;
        $sql .= " LIMIT $start,$limit";
      }

      $query = $this->mysqli->query($sql);

      if ($query) {
        $this->result = $query->fetch_all(MYSQLI_ASSOC);
        return true; // Query Was Successful
      } else {
        array_push($this->result, $this->mysqli->error);
        return false; // No Rows Were Returned 
      }
    } else {
      return false; // Table Does Not Exist 
    }
  }

  // Function To Show Pagination 
  public function pagination($table, $join = null, $where = null, $limit = null)
  {
    // Check To See If Table Exists
    if ($this->tableExists($table)) {
      if ($limit != null) {
        // Select Count() Query For Pagination 
        $sql = "SELECT COUNT(*) FROM $table";
        if ($join != null) {
          $sql .= " JOIN $join";
        }
        if ($where != null) {
          $sql .= " WHERE $where";
        }

        $query = $this->mysqli->query($sql);

        $total_record = $query->fetch_array();
        $total_record = $total_record[0];

        $total_page = ceil($total_record / $limit);

        $url = basename($_SERVER['PHP_SELF']);
        // Get The Page Number Which Is Set In Url 
        if (isset($_GET['page'])) {
          $page = $_GET['page'];
        } else {
          $page = 1;
        }
        // Show Pagination
        $output = "<ul class='pagination'>";

        if ($page > 1) {
          $output .= "<li><a href='$url?page=" . ($page - 1) . "'>Prev</a></li>";
        }

        if ($total_record > $limit) {
          for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $page) {
              $cls = "class='active'";
            } else {
              $cls = "";
            }
            $output .= "<li><a $cls href='$url?page=$i'>$i</a></li>";
          }
        }
        if ($total_page > $page) {
          $output .= "<li><a href='$url?page=" . ($page + 1) . "'>Next</a></li>";
        }
        $output .= "</ul>";

        echo $output;

      } else {
        return false; // If Limit is null
      }
    } else {
      return false; // Table does not exist
    }
  }

  public function sql($sql)
  {
    $query = $this->mysqli->query($sql);

    if ($query) {
      $this->result = $query->fetch_all(MYSQLI_ASSOC);
      return true; // Query Was Successful 
    } else {
      array_push($this->result, $this->mysqli->error);
      return false; // No Rows Were Returned 
    }
  }

  // Private Function To Check If Table Exists For Use With Queries
  private function tableExists($table)
  {
    $sql = "SHOW TABLES FROM $this->db_name LIKE '$table'";
    $tableInDb = $this->mysqli->query($sql);
    if ($tableInDb) {
      if ($tableInDb->num_rows == 1) {
        return true;  // The Table Exists
      } else {
        array_push($this->result, $table . " Does Not Exist In This Database.");
        return false; // The Table Does Not Exist
      }
    }
  }

  // Public Function To Return The Data To The User 
  public function getResult()
  {
    $val = $this->result;
    $this->result = array();
    return $val;
  }

  // Close Connection
  public function __destruct()
  {
    if ($this->conn) {
      if ($this->mysqli->close()) {
        $this->conn = false;

      }
    } else {

    }
  }

} //Class Close 


?>