<?php
session_start();
// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Create User</title>
    <link rel="stylesheet" href="/styles/styles.css">
  </head>
  <body class="log-form">
    <h1>Create an account</h1>
    <form method="post" action="create_user.php">
      <h2>Your profile informations</h2>
      <label for="username">Username</label>
      <input type="text" name="username">
      <label for="password">Password</label>
      <input type="password" name="password">
      <input type="submit">
    </form>
    <a href="/" class="a-block">Return</a>
  </body>
</html>
