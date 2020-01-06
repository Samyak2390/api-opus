<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Authorization, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  die();
}

header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');
// get authorization token and match it with token in session, if matches then destroy session
  foreach(getallheaders() as $name => $value){
    if(strtolower($name) == 'authorization'){
      if(isset($_SESSION['token'])){
        if($value == $_SESSION['token']){
          session_destroy();
          echo(json_encode(array('message'=>'Successfully Logged Out.')));
        }else{
          http_response_code(401);
          echo(json_encode(array('message'=>'Invalid token.')));
        }
      }else{
        http_response_code(401);
        echo(json_encode(array('message'=>'Your session has expired.')));
      }
      
    }
  }
?>