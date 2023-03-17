<?php
session_start();
$dbuser = $_SESSION['username'];
$dbpass = $_SESSION['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = $_POST['dbname'];

    // Connect to the MySQL server
    $conn = mysqli_connect("localhost", $dbuser, $dbpass);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Execute the DROP DATABASE command
    $sql = "DROP DATABASE $database";
    if (mysqli_query($conn, $sql)) {
        echo "Database $database deleted successfully.";
    } else {
        echo "Error deleting database: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);

    header("Location: /profile.php");
    exit();
}
