<?php 
  class Search{
    private $conn;
    public $category;
    public $searchText;
    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    public function search_data(){
      $query = "SELECT book_id, year, bookname, price, rating, description, author_name, image_name 
                FROM book, author, image, category
                WHERE (book.author_id = author.author_id 
                AND book.image_id = image.image_id 
                AND book.category_id = category.category_id";

      if(empty($this->category) && empty($this->searchText)){
        echo json_encode(array(
          'message' => "Atleast choose all or choose a category!"
        ));
        http_response_code(400);
        return;
      }

      if(empty($this->category) && !empty($this->searchText)){
        echo json_encode(array(
          'message' => "Atleast choose a category!"
        ));
        http_response_code(400);
        return;
      }
      
      $this->category = strtolower(htmlspecialchars(strip_tags($this->category)));
      $this->searchText = strtolower(htmlspecialchars(strip_tags($this->searchText)));

      //Getting all the categories
      $getCat = "SELECT category_name FROM category";
      $stmtCat = $this->conn->prepare($getCat);
      $stmtCat->execute();
      $num = $stmtCat->rowCount();

      if($num > 0){
        while($row=$stmtCat->fetch(PDO::FETCH_ASSOC)){
          extract($row);
          // array_push($items_arr['data'], $category_name);
          if($this->category == $category_name){
            if(!empty($this->searchText)){
              $query .= " AND category_name = '$category_name') AND 
                      (LOWER(author_name) LIKE '%$this->searchText%' OR LOWER(bookname) LIKE '%$this->searchText%')";
              break;
            }else{
              $query .= " AND category_name = '$category_name')";
              break;
            }
          }
        }

        if(!empty($this->category) && $this->category != $category_name){
          if(!empty($this->searchText)){
            $query .= ") AND (LOWER(author_name) LIKE '%$this->searchText%' OR LOWER(bookname) LIKE '%$this->searchText%')";
          }else{
            $query .= ') ORDER BY rating DESC';
          }
        }
      }

      // echo $query;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
    }

  }
?>