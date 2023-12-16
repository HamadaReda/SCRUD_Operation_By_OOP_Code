<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MysqlAdapter</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">

    <?php

    class MysqlAdapter
    {
      protected $_config = array();
      protected $_link;
      protected $_result;
      // Constructor
      public function __construct(array $config)
      {
        if (count($config) !== 4) {
          echo "<div class='alert alert-danger text-center container'>";
          // throw new InvalidArgumentException("Invalid number of connection parameters");
          echo "Invalid number of connection parameters";
          echo "</div>";
          exit;
        }
        $this->_config = $config;
      }
      // Connect to MySQL
      public function connect()
      {
        // Connect only once
        if ($this->_link === null) {
          list($host, $user, $password, $database) = $this->_config;
          if (!$this->_link = @mysqli_connect($host, $user, $password, $database)) {
            echo "<div class='alert alert-danger text-center container'>";
            // throw new RuntimeException("Error connecting to the server : " . mysqli_connect_error());
            echo "Error connecting to the server : " . mysqli_connect_error();
            echo "</div>";
            exit;
          }
          unset($host, $user, $password, $database);
        }
        return $this->_link;
      }
      // Execute the specified query
      public function query($query)
      {
        if (!is_string($query) || empty($query)) {
          echo "<div class='alert alert-danger text-center container'>";
          // throw new InvalidArgumentException("The specified query is not valid.");
          echo "The specified query is not valid.";
          echo "</div>";
          exit;
        }
        // lazy connect to MySQL
        $this->connect();
        if (!$this->_result = mysqli_query($this->_link, $query)) {
          if (mysqli_errno($this->_link) == 1062) {
            return "Duplicate";
          } else {
            echo "<div class='alert alert-danger text-center container'>";
            // throw new RuntimeException("Error executing the specified query " . $query . mysqli_error($this->_link));
            echo "Error executing the specified query " . $query . mysqli_error($this->_link);
            echo "</div>";
            exit;
          }
        }
        return $this->_result;
      }
      // Perform a SELECT statement
      public function select($table, $where = '', $fields = '*', $order = '', $limit = null, $offset = null)
      {
        $query = "SELECT " . $fields . " FROM " . $table . (($where) ? " WHERE " . $where : "") .
          (($limit) ? " LIMIT " . $limit : "") .
          (($offset && $limit) ? " OFFSET " . $offset : "") .
          (($order) ? " ORDER BY " . $order : "");
        $this->query($query);
        return $this->countRows();
      }
      // Perform an INSERT statement
      public function insert($table, array $data)
      {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_map(array($this, "quoteValue"), array_values($data)));
        $query = "INSERT INTO " . $table . " (" . $fields . ") " . "VALUES " . " (" . $values . ")";
        $this->query($query);
        return $this->getInsertId();
      }
      // Perform an UPDATE statement
      public function update($table, array $data, $where = "")
      {
        $set = array();
        foreach ($data as $field => $value) {
          $set[] = $field . " = " . $this->quoteValue($value);
        }
        $set = implode(", ", $set);
        $query = "UPDATE " . $table . " SET " . $set . (($where) ? " WHERE " . $where : "");
        $this->query($query);
        return $this->getAffectedRows();
      }
      // Perform a DELETE statement
      public function delete($table, $where = "")
      {
        $query = "DELETE FROM " . $table .
          (($where) ? " WHERE " . $where : "");
        $this->query($query);
        return $this->getAffectedRows();
      }
      // Escape the specified value
      public function quoteValue($value)
      {
        $this->connect();
        if ($value === null) {
          $value = "NULL";
        } else if (!is_numeric($value)) {
          $value = "'" . mysqli_real_escape_string($this->_link, $value) . "'";
        }
        return $value;
      }
      // Fetch a single row from the current result set (as an associative array)
      public function fetch()
      {
        if ($this->_result !== null) {
          if (($row = mysqli_fetch_array($this->_result, MYSQLI_ASSOC)) === false) {
            $this->freeResult();
          }
          return $row;
        }
        return false;
      }
      // Fetch all rows from the current result set (as an array of associative arrays)
      public function fetchAll()
      {
        if ($this->_result !== null) {
          if (($all = mysqli_fetch_all($this->_result, MYSQLI_ASSOC)) === false) {
            $this->freeResult();
          }
          return $all;
        }
        return false;
      }
      // Get the insertion ID
      public function getInsertId()
      {
        return $this->_link !== null ?
          mysqli_insert_id($this->_link) : null;
      }
      // Get the number of rows returned by the current result set
      public function countRows()
      {
        return $this->_result !== null ?
          mysqli_num_rows($this->_result) : 0;
      }
      // Get the number of affected rows
      public function getAffectedRows()
      {
        return $this->_link !== null ?
          mysqli_affected_rows($this->_link) : 0;
      }
      // Free up the current result set
      public function freeResult()
      {
        if ($this->_result === null) {
          return false;
        }
        mysqli_free_result($this->_result);
        return true;
      }
      // Close explicitly the database connection
      public function disconnect()
      {
        if ($this->_link == null) {
          return false;
        }
        mysqli_close($this->_link);
        $this->_link = null;
        return true;
      }
      // Close automatically the database connection when the instance of the class is destroyed
      public function __destruct()
      {
        $this->disconnect();
      }
    }

    ?>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>