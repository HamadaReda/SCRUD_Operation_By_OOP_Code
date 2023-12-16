<?php
require("user.php");
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

$result = $user->deleteUser($id);
if ($result) {
  header("Location: list.php");
  exit;
} else {
  header("Location: list.php?query_field=");
}
