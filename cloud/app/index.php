<?php
// Start a session to store user data
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
    <meta charset="UTF-8" />
    <title>Cloud</title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body class="log-form">
    <h1>Welcome on Cloud</h1>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <label for="username">username</label>
        <input type="text" name="username">
        <label for="password">password</label>
        <input type="password" name="password">
        <input type="submit">
    </form>
    <a href="/register.php" class="a-block">Don't have an account yet?</a>
</body>
</html>
