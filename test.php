<?php 
  session_start();
  echo"<pre>";
  print_r($_SESSION['token']);
  if(isset($_SESSION['token'])){
    echo "has";
  }else{
    echo "dont";
  }
  echo"</pre>";
?>