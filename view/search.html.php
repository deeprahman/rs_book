<?php
require_once __DIR__."/header.html.php";
?> 
<?php
// Select all from table book
require_once "../d_connect.php";

if(!isset($_SESSION['admin'])){
    header("location:../");
    exit();
}

$user_id = $_SESSION["admin"];

//Select id specific user name from admin table
$sql_select = "SELECT user_name FROM admin WHERE id='$user_id'";
 //get the user name
 try{
    $user_name = $db->query($sql_select);
    $user_name = $user_name->fetch();
    $user_name = $user_name['user_name'];
 }catch(PDOException $ex){
    exit($ex);
 }

// when  search box is empty return to book view page
 if(empty($_POST['search'])){
    header("location:..");
    exit();

 }
 
// Get the data
$search = filter_input(INPUT_POST,'search',FILTER_SANITIZE_FULL_SPECIAL_CHARS);


//  Search database 
$sql_select = <<<SQL
SELECT * FROM book WHERE (user_name = '{$user_name}'AND title LIKE '%$search%')
SQL;

try{
$select = $db->query($sql_select);

$result= $select->fetchAll();
}catch(PDOException $ex){

}

?>

<!-- ===========================Template=============================================== -->
<style>
 table, th, td {
    border: 1px solid black;
    text-align: center;
}
.center{
    text-align:center;
}

</style>

<!-- ----------------------------------------------------------------- -->

<h1 class="center">The Search Page</h1>
<br>
<p><a href="../">Go back to the Book View</a></p>
<hr>
<h4>The search result for <?=$search?></h4>
<table>
    <tr>
        
        <th>Title</th>
        <th>Author</th>
        <th>Summery</th>
        <th>Date of Publication</th>
        <th>Book Added By</th>
        <th>Number of Available Copy</th>
        <th>Published At</th>
        <th>Cover Picture</th>
 
    </tr>
    <?php foreach($result as $row):?>
        <tr>
            <td><?=$row['title']?></td>
            <td><?=$row['author']?></td>
            <td><?=$row['summery']?></td>
            <td><?=$row['date_of_pub']?></td>
            <td><?=$row['user_name']?></td>
            <td><?=$row['copy_avl']?></td>
            <td><?=$row['published_at']?></td>
                <!-- ++++++++Image path change depends on save file+++++++++++++++++? -->
                <?php $img_add =($row['book_cover']!=="default.png") ? '../files/'.$row['book_cover']:'../asset/'.$row['book_cover'] ?>
                <!-- ---------------------------------------------------------------------------- -->
            <td><img src="<?=$img_add?>" alt="cover Pic"></td>
            
        </tr>
    <?php endforeach?>
</table>




<!-- ============================================================== -->
<?php
require_once __DIR__."/footer.html.php";
?> 