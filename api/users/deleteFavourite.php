<?php 
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Authorization, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  die();
}

header("Access-Control-Allow-Origin: *");
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
    if($fav->delete_favourite()){
      //make json
      print_r(json_encode(
        array('message' => "Book has been removed from favourite.")
      ));
    }else{
      print_r(json_encode(
        array('message' => "Book Id is invalid.")
      ));
      http_response_code(400);
    }
  }else{
    print_r(json_encode(
      array('message' => "Book Id is not given.")
    ));
    http_response_code(400);
  }

  

  

  