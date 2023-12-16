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

    require 'MysqlAdapter.php';
    require 'database_config.php';

    class User extends MysqlAdapter
    {
      // Set the table name
      private $_table = 'users';
      public function __construct()
      {
        // Add from the database configuration file
        global $config;
        // Call the parent constructor
        parent::__construct($config);
      }
      // List All Users
      // return array Returns every user row as array of associative array
      public function getUsers()
      {
        $this->select($this->_table);
        return $this->fetchAll();
      }
      // Show one user
      // param it $user_id
      // return array Returns a user row ass associative array
      public function getUser($user_id)
      {
        $this->select($this->_table, 'id = ' . $user_id);
        return $this->fetch();
      }
      /**
       * Add New User
       * param array $user_data Associative array containing column and value
       * return int Returns the id of the user inserted
       */
      public function addUser($user_data)
      {
        return $this->insert($this->_table, $user_data);
      }
      /**
       * Update existing user
       * param array $user_data Associative array containing column and value
       * param int $user_id 
       * return int number of affected row 
       */
      public function updateUser($user_id, $user_data)
      {
        return $this->update($this->_table, $user_data, "id = " . $user_id);
      }
      /**
       * Delete existing 
       * param int $user_id
       * return int number of affected rows
       */
      public function deleteUser($user_id)
      {
        return $this->delete($this->_table, "id = " . $user_id);
      }
      /**
       * Search existing users
       * param string $keyword
       * return array Returns every user as array of associative array
       */
      public function searchUser($keyword)
      {
        $this->select($this->_table, "name LIKE '%$keyword%' OR email LIKE '%$keyword%'");
        return $this->fetchAll();
      }
      public function selectUser($email, $password)
      {
        $password = sha1($password);
        $this->select($this->_table, "email = '$email' AND password = '$password' LIMIT 1");
        return $this->fetch();
      }
    }
    ?>
  </div>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>