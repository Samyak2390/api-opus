<?php 
  include_once 'Validate_user.php';
  class User{
    private $conn;
    private $table = 'user';

    //post properties

    public $id;
    public $username;
    public $email;
    public $password;
    public $age;
    public $created_at;
    public $status;
    public $role;
    public $error;

    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    public function create_user(){
      try{
           //create query
          $query = "INSERT INTO $this->table
                    SET
                      username = :username,
                      password = MD5(:password),
                      email = :email,
                      age = :age,
                      status = 1,
                      role = 1
                      ";

          //prepare statement
          $stmt = $this->conn->prepare($query);

          //clean data
          $this->username = trim(htmlspecialchars(strip_tags($this->username)));
          $this->password = trim(htmlspecialchars(strip_tags($this->password)));
          $this->email = trim(htmlspecialchars(strip_tags($this->email)));
          $this->age = trim(htmlspecialchars(strip_tags($this->age)));

          //check if empty
          if(empty($this->username) || empty($this->password)|| empty($this->email)|| empty($this->age)){
            echo json_encode(
              array('message' => 'All fields are Required.')
            );
            http_response_code(400);
            return false;
          }

          $data = array ("username"=>$this->username, 
                         "password"=>$this->password,
                         "email"=>$this->email,
                         "age"=>$this->age,);

          $validation = new Validate_user($data);
          $errors = $validation->validateForm();

          if(sizeof($errors) > 0){
            echo json_encode(
              array('message' => $errors)
            );
            http_response_code(400);
            return false;
          }

          //bind data
          $stmt->bindParam(':username', $this->username);
          $stmt->bindParam(':password', $this->password);
          $stmt->bindParam(':email', $this->email);
          $stmt->bindParam(':age', $this->age);

          //Execute query
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

    //Get single user
    public function get_user(){
      //create query
      $query = "SELECT id, username, email, password, age, role
      FROM $this->table u
      WHERE u.username = :username AND u.password = md5(:password)";

      //prepare statement
      $stmt = $this->conn->prepare($query);

      //clean data
      $this->username = htmlspecialchars(strip_tags($this->username));
      $this->password = htmlspecialchars(strip_tags($this->password));

      //check if empty
      if(empty($this->username) || empty($this->password)){
        echo json_encode(
          array('message' => 'Username or Password is empty.')
        );
        http_response_code(400);
        return false;
      }

      //Bind data
      $stmt->bindParam(':username', $this->username);
      $stmt->bindParam(':password', $this->password);

      //Execute query
      if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
          $this->id = $row['id'];
          $this->username = $row['username'];
          $this->email = $row['email'];
          $this->role = $row['role'];
          return true;
        }else{
          return false;
        }
      }

      //Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);
      return false;
   }
  }
?>