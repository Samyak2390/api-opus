<?php 
session_start();
//check if user is logged in and is admin
if(!isset($_SESSION['token']) || !isset($_SESSION['role']) || $_SESSION['role'] !== '1'){
  print_r(json_encode(
    array('message' => "You are not authorized.")
  ));
  http_response_code(401);
  exit();
}
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

  include_once '../../config/Database.php';
  include_once '../../models/User.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $user = new User($db);
  $data = json_decode(file_get_contents("php://input"));

  if(!empty($data->id)){
    $user->id = $data->id;
    if($user->delete_user()){
      //make json
      print_r(json_encode(
        array('message' => "User has been removed.")
      ));
    }else{
      print_r(json_encode(
        array('message' => "User Id is invalid.")
      ));
      http_response_code(400);
    }
  }else{
    print_r(json_encode(
      array('message' => "User Id is not given.")
    ));
    http_response_code(400);
  }


  