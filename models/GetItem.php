<?php 
  
  class GetItem{
    private $conn;
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
                AND book.bestseller = '1'
                ORDER BY rating DESC";

      //prepare and execute statement
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }

    //Get New Releases
    public function get_new_releases(){
      //create query
      $query = "SELECT book_id, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.year = :year
                ORDER BY rating DESC";
      //current Year
      $currentYear = date("Y");

      //prepare and execute statement
      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':year', $currentYear);
      $stmt->execute();
      return $stmt;
    }

    public function get_all_items(){
      //create query
      $query = "SELECT book_id, bookname,year, pages, price, rating, bestseller, description, author_name, publisher_name, category_name, image_name 
                FROM book, author, image, publisher, category
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.publisher_id = publisher.publisher_id
                AND book.category_id = category.category_id
                ORDER BY book_id DESC";

      //prepare and execute statement
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }

    public function get_all_images(){
      //create query
      $query = "SELECT image_name FROM image";

      //prepare and execute statement
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }



  }
?>