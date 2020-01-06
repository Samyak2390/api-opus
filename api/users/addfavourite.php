<?php 
  session_start();
  //check if user is logged in
  if(!isset($_SESSION['token'])){
    print_r(json_encode(
      array('message' => "You are not authorized.")
    ));
    http_response_code(401);
    exit();
  }
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
  include_once '../../models/Favourite.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $fav = new Favourite($db);

  //Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if(!empty($data->book_id)){
    $fav->book_id = $data->book_id;
    if($fav->set_favourite()){
      //make json
      print_r(json_encode(
        array('message' => "Book added to favourite.")
      ));
    }
  }else{
    print_r(json_encode(
      array('message' => "Book Id is not given.")
    ));
    http_response_code(400);
  }

  

  

  