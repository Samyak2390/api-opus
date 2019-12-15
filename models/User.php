<?php 
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

    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    public function create_user(){
      //create query
      $query = "INSERT INTO $this->table
                SET
                  username = :username,
                  password = :password,
                  email = :email,
                  age = :age,
                  status = 1,
                  role = 1
                  ";

      //prepare statement
      $stmt = $this->conn->prepare($query);

      //clean data
      $this->username = htmlspecialchars(strip_tags($this->username));
      $this->password = htmlspecialchars(strip_tags($this->password));
      $this->email = htmlspecialchars(strip_tags($this->email));
      $this->age = htmlspecialchars(strip_tags($this->age));

      //bind data
      $stmt->bindParam(':username', $this->username);
      $stmt->bindParam(':password', $this->password);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':age', $this->age);

      //Execute query
      if($stmt->execute()){
        return true;
      }

      //Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);
      return false;
    }

    //Get single user
    public function get_user(){
      //create query
      $query = "SELECT id, username, email, password, age, role
      FROM $this->table u
      WHERE u.username = :username AND u.password = :password";

      //prepare statement
      $stmt = $this->conn->prepare($query);

      //clean data
      $this->username = htmlspecialchars(strip_tags($this->username));
      $this->password = htmlspecialchars(strip_tags($this->password));

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