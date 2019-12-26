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
      $this->validateYear();
      $this->validatePages();
      $this->validatePrice();
      $this->validateRating();
      return $this->errors;
    }

    private function validateYear(){
      $val = $this->data['year'];
      $currentYear = date("Y");
      if(!is_numeric($val)){
        array_push($this->errors, "Year must be a number.");
      }

      if(is_numeric($val) && $val > $currentYear){
        array_push($this->errors, "Year is invalid.");
      }
    }
    private function validatePages(){
      $val = $this->data['pages'];
      if(!is_numeric($val)){
        array_push($this->errors, "Page must be a number.");
      }
    }

    private function validatePrice(){
      $val = $this->data['price'];
      if(!is_numeric($val)){
        array_push($this->errors, "Price must be a number.");
      }
    }

    private function validateRating(){
      $val = $this->data['rating'];
      if(!is_numeric($val)){
        array_push($this->errors, "Rating must be a number.");
      }
      if(is_numeric($val) && $val > 5){
        array_push($this->errors, "Maximum rating is 5.");
      }
      if(is_numeric($val) && $val < 0){
        array_push($this->errors, "Minimum rating is 5.");
      }
    }
  
  }
?>