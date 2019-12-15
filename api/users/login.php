<?php 
  //Headersheader('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/User.php';

  //Instantiate db and connect

  $database = new Database();
  $db = $database->connect();

  //Instantiate  object
  $user = new User($db);

  //Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $user->username = $data->username;
  $user->password = $data->password;

  if($user->get_user()){
    //start sessioin
    session_start();
    $_SESSION['user']=$user->username;
    //create array
    $user_arr = array(
      'id' => $user->id,
      'username' => $user->username,
      'email' => $user->email,
      'role' => $user->role
    );
    //make json
    print_r(json_encode($user_arr));
    
  }else{
    echo json_encode(
      array('message' => 'Invalid Username or Password.')
    );
  }

  

  