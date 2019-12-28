<?php 
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Authorization, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  die();
}

header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $getCategories = new Category($db);

  //Getting items query
  $result = $getCategories->get_category();
  //Get row count
  $num = $result->rowCount();
  //check if any items
  if($num > 0){
    //Items array
    $items_arr = array();
    $items_arr['data'] = array();
    $item_count = 0;

    while($row=$result->fetch(PDO::FETCH_ASSOC)){
      extract($row);
      array_push($items_arr['data'], $category_name);
    }
    //Turn to JSON & output
    echo json_encode($items_arr);
  }else{
    //No posts
    echo json_encode(
      array('message' => 'No Posts Found')
    );
  }


  

  