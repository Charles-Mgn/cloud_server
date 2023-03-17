<?php
header('Access-Control-Allow-Origin: *');

session_start();

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the app database
    $dsn = 'mysql:host=localhost;dbname=app';
    $dbuser = 'root';
    $dbpass = 'root';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );
    try {
        $db = new PDO($dsn, $dbuser, $dbpass, $options);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }

    // Query the users table to retrieve the user's hashed password and salt
    $stmt = $db->prepare('SELECT password FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a matching user is found and the password is correct, create a session and redirect to the profile page
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header('Location: profile.php');
        exit;
    } else {
        // If the login failed, display an error message
        $error = 'Invalid username or password.';
        echo $error;
        echo $username;
        echo $password;
        echo hash('sha256', $password);
    }
}
