<?php
require("user.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css.map">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/style.css">

</head>

<body>
  <div class="container">

    <?php
    $user = new User();
    session_start();
    $error;
    $row;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['email']) && isset($_POST['password'])) {
        $row = $user->selectUser($_POST['email'], $_POST['password']);
      }
      if ($row) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        header("Location: list.php");
        exit;
      } else {
        $error = "Invalid email or password";
      }
    }
    ?>

    <form action="" method="POST">
      <div class="bg-light p-3 mt-3">
        <div class="mb-3">
          <label for="email" class="form-label fw-bold">Email</label>
          <input type="text" name="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : "" ?>" class="form-control">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label fw-bold">Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <?php
        if (isset($error)) {
          echo "<p class='text-danger'>* $error</p>";
        }
        ?>
        <input type="submit" value="Login" class="btn btn-secondary">
      </div>
    </form>


  </div>


  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/bootstrap.bundle.min.js.map"></script>
  <script src="js/all.min.js"></script>
</body>

</html>