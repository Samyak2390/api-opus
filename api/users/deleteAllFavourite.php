<?php 
  session_start();
 
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: http://localhost:8081');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Authorization, Content-Type, Access-Control-Allow-Credentials');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  header("Access-Control-Allow-Credentials: true");
  die();
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: http://localhost:8081");
header('content-type: application/json; charset=utf-8');

 //check if user is logged in
 if(!isset($_SESSION['token'])){
  print_r(json_encode(
    array('message' => "You are not authorized.")
  ));
  http_response_code(401);
  exit();
}

  include_once '../../config/Database.php';
  include_once '../../models/Favourite.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $fav = new Favourite($db);
  if($fav->delete_all_favourite()){
    //make json
    print_r(json_encode(
      array('message' => "All Books have been removed from favourite.")
    ));
  }else{
    print_r(json_encode(
      array('message' => "Something went wrong while deleting.")
    ));
    http_response_code(400);
  }
  ?>

  

  

  