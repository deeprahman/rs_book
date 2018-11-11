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
 if(empty($_POST['search']) && !isset($_GET['pageno'])){
    header("location:..");
    exit();

 }
 
// Get the data
if (isset($_POST['search'])) {
    $_SESSION['search'] = filter_input(INPUT_POST,'search',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $search = $_SESSION['search'] ;
}else{
    $search = $_SESSION['search'] ;
}



/**
 * Pagination:
 * Shows two records per page
 */
//Number of records per page
$rows_per_page = 2;

//Obtain the required page number
if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

//  Search database for total number of records for a given condition
$sql_select = <<<SQL
SELECT COUNT(title) FROM book WHERE (user_name = '{$user_name}' AND title LIKE '%$search%') 
SQL;

try{
    $count = $db->query($sql_select);
    $count = $count->fetch();
    $unmrows = $count['COUNT(title)'];

}catch(PDOException $ex){
    exit($ex->getMessage());
}

//The last page number
$lastpage = ceil($unmrows / $rows_per_page);

//Ensure that the page number  stays within a the first page number and the last page number
$pageno = (int) $pageno;

if ($pageno > $lastpage) {
    $pageno = $lastpage;
}
if ($pageno < 1) {
    $pageno = 1;
}

//Defint offset

$offset = ($pageno - 1) * $rows_per_page;


//Select all  uploaded books from the book table
$sql_select = "SELECT * FROM book WHERE (user_name = '{$user_name}' AND title LIKE '%$search%') LIMIT $offset,$rows_per_page"; 
try{
    $result=$db->query($sql_select);
}catch(PDOExtension $ex){
    exit($ex);
}
$result = $result->fetchAll();

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
<br>
<br>
<hr>
<!-- Pagination Buttons -->
<div class="pagination">
    <?php if($pageno === 1):?>
        <?= "FRIST PREV"?>
    <?php else:?>    
        <a href="?pageno=1">FIRST</a>
        <?php $prevpage = $pageno - 1?>
        <a href='<?="?pageno=$prevpage" ?>'>PREV</a>  
    <?PHP endif?> 

    <?= "(Page $pageno of $lastpage)"?> 

    <?php if($pageno === $lastpage):?>
        <?= "NEXT LAST"?>
    <?php else:?>  
        <?php $nextpage = $pageno + 1?>
        <a href='<?="?pageno=$nextpage" ?>'>NEXT</a>
        <a href='<?="?pageno=$lastpage" ?>'>LAST</a>  
    <?PHP endif?> 

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