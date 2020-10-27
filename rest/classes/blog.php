<?php

class Blog{
    public $user_id;
    public $blog_title;
    public $blog_body;

    private $conn;
    private $blogs_tbl;

    public function __construct($conn){
        $this->conn = $conn;
        $this->blogs_tbl = "blogs";
    }


    function create_blog(){
        $blog_query = "INSERT INTO $this->blogs_tbl(user_id , title , body , date ) VALUES(? ,? ,?,NOW())";
        $blog_query_object = $this->conn->prepare($blog_query);
        $blog_query_object->bind_param("iss" ,$this->user_id , $this->blog_title , $this->blog_body);
        if($blog_query_object->execute()){
            return true;
        }
        return false;
    }
    
    public function get_all_blogs(){
        $query = "SELECT * FROM $this->blogs_tbl ORDER BY id DESC";
        $query_obj = $this->conn->prepare($query);
        $query_obj->execute();
        return $query_obj->get_result();
    }

}



?>