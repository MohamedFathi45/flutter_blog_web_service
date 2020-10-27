<?php

ini_set("display_errors", 1);
require '../../vendor/autoload.php';


use \Firebase\JWT\JWT;

include_once("initialize.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

$dp = new Database();
$connection = $dp->connect();
$blog_obj = new Blog($connection);

$path = $_SERVER['PATH_INFO'];
if ($path != null) {
    $path_params = explode ("/", $path);
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents('php://input'));
    $headers = getallheaders();
    if( !empty($data->blog_title) && !empty($data->blog_body)){
        try{
            $jwt = $headers["Authorization"];
            $secret_key = "secretkey";
            $decoded_data = JWT::decode($jwt, $secret_key, array('HS256'));
            $blog_obj->user_id = $decoded_data->data->id;
            $blog_obj->blog_title = $data->blog_title;
            $blog_obj->blog_body = $data->blog_body; 

            if($blog_obj->create_blog()){
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "blog has been created"
                ));
            }
            else{
                http_response_code(500);        //server internal error
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "Failed to save blog"
                    ));
            }

        }catch(Exception $ex){
            http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => $ex->getMessage()
                ));
        } 
          
    }
    else{
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "All data needed"
      ));
    }
}else if($_SERVER['REQUEST_METHOD'] == 'GET'){
    /*
    if($path_params[1] != null){
        settype($path_params[1] , 'integer');
        $query = "SELECT * FROM blogs WHERE id = $path_params[1]";    
    }
    else{
        $query = "SELECT b.id , b.name , b.author , b.isbn FROM book AS b";
    }
    $result = mysqli_query($link,$query) or die("mysql query filed" . mysqli_error());
    echo "<books>";
    while($line = mysqli_fetch_array($result , 1)){
        echo "<book>";
        foreach($line as $key => $col_value){
            echo "<$key> $col_value </$key>";
        }
        echo "</book>";
    }
    echo "</books>";
    mysqli_free_result($result);
    */
}


?>