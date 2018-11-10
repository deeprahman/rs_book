<?php

require_once "./session.php";
if (!isset($_SESSION['admin'])) {
    header("location:.");
    exit();
}

use classes\FileUplaod;
use classes\ImageResize;
//include the PHP file for FIleUpload class

include "classes/ImageResize.php";
include "classes/Fileupload.php";

//Database connection
require_once __DIR__ . "/d_connect.php";

// Get the information for the book to be update
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$summery = filter_input(INPUT_POST, 'summery', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$date_pub = filter_input(INPUT_POST, 'date_pub', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$avs_copy = (int) filter_input(INPUT_POST, 'avl_copy', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = (int) $_SESSION['id'];

$sql_update = <<<SQL
UPDATE book SET
title = :title,
author = :author ,
summery = :summery,
date_of_pub = :date_of_pub,
copy_avl = :copy_avl,
book_cover = :book_cover
WHERE id = :id
SQL;

//Select the book cover from book
$sql_select = <<<SQL
SELECT book_cover FROM book WHERE id={$id}
SQL;
try {
    $img_name = $db->query($sql_select);
    $img_name = $img_name->fetch();
    $img_name = $img_name['book_cover'];
} catch (PDOException $ex) {
    exit($ex);
}

$target = $img_name;
$destination = "./thumb";
//Instantiate the ImageResize class
$resize = new ImageResize($target, $destination);

if ($img_name !== "default.png") {
    //Instantiate the FileUpload class
    $file_uplaod = new FileUplaod($_FILES['image'], false);

    $target = $img_name;
    $destination = "./thumb";
//Instantiate the ImageResize class
    $resize = new ImageResize($target, $destination);
    //Check if the file is uploaded and the submit button is clicked
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        //Limit upload file size
        if (filesize($_FILES['image']['tmp_name']) > 4000000) {
            header("location:index.php");
            exit();
        }

        //Restrict MIME type
        $alowed_mime = ["image/jpeg", "image/png"];
        if (!in_array($file_uplaod->chkMime(), $alowed_mime)) {

            exit("mime type invalid!!!!!");
        }
        //Move file to specified directory
        $tar_dir = __DIR__ . "/files";
        $upload_status = $file_uplaod->moveFileUpdate($tar_dir, $img_name);
        if (!$upload_status) {
            // header("location:index.php");
            $_SESSION['msg'] = "Someting whent wrong when updatingg pic!!!";
        }
        //Image Resize
        if (!$resize->thumbNail()) {
            $_SESSION['msg'] = "Image cannot be resized";
            header("location:.");
            exit();
        }
    }
} else {

    $file_uplaod = new FileUplaod($_FILES['image'], true);
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        //Limit upload file size
        if (filesize($_FILES['image']['tmp_name']) > 4000000) {
            header("location:index.php");
            exit();
        }

        //Restrict MIME type
        $alowed_mime = ["image/jpeg", "image/png"];
        if (!in_array($file_uplaod->chkMime(), $alowed_mime)) {

            exit("mime type invalid!!!!!");
        }
        //Move file to specified directory
        $tar_dir = __DIR__ . "/files";
        $upload_status = $file_uplaod->moveFile($tar_dir);
        if (!$upload_status) {
            // header("location:index.php");
            $_SESSION['msg'] = "Someting whent wrong when uupdating pic!!!";
        }
        $img_name = $file_uplaod->getUnique();
        //Image Resize
        $target = $img_name;
        $destination = "./thumb";
        //Instantiate the ImageResize class
        $resize = new ImageResize($target, $destination);

        if (!$resize->thumbNail()) {
            $_SESSION['msg'] = "Image cannot be resized";
            header("location:.");
            exit();
        }
    }

}

try {
    $result = $db->prepare($sql_update);
    $params = [
        ':title' => $title,
        ':author' => $author,
        ':summery' => $summery,
        ':date_of_pub' => $date_pub,
        ':copy_avl' => $avs_copy,
        ':id' => $id,
        ':book_cover' => $img_name,
    ];
    $result->execute($params);
} catch (PDOException $ex) {
    exit($ex);
}
header("location:.");
