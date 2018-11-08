<?php
use classes\Register;
require_once __DIR__."/classes/Register.php";

$reg = new Register;
if(!isset($_SESSION['admin'])||!isset($_POST['user_id'])){
    header("location:.");
    exit();
  }

//Take input
foreach($_POST as $key => $value){
    $input[$key]=filter_input(INPUT_POST,$key,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}



$array =$reg->inputEmty($input);

if($array){
    var_dump($array);
    exit();
}

$email = $reg->validEmail($input['email']);
if(!$email){
    var_dump($email);
    exit();
}