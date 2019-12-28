<?php 
  
  class Category{
    private $conn;
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

  }
?>