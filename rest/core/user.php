<?php
include_once('initialize.php');

function sendXMLError($error_body){
    echo "<Error>";
        echo "<XMLError>";
            echo"<message>$error_body</message>";
        echo "</XMLError>";
echo "</Error>";
}

function valid_user_infromation($username , $password , $email){
    if (empty($username) || empty($password) || empty($email)) {
        sendXMLError("invalid inputs");
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendXMLError("invalid inputs");
        return false;
    }
    if (preg_match('/[A-Za-z0-9]+/', $username) == 0) {
        sendXMLError("invalid inputs");
        return false;
    }
    if (strlen($password) > 20 || strlen($password) < 5) {
        sendXMLError("invalid inputs");
        return false;
    }
    return true;
}

function add_user($link){
    $input = file_get_contents("php://input");
    $xml = simplexml_load_string($input);
    $username = $xml->user[0]->username;
    $password = $xml->user[0]->password;;
    $email    = $xml->user[0]->email;
    if(valid_user_infromation($username , $password , $email)){
        if($stmt = mysqli_prepare($link,"SELECT * FROM users WHERE email =?")){
            mysqli_stmt_bind_param($stmt,'s',$email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $lines = mysqli_num_rows($result);
            if($lines == 0){
                $password = password_hash($password, PASSWORD_DEFAULT);
                if($stmt = mysqli_prepare($link,"INSERT INTO users (username, password,email) VALUES(?,?,?)")){
                   mysqli_stmt_bind_param($stmt ,'sss',$username , $password ,$email);
                   mysqli_stmt_execute($stmt);
                }
                else{
                    die('failed preparing the query: '.mysqli_error($link));
                }
            }
            else{
                sendXMLError("username is not avilable");
            }
        }
        else{
            die('failed preparing the query: '.mysqli_error($link));
        }
          
    }
}
function print_result($query, $root_element_name, $wrapper_element_name,$link){
    $result = mysqli_query($link ,$query) or die('Query failed: ' . mysqli_error($link));
    echo "<$root_element_name>";
    while($line = mysqli_fetch_array($result , 1)){
        echo "<$wrapper_element_name>";
        foreach($line as $key => $col_value){
            echo "<$key>$col_value</$key>";
        }
        echo "</$wrapper_element_name>";
    }
    echo "</$root_element_name>";
    mysqli_free_result($result);
}
header("Content-Type: text/xml");
$path = $_SERVER['PATH_INFO'];
if($path != null){
    $path_params = explode('/',$path);
}


if($_SERVER['REQUEST_METHOD'] =='GET'){
    get_advertisements($link);
}
else if($_SERVER['REQUEST_METHOD'] =='POST'){
    $con = init_dp();
    if ($path_params[1] != null && $path_params[2] != null && $path_params[3] != null) {
    }
    else{
        add_user($con);
    }
    //mysqli_close($database);
}





?>







