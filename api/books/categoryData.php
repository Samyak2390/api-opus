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
  $cat = new Category($db);

  //Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $cat->category = $data->category;
  //Getting items query
  $result = $cat->get_category_data();
  //Get row count
  $num = $result->rowCount();
  //check if any items
  if($num > 0){
    //Items array
    $items_arr = array();
    $items_arr['data'] = array();

    while($row=$result->fetch(PDO::FETCH_ASSOC)){
      extract($row);
      $book_items = array(
        'book_id' => $book_id,
        'bookname' => $bookname,
        'price' => $price,
        'rating' => $rating,
        'description' => $description,
        'author_name' => $author_name,
        'image_url' => 'http://localhost/WAT/wat2019/api-opus/images/'.$image_name
      );

      array_push($items_arr['data'], $book_items);
    }
    //Turn to JSON & output
    echo json_encode($items_arr);
  }else{
    //No data
    echo json_encode(
      array('message' => 'No Data Found')
    );
  }

  

  