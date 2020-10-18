<?php
ini_set("display_errors", 1);


include_once("initialize.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

$dp = new Database();
$connection = $dp->connect();
$user_obj = new User($connection);

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $data =json_decode(file_get_contents("php://input"));
    if(!empty($data->email) && !empty($data->password)){
        $user_obj->email = $data->email;
        $user_data = $user_obj->check_login();
        if(!empty($user_data)){
            $username = $user_data['username'];
            $password = $user_data['password'];
            $email = $user_data['email'];
            echo $username ." ".$password;
            if(password_verify($data->password ,$password)){
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "User Logged in Successfully"
                ));
            }
            else{
                http_response_code(404);
                echo json_encode(array(
                    "status" =>0,
                    "message" =>"Invalid credentials"
                ));
            }
        }
        else{
            http_response_code(404);
            echo json_encode(array(
                "status" =>0,
                "message" =>"Invalid credentials"
            ));
        }
    }else{
        http_response_code(404);
            echo json_encode(array(
                "status" =>0,
                "message" =>"Invalid credentials"
            ));
    }
}
else{
    http_response_code(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access Denied"
  ));
}

?>