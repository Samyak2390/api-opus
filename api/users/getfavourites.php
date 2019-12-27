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
  include_once '../../models/Favourite.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $fav = new Favourite($db);

  if(isset($_COOKIE['favourite'])){
    $favArray = json_decode($_COOKIE['favourite'], true);
    $fav->idArray = $favArray;

    if(sizeof($favArray) > 0){
      $result = $fav->get_favourite();
      //Get row count
      $num = $result->rowCount();
      //check if any favs
      if($num > 0){
        //Post array
        $favs_arr = array();
        $favs_arr['data'] = array();

        while($row=$result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $favs_item = array(
            'book_id' => $book_id,
            'bookname' => $bookname,
            'price' => $price,
            'rating' => $rating,
            'description' => $description,
            'author_name' => $author_name,
            'image_url' => 'http://localhost/WAT/wat2019/api-opus/images/'.$image_name
          );

          // Push to "data"
          array_push($favs_arr['data'], $favs_item);
        }
        //Turn to JSON & output
        echo json_encode($favs_arr);
      }
    
    }else{
      //No favs
      echo json_encode(
        array('message' => 'You have no Favourites.')
      );
    }
  }

  

  

  