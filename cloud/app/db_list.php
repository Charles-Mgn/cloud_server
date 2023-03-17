<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$response = array();

// Connect to the MySQL server
$conn = mysqli_connect("localhost", $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve a list of databases
$sql = "SELECT schema_name FROM information_schema.schemata WHERE schema_name NOT IN ('mysql', 'information_schema', 'performance_schema')";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
    $db_name = $row['schema_name'];

    // Get the database size in bytes
    $query = "SELECT SUM(data_length + index_length) as size FROM information_schema.TABLES WHERE table_schema = '$db_name'";
    $result2 = mysqli_query($conn, $query);
    $row2 = mysqli_fetch_assoc($result2);
    $size = $row2['size'];

    $response[] = array('db_name' => $db_name, 'db_size' => $size);
}

// Close the database connection
mysqli_close($conn);

echo json_encode($response);
