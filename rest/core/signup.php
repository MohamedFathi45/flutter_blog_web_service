<?php
ini_set("display_errors", 1);


include_once("initialize.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

$dp = new Database();
$connection = $dp->connect();
$user_obj = new User($connection);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $data =json_decode(file_get_contents("php://input"));
    if( !empty($data->email) && !empty($data->password)  && !empty($data->username)){
        $user_obj->username = $data->username;
        $user_obj->password = password_hash($data->password , PASSWORD_DEFAULT);
        $user_obj->email = $data->email;
        $email_data = $user_obj->check_email();
        if(!empty($email_data)){
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                 "message" => "User already exists, try another email address"
            ));
        }
        else{
            if(!$user_obj->check_user_inputs()){
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Invalid inputs"
                ));
            }
            else if($user_obj->add_user()){
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "User has been created"
                ));
            }
            else{
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Failed to save user"
                ));
            }
        }
    }
    else{
        http_response_code(500);
        echo json_encode(array(
            "status" => 0,
            "message" => "All data needed"
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