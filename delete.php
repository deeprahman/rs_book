<?php
if (!isset($_SESSION['admin'])) {
    header("location:.");
    exit();
}

//Select id specific user name from admin table
$sql_select = "SELECT user_name FROM admin WHERE id='{$_SESSION['admin']}'";
//get the user name
try {
    $user_name = $db->query($sql_select);
    $user_name = $user_name->fetch();
    $user_name_book = $user_name['user_name'];
    if (!isset($user_name_book)) {
        $_SESSION['msg'] = "User not found!";
        header("location:.");
        exit();
    }
} catch (PDOException $ex) {
    exit($ex);
}

$sql_delete = "DELETE FROM book WHERE (id = {$_GET['id']} AND user_name='{$user_name_book}')";
$sql_select_cover = <<<SQL
SELECT book_cover FROM book WHERE id = {$_GET['id']}
SQL;

try {
    //GET the name of the cover pic to be deleted
    $cover_pic = $db->query($sql_select_cover);
    $cover_pic = $cover_pic->fetch();
    if ($cover_pic['book_cover'] !== 'default.png') {

        $delete_path = "files/" . $cover_pic['book_cover'];
        $delete_path_tmb = "thumb/".$cover_pic['book_cover'];
        unlink($delete_path);
        unlink($delete_path_tmb);
    }
    //Delete from database
    $result = $db->exec($sql_delete);
    if ($result === 0) {
        $_SESSION['msg'] = "Book Was not deleted";
        header("location:.");
        exit();
    }
} catch (PDOException $ex) {
    exit($ex);
}

header("location:.");
