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
  $getAll= new GetItem($db);

  //Getting items query
  $result = $getAll->get_all_items();
  //Get row count
  $num = $result->rowCount();
  //check if any items
  if($num > 0){
    //Items array
    $items_arr = array();
    $items_arr['data'] = array();

    //book_id, bookname,year, pages, price, rating, bestseller, description, author_name, publisher_name, category_name, image_name 
    while($row=$result->fetch(PDO::FETCH_ASSOC)){
      extract($row);
      $book_items = array(
        'book_id' => $book_id,
        'bookname' => $bookname,
        'year' => $year,
        'pages' => $pages,
        'price' => $price,
        'rating' => $rating,
        'bestseller' => $bestseller,
        'description' => $description,
        'author_name' => $author_name,
        'publisher_name' => $publisher_name,
        'category_name' => $category_name,
        'image_url' => 'http://localhost/WAT/wat2019/api-opus/images/'.$image_name
      );
      array_push($items_arr['data'], $book_items);
    }
    //Turn to JSON & output
    echo json_encode($items_arr);
  }else{
    //No posts
    echo json_encode(
      array('message' => 'No Items Found')
    );
  }


  

  