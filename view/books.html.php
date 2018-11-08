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

 
/**
 * Pagination: 
 * Shows two records per page
 */

 // Number of records to be shown per page
$rec_limit = 5;

// Get the page number after usr clicks the page number
if (isset($_GET['page'])) {
    // increase the caught page number by 1
    $page = $_GET['page'] + 1;
    // Set the offset record from
    $offset = $rec_limit * $page;
}else{
    // when book template is being displayed for the first time
    $page = 0;
    $offset = 0;
}


// Fetch total number of book rocords for a user
$sql_select = <<<SQL
SELECT COUNT(title) FROM book WHERE user_name = '{$user_name}'
SQL;
try{
$count = $db->query($sql_select);
$count= $count->fetch();
$rec_count = $count['COUNT(title)'];
}catch(PDOException $ex){
    exit($ex->getMessage());
}
// The nunber of book records left after displaying the current page
$left_rec = $rec_count - ($page * $rec_limit);



//Select all  uploaded books from the book table
$sql_select = "SELECT * FROM book WHERE user_name='$user_name' LIMIT $offset,$rec_limit";
try{
    $result=$db->query($sql_select);
}catch(PDOExtension $ex){
    exit($ex);
}
$result = $result->fetchAll();




?>


<!-- -----------------TEMPLATE Begins------------------------------------------------------------------------------ -->
<style>
 table, th, td {
    border: 1px solid black;
    text-align: center;
}

</style>

<!-- Show message when user name and password does not match -->
<?php if(isset($_SESSION['msg'])){
  echo "<br>".$_SESSION['msg']."<br><hr><br>";
  unset($_SESSION['msg']);
}?>

<h1>THis is the book view page</h1>
<!-- Add book -->
<a href="../index.php?add=yes">Add Book</a>
<br>
<br>
<hr>
<div class="pagination">
    <?php if($page > 0 && !($left_rec<$rec_limit)):?>
        <?php $last = $page -2?>
        <?= "<a href=\"?page=$last\">Previous</a>"?>
        <?= "<a href=\"?page=$page\">Next</a>"?>
    <?php elseif($page===0):?>  
        <?= "<a href=\"?page=$page\">Next</a>"?>
    <?php elseif($left_rec<$rec_limit):?>
        <?php $last = $page -2?>  
        <?= "<a href=\"?page=$last\">Previous</a>"?>
    <?php endif?>        
</div>
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
        <th>Action</th>
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
            <td><a onclick="return confirm('Do you want to delete the book?')" href="../index.php?del=yes&id=<?=$row['id']?>" >Delete Book</a> / <a href="./update.html.php?update=yes&book_id=<?=$row['id']?>&usr_id=<?= $user_id?>">Update Book</a></td>
        </tr>
    <?php endforeach?>
</table>

<!-- ---------JavaScript------------------- -->
<script type="text/javascript">
function myFunction() {
    var txt;
    if (confirm("Press a button!")) {
        txt = "You pressed OK!";
    } else {
        txt = "You pressed Cancel!";
    }
</script>

<?php
require_once __DIR__."/footer.html.php";
?> 
