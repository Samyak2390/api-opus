<?php 
  
  class Dashboard{
    private $conn;

    //constructor with db
    public function __construct($db){
      $this->conn = $db;
    }

    public function get_dashboard_data(){
      //total no. of users
      $query1 = "SELECT count(*) users FROM users";
      
      //total no. of admins
      $query2 = "SELECT count(*) admins FROM users WHERE role='1'";

      //total no. of books
      $query3 = "SELECT count(*) items FROM book";

      $stmt1 = $this->conn->prepare($query1);
      $stmt1->execute();
      $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

      $stmt2 = $this->conn->prepare($query2);
      $stmt2->execute();
      $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

      $stmt3 = $this->conn->prepare($query3);
      $stmt3->execute();
      $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

      if(isset($row1['users']) && isset($row2['admins']) && isset($row3['items'])){

        $info = array(
          'users' => $row1['users'],
          'admins' => $row2['admins'],
          'items' => $row3['items']
        );

        echo json_encode(array('data'=>$info));
      }
      else{
        echo json_encode(array('message'=>'Something went Wrong!'));
        http_response_code(400);
      }
    }

  }
?>