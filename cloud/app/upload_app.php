<?php
header('Access-Control-Allow-Origin: *');

session_start();
// Current username
$username = $_SESSION['username'];
exec("sudo chown -R $username:www-data /home/$username && sudo chmod -R 775 /home/$username && sudo chmod -R 775 /etc/nginx/sites-available");

// Set the target directory to the user's home directory
$uploadDir = "/home/$username/public_html";

if (isset($_FILES['file'])) {
  foreach ($_FILES['file']['tmp_name'] as $key => $tmpName) {
    $fileName = $_FILES['file']['name'][$key];
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($tmpName, $filePath)) {
      $extension = pathinfo($filePath, PATHINFO_EXTENSION);
      if ($extension === 'zip') {
        // Extract zip file to the user's directory
        $zip = new ZipArchive;
        if ($zip->open($filePath) === true) {
          $zip->extractTo($uploadDir);
          $zip->close();
          header("Location: /profile.php");
          exit();
        } else {
          echo "Error extracting zip file: $fileName\n";
        }
      }
    } else {
      echo "Error uploading file: $fileName\n";
    }
  }
} else {
  echo "$_FILES not set";
}

