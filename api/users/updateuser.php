<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Authorization, Content-Type');
  header('content-type: multipart/form-data; charset=utf-8');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  die();
}

header("Access-Control-Allow-Origin: *");
header('content-type: multipart/form-data; charset=utf-8');

//check if user is logged in and is admin
if(!isset($_SESSION['token']) || !isset($_SESSION['role']) || $_SESSION['role'] !== '1'){
  print_r(json_encode(
    array('message' => "You are not authorized.")
  ));
  http_response_code(401);
  exit();
}

  include_once '../../config/Database.php';
  include_once '../../models/User.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $user = new User($db);

  //Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $user->id = $data->id;
  $user->username = $data->username;
  $user->email = $data->email;
  $user->password = $data->password;
  $user->age = $data->age;
  $user->role = $data->role;

  if($user->update_user()){
    echo json_encode(
      array('message' => 'User updated Successfully.')
    );
  }

  

  