<?php
header('Access-Control-Allow-Origin: *');

session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create SSH user
    exec("sudo ./create_user.sh $username $password");

    // Connect to database
    $dsn = 'mysql:host=localhost;dbname=app';
    $dbuser = "root";
    $dbpass = "root";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
        $db = new PDO($dsn, $dbuser, $dbpass, $options);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }

    // Create database user
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hash);
    $stmt->execute();
}

$_SESSION['username'] = $username;
$_SESSION['password'] = $password;
header('Location: profile.php');
exit;
