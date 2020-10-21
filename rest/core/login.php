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

                $iss = "localhost";
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 30;       // expired after 30
                $aud = "myusers";
                $user_arr_data = array(
                    "id" => $user_data['id'],
                    "username" => $user_data['username'],
                    "email" => $user_data['email']
                );
                $secret_key = "secretkey";

                $payload_info = array(
                    "iss" => $iss,          //issure
                    "iat" => $iat,          //issued at
                    "nbf" => $nbf,          //not before
                    "exp" => $exp,          //expired at
                    "aud" => $aud,          //audiance
                    "data" => $user_arr_data
                );

                $jwt = JWT::encode($payload_info , $secret_key);
                
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "jwt" => $jwt,
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
    http_response_codes(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access Denied"
  ));
}

?>