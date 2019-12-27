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
  include_once '../../models/GetItem.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $getRatedItems = new GetItem($db);

  //Getting items query
  $result = $getRatedItems->get_max_rated_books();
  //Get row count
  $num = $result->rowCount();
  //check if any posts
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
        'image_url' => $_SERVER['DOCUMENT_ROOT'].'/WAT/wat2019/api-opus/images/'.$image_name
      );

      // Push to "data"
      array_push($items_arr['data'], $book_items);
    }
    //Turn to JSON & output
    echo json_encode($items_arr);
  }else{
    //No posts
    echo json_encode(
      array('message' => 'No Posts Found')
    );
  }


  

  