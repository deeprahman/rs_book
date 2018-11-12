<?php
require_once "session.php";

//Database connection
require_once "d_connect.php";

// Execute only if logged in
if (!isset($_SESSION['admin'])) {
    header("location:.");
    exit();
}

// Get the user id and image name
$user_id = $_POST['usr_id'];
$book_cover = $_POST['cover'];
$remove = $_POST['remove'];

//If book cover name is equal to default.png, show a message and exit the script
if ($book_cover === "default.png") {
    echo "User did not upload any book cover!!!";
    exit();
}

//Select id specific user name from admin table
$sql_select = "SELECT user_name FROM admin WHERE id='$user_id'";
//get the user name
try {
    $user_name = $db->query($sql_select);
    $user_name = $user_name->fetch();
    $user_name = $user_name['user_name'];
} catch (PDOException $ex) {
    exit($ex);
}

//Find out whether the user had saved a book_cover with provided name in database
$sql_select = <<<SQL
SELECT book_cover FROM book WHERE (user_name = '{$user_name}' AND book_cover = '{$book_cover}')
SQL;
try {
    $row = $db->query($sql_select);
    $row = $row->rowCount();
} catch (PDOException $ex) {
    exit($ex);
}

//If the row count is equal to 1, remove the book cover and set the default book cover
if ($row === 1) {
    $book_cover_name = "default.png";
    //Set the book_cover to default.png in database
    $sql_update = "UPDATE book SET book_cover='{$book_cover_name}' WHERE (user_name = '{$user_name}'AND book_cover='{$book_cover}') ";
    try {
        $update_cover = $db->exec($sql_update);
    } catch (PDOException $ex) {
        exit($ex);
    }

    //Delete from directroy
    $delete_path = "files/" . $book_cover;
    $delete_path_tmb = "thumb/" .$book_cover;
    
    if (unlink($delete_path) && unlink($delete_path_tmb)) {
        echo "Successfully Removed";
    }else{
        echo "Could not be removed!!";
    }
}
