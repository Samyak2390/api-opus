<?php

  include 'connection.php';

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
  }

  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');


  $condition = " CustomerID";

  if(isset($_GET['userid'])){
    $condition = " CustomerID=".$_GET['userid'];
  }

  $userData = mysqli_query($connection, "SELECT * FROM customer WHERE".$condition);

  $response = array();

  while($row=mysqli_fetch_assoc($userData)){
    $response[] = $row;
  }
  
  echo json_encode($response);
  exit;
?>