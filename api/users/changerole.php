<?php 
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: http://localhost:8081');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Authorization, Content-Type, Access-Control-Allow-Credentials');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  header("Access-Control-Allow-Credentials: true");
  die();
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: http://localhost:8081");
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

  if(!empty($data->id)){
    $user->id = $data->id;
    if($user->change_role()){
      //make json
      print_r(json_encode(
        array('message' => "Role has been changed successfully.")
      ));
    }else{
      print_r(json_encode(
        array('message' => "Something went wrong while changing role.")
      ));
      http_response_code(400);
    }
  }else{
    print_r(json_encode(
      array('message' => "Id is not given.")
    ));
    http_response_code(400);
  }

  

  

  