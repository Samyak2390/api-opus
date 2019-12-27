<?php 
  
  class GetItem{
    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    //Get books with maximum rating , order them from high to low
    public function get_max_rated_books(){
      //create query
      $query = "SELECT book_id, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                ORDER BY rating DESC";

      //prepare and execute statement
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
   }

   //Get bestseller books
   public function get_bestsellers(){
    //create query
    $query = "SELECT book_id, bookname, price, rating, description, author_name, image_name 
              FROM book, author, image
              WHERE book.author_id = author.author_id 
              AND book.image_id = image.image_id 
              ORDER BY rating DESC";

    //prepare and execute statement
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
 }


  }
?>