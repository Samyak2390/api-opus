<?php 
  include_once 'Validate_item.php';
  // define ('SITE_ROOT', realpath(dirname(__FILE__)));
  class Item{
    private $conn;
    private $tableBooks = 'book';
    private $tableAuthor = 'author';
    private $tableCategory = 'category';
    private $tablePublisher = 'publisher';
    private $tableImage = 'image';

    //post properties

    public $id;
    public $bookname;
    public $author;
    public $year;
    public $pages;
    public $pulbisher;
    public $price;
    public $rating;
    public $category;
    public $image;
    // public $imageFile;
    public $description;

    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    public function add_item(){
      try{
          //create query

          //for adding author
          $addAuthor = "INSERT INTO $this->tableAuthor SET author_name = :authorName";
  
          //for adding publisher
          $addPublisher = "INSERT INTO $this->tablePublisher SET publisher_name = :publisherName";

          //for adding Category
          $addCategory = "INSERT INTO $this->tableCategory SET category_name = :categoryName";

          //for adding Image
          $addImage = "INSERT INTO $this->tableImage SET image_name = :imageName";

          //prepare statement
          $stmtAuthor = $this->conn->prepare($addAuthor);
          $stmtPublisher = $this->conn->prepare($addPublisher);
          $stmtCategory = $this->conn->prepare($addCategory);
          $stmtImage = $this->conn->prepare($addImage);

          //clean data
          $this->bookname = trim(htmlspecialchars(strip_tags($this->bookname)));
          $this->author = trim(htmlspecialchars(strip_tags($this->author)));
          $this->pages = trim(htmlspecialchars(strip_tags($this->pages)));
          $this->pulbisher = trim(htmlspecialchars(strip_tags($this->pulbisher)));
          $this->price = trim(htmlspecialchars(strip_tags($this->price)));
          $this->year = trim(htmlspecialchars(strip_tags($this->year)));
          $this->rating = trim(htmlspecialchars(strip_tags($this->rating)));
          $this->category = trim(htmlspecialchars(strip_tags($this->category)));
          $this->image = trim(htmlspecialchars(strip_tags($this->image)));
          $this->description = trim(htmlspecialchars(strip_tags($this->description)));


          //check if empty
          if(empty($this->bookname) || empty($this->author)|| empty($this->pages)|| empty($this->publisher)
          || empty($this->price)|| empty($this->year)|| empty($this->rating)|| empty($this->category)
          || empty($this->description)){
            echo json_encode(
              array('message' => 'All fields are Required.')
            );
            http_response_code(400);
            return false;
          }

          if(empty($this->image) && !is_uploaded_file($_FILES['imageFile']["tmp_name"])){
            echo json_encode(
              array('message' => 'Atleast one image is required.')
            );
            http_response_code(400);
            return false;
          }

          if(!empty($this->image) && is_uploaded_file($_FILES['imageFile']["tmp_name"])){
            echo json_encode(
              array('message' => 'Only one image is required.')
            );
            http_response_code(400);
            return false;
          }

          $data = array("bookname" => $this->bookname,
                        "author" => $this->author,
                        "year" => $this->year,
                        "pages" => $this->pages,
                        "publisher" => $this->publisher,
                        "price" => $this->price,
                        "rating" => $this->rating,
                        "category" => $this->category,
                        "image" => $this->image,
                        "description" => $this->description);

          echo "printing data";
          print_r($data);
        
          $validation = new Validate_item($data);
          $errors = $validation->validateForm();

          if(sizeof($errors) > 0){
            echo json_encode(
              array('message' => $errors)
            );
            http_response_code(400);
            return false;
          }
          //handle image upload
          if(is_uploaded_file($_FILES['imageFile']["tmp_name"])){
            $filetmp = $_FILES['imageFile']["tmp_name"];
            $filename = $_FILES['imageFile']["name"];
            $filetype = $_FILES['imageFile']["type"];
            $filesize = $_FILES['imageFile']["size"];

            //have to change this
            $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/WAT/wat2019/api-opus/images/'.$filename;

            if($filetype != "image/jpeg" && $filetype != "image/png" && $filetype != "image/gif"){
              echo json_encode(
                array('message' => 'Invalid image file.')
              );
              http_response_code(400);
              return false;
            }

            if($filesize > 1000000){
              echo json_encode(
                array('message' => 'Image must be less than 1MB.')
              );
              http_response_code(400);
              return false;
            }else{
              $stmtImage->bindParam(':imageName', $filename);
              if(move_uploaded_file($filetmp, $uploadDir) && $stmtImage->execute()){
                $imageId = $this->conn->lastInsertId();
              }
            }
          }

          //if already upoaded image is chosen

          if(!empty($this->image) && empty($this->imageFile)){
            //get image id for that file name
            $imgQuery = "SELECT image_id FROM image where image_name = '$this->image'";
            $imgStmt = $this->conn->prepare($imgQuery);
            if($imgStmt->execute()){
              $row = $stmt->fetch(PDO::FETCH_ASSOC);

              if($row){
                $imageId = $row['image_id'];
              }
            }else{
              echo json_encode(
                array('message' => 'Something went wrong while getting image.')
              );
              http_response_code(400);
              return false;
            }
          }

          //bind data
          $stmtPublisher->bindParam(':publisherName', $this->publisher);
          $stmtCategory->bindParam(':categoryName', $this->category);
          $stmtAuthor->bindParam(':authorName', $this->author);

          //Execute queries
          $stmtAuthor->execute();
          $authorId = $this->conn->lastInsertId();

          $stmtPublisher->execute();
          $publisherId = $this->conn->lastInsertId();

          $stmtCategory->execute();
          $categoryId = $this->conn->lastInsertId();

          $addItem = "INSERT INTO book
                      SET bookname =:bookname, 
                          year = :year, 
                          pages = :pages, 
                          price = :price, 
                          rating = :rating, 
                          description = :description,
                          author_id = :author_id,
                          publisher_id=:publisher_id,
                          category_id = :category_id,
                          image_id = :image_id";

          $stmt = $this->conn->prepare($addItem);

          $stmt->bindParam(':bookname', $this->bookname);
          $stmt->bindParam(':year', $this->year);
          $stmt->bindParam(':pages', $this->pages);
          $stmt->bindParam(':price', $this->price);
          $stmt->bindParam(':rating', $this->rating);
          $stmt->bindParam(':description', $this->description);
          $stmt->bindParam(':author_id', $authorId);
          $stmt->bindParam(':publisher_id', $publisherId);
          $stmt->bindParam(':category_id', $categoryId);
          $stmt->bindParam(':image_id', $imageId);

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