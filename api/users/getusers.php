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
  include_once '../../models/User.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $getAll= new User($db);

  //Getting items query
  $result = $getAll->get_all_users();
  //Get row count
  $num = $result->rowCount();
  //check if any items
  if($num > 0){
    //Items array
    $users_arr = array();
    $users_arr['data'] = array();

    while($row=$result->fetch(PDO::FETCH_ASSOC)){
      extract($row);
      $users = array(
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'role' => $role
      );
      array_push($users_arr['data'], $users);
    }
    //Turn to JSON & output
    echo json_encode($users_arr);
  }else{
    //No posts
    echo json_encode(
      array('message' => 'No Users Found')
    );
  }


  

  