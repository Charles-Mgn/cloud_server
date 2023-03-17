<?php
session_start();
// Check if the user is already logged in
if (!$_SESSION['username']) {
    header('Location: index.php');
    exit;
}
$username = $_SESSION['username'];
$password = $_SESSION['password'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>My profile</title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>
    <h1>My profile</h1>
    <p>Username: <?php echo $username; ?></p>
    <p>SSH access: <?php echo $username; ?>@20.84.61.164 (SSH default password = app password)</p>
    <h2>Disconnect</h2>
    <form method="post" action="/logout.php">
        <input type="submit" value="Logout">
    </form>
    <hr>
    <h2>Upload your app</h2>
    <form method="post" action="/upload_app.php" enctype="multipart/form-data">
        <label for="file">Select a zip file:</label>
        <input type="file" name="file[]" accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed">
        <input type="submit" value="Upload app">
    </form>
    <hr>
    <h2>Currently hosted apps</h2>
    <div>
        <?php
            $directory = "/home/$username/public_html/";
            $appList = scandir($directory);
            $list = array_diff($appList, array('.', '..'));
	    foreach ($list as $app) {
                echo "<p style='display: inline-block'>$app </p>";
                echo "<a href='http://20.84.61.164/$username/public_html/$app' target='_blank' class='a-block'>View</a>";
                echo "(" . number_format((float)filesize("/home/$username/public_html/$app") / (1024*1024), 3, '.', '') . " MB)"; // Display the size of the file in MB ?>
                <form method='POST' action='/delete_app.php' class='delete-form'>
                  <input type='hidden' name='app' value='<?php echo $app ?>'>
                  <input type='submit' value='Delete'>
                </form><br>
                <?php
            }
            if (count($list) == 0) { echo "<div>You don't have any hosted app at the moment.</div>"; }
        ?>
    </div>
    <hr>
    <h2>Create database</h2>
    <form method="post" action="/create_database.php">
        <label for="dbname">DB name</label>
        <input type="text" name="dbname">
        <input type="submit" value="Create DB">
    </form>
    <hr>
    <h2>Currently hosted databases</h2>
    <div>
        <?php
            // Connect to the MySQL server
            $conn = mysqli_connect("localhost", $username, $password);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Retrieve a list of databases
            $sql = "SELECT schema_name FROM information_schema.schemata WHERE schema_name NOT IN ('mysql', 'information_schema', 'performance_schema')";
            $result = mysqli_query($conn, $sql);

            // Display the list of databases with a delete button
            if(mysqli_num_rows($result) == 0) {
                echo "You don't have any hosted database at the moment.";
            } else {
                while($row = mysqli_fetch_assoc($result)) {
                    $db_name = $row['schema_name'];

                    // Get the database size in bytes
                    $query = "SELECT SUM(data_length + index_length) as size FROM information_schema.TABLES WHERE table_schema = '$db_name'";
                    $result2 = mysqli_query($conn, $query);
                    $row2 = mysqli_fetch_assoc($result2);
                    $size = $row2['size'];

                    echo "<p style='display:inline'>$db_name (" . number_format((float)$size / (1024*1024), 3, '.', '') . " MB)</p>";
                    ?>
                    <form method="post" action="/import_database.php" enctype="multipart/form-data" class='delete-form'>
                        <input type="file" name="sql_file">
                        <input type="hidden" name="dbname" value="<?php echo $db_name ?>">
                        <input type="submit" value="Import">
                    </form>
                    <form method="post" action='/download_database.php' class='delete-form'>
                        <input type='hidden' name='dbname' value='<?php echo $db_name ?>'>
                        <input type='submit' value='Download'>
                    </form>
                    <form method="post" action='delete_database.php' class='delete-form'>
                         <input type='hidden' name='dbname' value='<?php echo $db_name ?>'>
                         <input type='submit' value='Delete'>
                    </form>
                    <?php
                }
            }

            // Close the database connection
            mysqli_close($conn);
        ?>
    </div>
</body>
</html>
