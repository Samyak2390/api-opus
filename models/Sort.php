<?php 
  
  class Sort{
    private $conn;
    public $category;
    public $sortby;
    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    // sort data for a certain category
    public function sort_data(){
      $this->category = strtolower(htmlspecialchars(strip_tags($this->category)));
      $this->sortby = strtolower(htmlspecialchars(strip_tags($this->sortby)));

      if($this->category === 'all'){
        $query = "SELECT book_id, year, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image, category
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.category_id = category.category_id ";
      }else{
        $query = "SELECT book_id, year, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image, category
                WHERE book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.category_id = category.category_id
                AND category_name = :category_name ";
      }
      switch($this->sortby){
        case 'high rating to low':
          $query .= 'ORDER BY rating DESC';
        break;

        case 'low rating to high':
          $query .= 'ORDER BY rating';
        break;

        case 'high price to low':
          $query .= 'ORDER BY price DESC';
        break;

        case 'low price to high':
          $query .= 'ORDER BY price';
        break;

        case 'newest to oldest':
          $query .= 'ORDER BY year DESC';
        break;

        case 'oldest to newest':
          $query .= 'ORDER BY year';
        break;
        
        default:
          $query .= 'ORDER BY rating DESC';
      }

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':category_name', $this->category);
      $stmt->execute();
      return $stmt;
    }

  }
?>