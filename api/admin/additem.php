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
  include_once '../../models/Item.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $item = new Item($db);

  //Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $item->bookname = $data->bookname;
  $item->author = $data->author;
  $item->year = $data->year;
  $item->pages = $data->pages;
  $item->publisher = $data->publisher;
  $item->price = $data->price;
  $item->rating = $data->rating;
  $item->category = $data->category;
  $item->image = $data->image;
  $item->imageFile = $data->imageFile;
  $item->description = $data->description;
  

  if($item->add_item()){
    echo json_encode(
      array('message' => 'User registered Successfully.')
    );
  }

  

  