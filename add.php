<?php
//Include the database connection
if(!isset($_SESSION['admin'])){
  header("location:.");
  exit();
}
use classes\FileUplaod;
require_once "d_connect.php";

//include the PHP file for FIleUpload class
include "classes/Fileupload.php";
//Instantiate the FileUpload class

$file_uplaod = new FileUplaod($_SESSION['file'],TRUE);

echo "This is the add book page<br>";


// Get the information for the book to be added
$title = filter_input(INPUT_POST,'title',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$author = filter_input(INPUT_POST,'author',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$summery = filter_input(INPUT_POST,'summery',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$date_pub = filter_input(INPUT_POST,'date_pub',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$avs_copy = (int)filter_input(INPUT_POST,'avl_copy',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$user_id = $_SESSION["admin"];
if($_SESSION['file']['error'] == UPLOAD_ERR_OK){
  $img_new_name = $file_uplaod->getUnique();
}else{
  $img_new_name = "default.png";
}


//find the user name
$sql_select = "SELECT user_name FROM admin WHERE id='$user_id'";

$sql_insert = "INSERT INTO book (title,author,summery,date_of_pub,copy_avl,book_cover,user_name) VALUES (:title,:author,:summery,:date_of_pub,:copy_avl,:book_cover,:user_name)";
  try{
    //get the user name
    $user_name = $db->query($sql_select);
    $user_name = $user_name->fetch();
    $user_name = $user_name['user_name'];

    //Enter Book info in the database
      $prep = $db->prepare($sql_insert);
      $params = [
          ':title' => $title,
          ':author' => $author,
          ':summery' => $summery,
          ':date_of_pub' => $date_pub,
          ':copy_avl' => $avs_copy,
          ':book_cover' => $img_new_name,
          ':user_name' => $user_name
      ];
      $prep->execute($params);
      
  }catch(PDOException $ex){
      $_SESSION['msg']=$ex->getMessage();
      exit();
  }

// File upload section


  //Check if the file is uploaded and the submit button is clicked
  if ($_SESSION['file']['error'] == UPLOAD_ERR_OK) {
    //Limit upload file size
    if (filesize($_SESSION['file']['tmp_name']) > 4000000) {
      header("location:index.php");
      exit();
    }
    
    //Restrict MIME type
    $alowed_mime = ["image/jpeg","image/png"];
    if (!in_array($file_uplaod->chkMime(),$alowed_mime)){
      
      exit("mime type invalid!!!!!S");
    }
    //Move file to specified directory
    $tar_dir = __DIR__."/files";
    $upload_status=$file_uplaod->moveFile($tar_dir);
    if (!$upload_status){
      // header("location:index.php");
      $_SESSION['msg']="Someting whent wrong when uploading pic!!!"; 
    }
  }

  

header('location:.');