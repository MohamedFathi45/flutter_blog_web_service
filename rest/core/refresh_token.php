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

if($_SERVER['REQUEST_METHOD'] == "GET"){
    $secret_key = "secretkey";
    $data = json_decode(file_get_contents('php://input'));
    $headers = getallheaders();
        try{
            $jwt = $headers["Authorization"];
            $decoded_data = JWT::decode($jwt, $secret_key, array('HS256'));
            $refresh_token=$decoded_data->data->refresh_token;
        }catch(Exception $ex){
            if($ex->getMessage() == "Expired token"){
                list($header, $payload, $signature) = explode(".", $jwt);
                $payload = json_decode(base64_decode($payload));
                $refresh_token = $payload->data->refresh_token;
                
                $iss = "localhost";
                $iat = time();
                $nbf = $iat;
                $exp = $iat + 180;       // expired after 180
                $aud = "myusers";
                $user_arr_data = array(
                    "id" => $payload->data->id,
                    "username" =>$payload->data->username,
                    "email" => $payload->data->email,
                    "refresh_token" => $refresh_token
                );

                $payload_info = array(
                    "iss" => $iss,          //issure
                    "iat" => $iat,          //issued at
                    "nbf" => $nbf,          //not before
                    "exp" => $exp,          //expired at
                    "aud" => $aud,          //audiance
                    "data" => $user_arr_data
                );
                $jwt = JWT::encode($payload_info , $secret_key , 'HS256');
                
                http_response_code(200);
                echo json_encode(array(
                    "status" =>1,
                    "jwt" => $jwt,
                    "message" => "refresh token sent"
                ));
                die();
            }
            else {
                http_response_code(401);
                echo json_encode(array(
                    "status" =>0,
                    "message" => "Access denied.",
                ));
            }
        }
        echo json_encode(array(
            "status" =>1,
            "message" => "token is valid"
        ));
}else{
    http_response_code(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Access Denied"
    ));
}

?>