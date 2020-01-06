<?php 
  
  class Favourite{
    private $conn;
    public $book_id;
    public $idArray;
    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    //set book_id in favourite cookie
    public function set_favourite(){
      $favArray = array();
      //check if book_id is actually present in the table
      $query = "SELECT book_id from book WHERE book_id = $this->book_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row){
        //create cookie for a first time
        if(!isset($_COOKIE['favourite'])){
          array_push($favArray, $this->book_id);
          setcookie('favourite', json_encode($favArray), time()+30*24*60*60);
          return true;
        }
        //update cookie if already exists
        if(isset($_COOKIE['favourite'])){
          $favArray = json_decode($_COOKIE['favourite'], true);
          array_push($favArray, $this->book_id);
          //remove duplicates from array
          $favArray=array_unique($favArray);
          setcookie('favourite', json_encode($favArray), time()+30*24*60*60);
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

    //get all book_id in favourite cookie
    public function get_favourite(){
      $query = 'SELECT book_id, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id
                AND book_id IN ('.implode(',', $this->idArray).') ORDER BY rating DESC ';
      
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }

    //remove book_id from a favourite cookie array
    public function delete_favourite(){
      if(isset($_COOKIE['favourite'])){
        $favArray = json_decode($_COOKIE['favourite'], true);
        if(in_array($this->book_id, $favArray)){
          $index = array_search($this->book_id, $favArray);
          array_splice($favArray, $index,1);
          //remove duplicates from array
          $favArray=array_unique($favArray);
          setcookie('favourite', json_encode($favArray), time()+30*24*60*60);
          return true;
        }else{
          return false;
        }
      }
    }

    //destroy the cookie
    public function delete_all_favourite(){
      if(isset($_COOKIE['favourite'])){
        setcookie('favourite', json_encode(array()), time()-30*24*60*60);
        return true;
      }else{
        return false;
      }
    }

  }
?>