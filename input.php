<?php
if(!isset($_SESSION['admin'])){
    header("location:.");
    exit();
  }

$user_id =(string) filter_input(INPUT_POST,'userID',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password =(string) filter_input(INPUT_POST,'password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

