<?php 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Authorization, Content-Type');
  header('content-type: application/json; charset=utf-8');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  die();
}

header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

  include_once '../../config/Database.php';
  include_once '../../models/User.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $user = new User($db);

  //Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $user->username = $data->username;
  $user->email = $data->email;
  $user->password = $data->password;
  $user->age = $data->age;
  if(isset($data->checkbox)){
    $user->checkbox = $data->checkbox;
  }

  if($user->create_user()){
    echo json_encode(
      array('message' => 'User registered Successfully.')
    );
  }

  

  