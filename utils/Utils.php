<?php 
  class Utils{
    private $token;
    public function generateToken($len){
      $i = 0;
      while($i < $len){
        $this->token .= rand(0,9);
        $i+=1;
      }
      return $this->token;
    }

  }
?>