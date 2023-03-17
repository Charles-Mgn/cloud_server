<?php
header('Access-Control-Allow-Origin: *');

session_start();
$dbuser = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbname = $_POST['dbname'];

    // Call shell script to create database
    exec("sudo ./create_database.sh $dbname $dbuser");

    // Redirect back to the profile page
    header('Location: /profile.php');
    exit;
}
