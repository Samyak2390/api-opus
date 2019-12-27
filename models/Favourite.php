<?php 
  
  class Favourite{
    private $conn;
    public $book_id;
    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    public function set_favourite(){
      $favArray = array();
      //check if book_id is actually present in the table
      $query = "SELECT book_id from book WHERE book_id = $this->book_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row){
        if(!isset($_COOKIE['favourite'])){
          array_push($favArray, $this->book_id);
          setcookie('favourite', json_encode($favArray), time()+30*24*60*60);
          return true;
        }
        if(isset($_COOKIE['favourite'])){
          $favArray = json_decode($_COOKIE['favourite'], true);
          array_push($favArray, $this->book_id);
          //remove duplicates from array
          $favArray=array_unique($favArray);
          setcookie('favourite', json_encode($favArray), time()+30*24*60*60);
          print_r($favArray);
          return true;
        }
      }else{
        print_r(json_encode(
          array('message' => "Book Id is not valid.")
        ));
        http_response_code(400);

      }
      return false;
    }

  }
?>