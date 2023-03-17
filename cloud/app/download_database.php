<?php
header('Access-Control-Allow-Origin: *');

session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_name = $_POST['dbname'];
    $file = "$db_name.sql";

    // Set up the MySQL command to create a dump of the database
    $command = "sudo mysqldump -u $username -p$password $db_name > $file";

    // Run the command
    exec($command);

    // Download the file
    header("Content-Disposition: attachment; filename=\"$file\"");
    header("Content-Type: application/sql");
    header("Content-Length: " . filesize($file));
    readfile($file);

    // Delete the temporary file
    unlink($file);
}
