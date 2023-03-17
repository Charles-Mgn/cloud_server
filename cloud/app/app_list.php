<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$response = array();

$directory = "/home/$username/public_html/";
$appList = scandir($directory);
$list = array_diff($appList, array('.', '..'));

foreach ($list as $app) {
  $response[] = array('app_name' => $app, 'app_size' => filesize("/home/$username/public_html/$app"));
}

echo json_encode($response);
