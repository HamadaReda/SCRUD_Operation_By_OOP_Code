<?php

require("user.php");
$allUsers = new User();
// echo "<pre>";
// print_r($allUsers->getUsers());
// echo "</pre>";


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Users</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">

</head>

<body>
  <div class="container">


    <?php

    $selectedUsers = $allUsers->getUsers();
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
      if (isset($_GET['search'])) {
        $selectedUsers = $allUsers->searchUser($_GET['search']);
      }
    }

    // Welcome message
    session_start();
    if (isset($_SESSION['id'])) {
      echo "<div class='alert alert-primary text-center'>Welcome " . $_SESSION['name'] . "</div>";
      echo "<a class='btn btn-secondary' href='logout.php'>Logout</a>";
    } else {
      header("Location: login.php");
      exit;
    }

    ?>




    <h1 class="text-center fw-bold">Users List</h1>

    <!-- Search by name or email -->
    <form action="" method="GET">
      <div class="mb-4 mt-3 row">
        <div class="col-4">
          <label for="" class="form-label fw-bold d-flex justify-content-end">Enter Name or Email for Search :</label>
        </div>
        <div class="col-6">
          <input type="text" name="search" class="form-control">
        </div>
        <div class="col-2">
          <input type="submit" value="Search" class="btn btn-secondary">
        </div>
      </div>

    </form>

    <!-- All Users -->
    <table class="table table-light table-hover text-center">
      <thead class="fw-bold">
        <tr>
          <td>Id</td>
          <td>Name</td>
          <td>Email</td>
          <td>Admin</td>
          <td>Avatar</td>
          <td>Actions</td>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($selectedUsers as $row) {
        ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= ($row['admin']) ? "Yes" : "No" ?></td>
            <td>
              <?php
              if ($row['avatar'] != '') {
              ?>
                <img class="avatar" src="<?= "uploads/" . $row['name'] . "." . $row['avatar'] ?>" alt="">
              <?php
              } else {
              ?>
                <i class="fa-solid fa-user"></i>
              <?php
              }
              ?>
            </td>
            <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Edit</a><a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-secondary ms-3">Delete</a></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5" class="fw-bold"><?= $allUsers->countRows() ?> Users</td>
          <td colspan="1"><a href="add.php" class="btn btn-secondary">Add User</a></td>
        </tr>
      </tfoot>
    </table>







  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>