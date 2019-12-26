<?php 
  // include_once '../../config/Database.php';
  class Validate_user{
    private $data;
    private $errors = [];
    private $db;

    public function __construct($data){
      $this->data = $data;
      $database = new Database();
      $this->db = $database->connect();
    }

    public function validateForm(){
      $this->validateUsername();
      $this->validateEmail();
      $this->validatePassword();
      return $this->errors;
    }

    private function validateUsername(){
      $val = $this->data['username'];
      $query = "SELECT * FROM user WHERE username='$val'";

      $stmt = $this->db->prepare($query);
      $stmt->execute();
      $num = $stmt->rowCount();

      if($num > 0){
        array_push($this->errors, "Username is already taken");
      }

    }

    private function validateEmail(){
      $val = $this->data['email'];
      $query = "SELECT * FROM user WHERE email='$val'";

      $stmt = $this->db->prepare($query);
      $stmt->execute();
      $num = $stmt->rowCount();

      if($num > 0){
        array_push($this->errors, "Email is already taken.");
      }

      if(!filter_var($val, FILTER_VALIDATE_EMAIL)){
        array_push($this->errors, "Email is invalid.");
      }
    }

    private function validatePassword(){
      $passError = "Password must have atleast one capital letter, a number and a symbol.";
      $val = $this->data['password'];
      $checkUpper = preg_match('@[A-Z]@', $val);
      $checkNum = preg_match('@[0-9]@', $val);
      $checkSpecialChars = preg_match('@[^\w]@', $val);

      if(!$checkUpper || !$checkNum || !$checkSpecialChars){
        array_push($this->errors, $passError);
      }
    }
  
  }
?>