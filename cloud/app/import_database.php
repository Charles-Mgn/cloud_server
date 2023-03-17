<?php
header('Access-Control-Allow-Origin: *');

session_start();

$username = $_SESSION['username'];
$password = $_SESSION['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the uploaded SQL file
    $sql_file = $_FILES['sql_file'];
    $dbname = $_POST['dbname'];

    // Move the file to the user's home directory
    $file_path = "/home/$username/" . $sql_file['name'];
    move_uploaded_file($sql_file['tmp_name'], $file_path);

    // Run the MySQL client to import the file
    $cmd = "sudo mysql -u $username -p'$password' $dbname < $file_path";
    $output = array();
    $retval = 0;
    exec($cmd, $output, $retval);
    if ($retval !== 0) {
        echo "Error importing SQL file: " . implode("\n", $output);
        exit;
    }

    // Redirect back to the profile page
    header('Location: /profile.php');
    exit;
}
