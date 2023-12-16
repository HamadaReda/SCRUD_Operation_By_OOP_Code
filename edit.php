<?php
require("user.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <div class="container">

    <?php

    // Welcome message
    session_start();
    if (isset($_SESSION['id'])) {
      echo "<div class='alert alert-primary text-center'>Welcome " . $_SESSION['name'] . "</div>";
      echo "<a class='btn btn-secondary' href='logout.php'>Logout</a>";
    } else {
      header("Location: login.php");
      exit;
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $user = new User();
    $row = $user->getUser($id);
    $error_fields = array();
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
      if (!(isset($_POST['name']) && !empty($_POST['name']))) {
        $error_fields[] = 'name';
      }
      if (!(isset($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
        $error_fields[] = "email";
      }
      if (!(isset($_POST['password']) && strlen($_POST['password']) > 5)) {
        $error_fields[] = "password";
      }
      if (!$error_fields) {
        $admin = (isset($_POST['admin'])) ? 1 : 0;
        $uploads_dir = $_SERVER['DOCUMENT_ROOT'] . "/admin/users_oop/uploads/";
        if ($_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
          $tmp_name = $_FILES['avatar']['tmp_name'];
          $avatar = basename($_FILES['avatar']['name']);
          move_uploaded_file($tmp_name, "$uploads_dir/" . $_POST['name'] . ".$avatar");
        } else {
          if ($_FILES['avatar']['name'] == '') {
            $avatar = '';
          } else {
            echo "<div class='alert alert-danger text-center container'>File can't be uploaded</div>";
            exit;
          }
        }
        $data = ['name' => $_POST['name'], 'email' => $_POST['email'], 'password' => sha1($_POST['password']), 'admin' => $admin, 'avatar' => $avatar];
        $result = $user->updateUser($id, $data);

        if ($result > 0) {
          header("Location: list.php");
          exit;
        } else {
          if (mysqli_errno($user->connect()) == 1062) {
            $error_fields[] = "duplicate_email";
          }
        }
      }
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
      <div class="bg-light p-3 mt-3">
        <div class="mb-3">
          <label for="name" class="form-label fw-bold">Name</label>
          <input type="text" name="name" class="form-control" value="<?php if (isset($_POST['name'])) {
                                                                        echo $_POST['name'];
                                                                      } else {
                                                                        echo $row['name'];
                                                                      }
                                                                      ?>"><?php if (in_array('name', $error_fields)) {
                                                                            echo "<p class='text-danger'>* Please enter your name</p>";
                                                                          } ?>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label fw-bold">Email</label>
          <input type="text" name="email" class="form-control" value="<?php if (isset($_POST['email'])) {
                                                                        echo $_POST['email'];
                                                                      } else {
                                                                        echo $row['email'];
                                                                      }
                                                                      ?>"><?php if (in_array("email", $error_fields)) {
                                                                            echo "<p class='text-danger'>* Please enter a valid email</p>";
                                                                          }
                                                                          if (in_array("duplicate_email", $error_fields)) {
                                                                            echo "<p class='text-danger'>* This email is exist, please enter another email</p>";
                                                                          }
                                                                          ?>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label fw-bold">Password</label>
          <input type="password" name="password" class="form-control"><?php if (in_array('password', $error_fields)) {
                                                                        echo "<p class='text-danger'>* Please enter a password not less than 5 characters</p>";
                                                                      } ?>
        </div>
        <div class="mb-3">
          <label for="avatar" class="form-label fw-bold">Select Avatar</label>
          <input type="file" name="avatar" class="form-control">
        </div>
        <div class="mb-3">
          <div class="check-avatar">
            <input type="checkbox" name="admin" class="check-form-input" <?= ($row['admin']) ? "checked" : "" ?>>
            <label for="admin" class="check-form-label">Admin</label>
          </div>
        </div>
        <input type="submit" value="Edit" class="btn btn-secondary">
      </div>
    </form>

  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>