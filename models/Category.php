<?php 
  
  class Category{
    private $conn;
    public $category;
    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    //Get books with maximum rating , order them from high to low
    public function get_category(){
      //create query
      $query = "SELECT category_name FROM category";

      //prepare and execute statement
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }

    public function get_category_data(){
      $this->category = strtolower(htmlspecialchars(strip_tags($this->category)));
      if($this->category === 'all'){
        $query = "SELECT book_id, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image, category
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.category_id = category.category_id
                ORDER BY rating DESC";

      }else{
        $query = "SELECT book_id, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image, category
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.category_id = category.category_id
                AND category_name = :category_name
                ORDER BY rating DESC";
      }
      
      $stmt = $this->conn->prepare($query);
      $this->category = htmlspecialchars(strip_tags($this->category));
      $stmt->bindParam(':category_name', $this->category);
      $stmt->execute();
      return $stmt;
    }

  }
?>