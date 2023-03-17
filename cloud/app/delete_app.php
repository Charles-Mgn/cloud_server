<?php
header('Access-Control-Allow-Origin: *');

session_start();
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $app = $_POST['app'];

    // Run shell script to delete the file
    exec("sudo ./delete_app.sh $username $app");

    // Redirect back to the profile page
    header('Location: /profile.php');
    exit;
}

