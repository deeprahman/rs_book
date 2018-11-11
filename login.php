<?php
require_once "session.php";
require_once __DIR__."/d_connect.php";
// include_once __DIR__."/input.php";


$user_id =(string) filter_input(INPUT_POST,'userID',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password =(string) filter_input(INPUT_POST,'password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//get data from the database
$sql_select ="SELECT * FROM admin WHERE user_name= '$user_id'";


try{
    $result = $db->query($sql_select);
    $result = $result->fetch();
    var_dump($result);
}catch(PDOException $ex){
    exit($ex);
}

if($user_id !==$result['user_name']||$password !== $result['password']){
    $_SESSION["msg"]="User Name and Password does not match!";
    header("location:.");
    exit();
}

$_SESSION['admin'] = $result['id'];


header("location:.");
