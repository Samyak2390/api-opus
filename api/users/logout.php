<?php
  header("Access-Control-Allow-Origin: *");
  header('content-type: application/json; charset=utf-8');
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  session_start();
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