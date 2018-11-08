<?php
$dsn = "mysql:host=localhost;dbname=rs_book";
try{
    $db = new PDO($dsn,'root','');
    $db-> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $ex){
    exit($ex);
}

