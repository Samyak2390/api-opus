<?php 
  
  class Delete{
    private $conn;
    public $book_id;

    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    //Delete item for a given id

    public function delete_item(){
      try{
        $query = "DELETE FROM book WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $this->book_id);
        if($stmt->execute()){
          return true;
        }
      }catch(PDOException $e){
        $this->error = 'Error: ' . $e -> getMessage();
        echo json_encode(
          array('message' => $this->error)
        );
        return false;
      }
    }



  }
?>