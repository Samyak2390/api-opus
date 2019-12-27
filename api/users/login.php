<?php 
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: http://localhost:8081');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Authorization, Content-Type, withCredentials');
  header('Access-Control-Max-Age: 1728000');
  header('Access-Control-Allow-Credentials: true');
  header('Content-Length: 0');
  die();
}
header('Access-Control-Allow-Origin: http://localhost:8081');
header('content-type: application/json; charset=utf-8');

  include_once '../../config/Database.php';
  include_once '../../models/User.php';
  include_once '../../utils/Utils.php';

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
    //generate random number of 20 digits
    $util = new Utils();
    $token = $util->generateToken(20);
    //start sessioin
    session_start();
    $_SESSION['token']=$token;
    $_SESSION['role']=$user->role;
    //create array
    $user_arr = array(
      'token' => $token,
      'id' => $user->id,
      'username' => $user->username,
      'email' => $user->email,
      'role' => $user->role
    );
    //make json
    print_r(json_encode($user_arr));
    
  }

  

  