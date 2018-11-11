<?php

require_once "session.php";
// session_start();
include __DIR__."/d_connect.php";

//Store info from file sg.
if(isset($_FILES['image'])){
    $_SESSION['file']=$_FILES['image'];
}
if(!isset($_SESSION['admin'])){
    include __DIR__."/view/login.html.php";
    exit();
}
if(isset($_GET['del']) && $_GET['del']==='yes' && isset($_SESSION['admin'])){
    include_once __DIR__."/delete.php";
    exit();
}


if(!empty($_POST["add_book"]) && isset($_SESSION['admin'])){

    include_once __DIR__."/add.php";
    exit();
}
if(!empty($_POST["update_book"]) && isset($_SESSION['admin'])){
    include_once __DIR__."/update.php";
    exit();
}
//Go to add view book page
if(isset($_GET['add']) && $_GET['add']==='yes' && isset($_SESSION['admin'])){
    $path = "view/add_book.html.php";
    header("location:{$path}");
    exit();
}


// include_once __DIR__."/view/books.html.php";
$path ="view/books.html.php";
header("location:$path");
exit();






