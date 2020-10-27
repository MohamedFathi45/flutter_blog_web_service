<?php

class User{
    public $email;
    public $username;
    public $password;
    private $users_tbl;

    public function __construct($conn){
        $this->conn = $conn;
        $this->users_tbl = "users";
    }

    function add_user(){
        $user_query = "INSERT INTO " . $this->users_tbl ." (username, password,email) VALUES(?,?,?)";
        $user_obj = $this->conn->prepare($user_query);
        $user_obj->bind_param("sss", $this->username, $this->password,$this->email);

        if($user_obj->execute()){
            return true;
        }
        return false;
    }

    public function check_email(){

        $query = "SELECT * from ".$this->users_tbl." WHERE email = ?";
    
        $usr_obj = $this->conn->prepare($query);
    
        $usr_obj->bind_param("s", $this->email);
    
        if($usr_obj->execute()){
    
           $data = $usr_obj->get_result();
    
           return $data->fetch_assoc();
        }
    
        return array();
      }

      public function check_login(){
        $email_query = "SELECT * from ".$this->users_tbl." WHERE email = ?";
        $usr_obj = $this->conn->prepare($email_query);
        $usr_obj->bind_param("s", $this->email);
        if($usr_obj->execute()){
           $data = $usr_obj->get_result();
           return $data->fetch_assoc();
        }
        return array();
      }

      function check_user_inputs(){
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if (preg_match('/[A-Za-z0-9]+/', $this->username) == 0) {
            return false;
        }
        return true;
    }
}


?>