<?php
require_once __DIR__ . "/header.html.php";
?>
<?php
// Select all from table book
require_once "../d_connect.php";

if (!isset($_SESSION['admin'])) {
    header("location:../");
    exit();
}

$user_id = $_SESSION["admin"];

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
//Identify how many database rows are available
$sql_select = <<<SQL
SELECT COUNT(title) FROM book WHERE user_name = '{$user_name}'
SQL;
try {
    $count = $db->query($sql_select);
    $count = $count->fetch();
    $unmrows = $count['COUNT(title)'];
} catch (PDOException $ex) {
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
$sql_select = "SELECT * FROM book WHERE user_name='$user_name' LIMIT $offset,$rows_per_page";
try{
    $result=$db->query($sql_select);
}catch(PDOExtension $ex){
    exit($ex);
}
$result = $result->fetchAll();


#The name of the array that contains all results is $result

?>


<!-- -----------------TEMPLATE Begins------------------------------------------------------------------------------ -->
<style>
 table, th, td {
    border: 1px solid black;
    text-align: center;
}
.center{
    text-align:center;
}

</style>

<!-- Show message when user name and password does not match -->
<?php if (isset($_SESSION['msg'])) {
    echo "<br>" . $_SESSION['msg'] . "<br><hr><br>";
    unset($_SESSION['msg']);
}?>

<h1 class ="center">This is the book view page</h1>
<!-- Add book -->
<br>
<form action="./search.html.php" method="post">
    <label for="search">Search Box:
        <input type="text" name="search" value="" placeholder="Enter Book Title">
    </label>
    <button type="submit">Search</button>
</form>
<br>
<a href="../index.php?add=yes">Add Book</a>
<br>
<br>
<hr>
<!-- Pagination Buttons -->
<div class="pagination">
    <?php if($pageno === 1):?>
        <?= "FRIST PREV"?>
    <?php else:?>    
        <a href="<?=$_SERVER['PHP_SELF'].'?pageno=1' ?>">FIRST</a>
        <?php $prevpage = $pageno - 1?>
        <a href='<?=$_SERVER['PHP_SELF']."?pageno=$prevpage" ?>'>PREV</a>  
    <?PHP endif?> 

    <?= "(Page $pageno of $lastpage)"?> 

    <?php if($pageno === $lastpage):?>
        <?= "NEXT LAST"?>
    <?php else:?>  
        <?php $nextpage = $pageno + 1?>
        <a href='<?=$_SERVER['PHP_SELF']."?pageno=$nextpage" ?>'>NEXT</a>
        <a href='<?=$_SERVER['PHP_SELF']."?pageno=$lastpage" ?>'>LAST</a>  
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
        <th>Action</th>
    </tr>
    <?php foreach ($result as $row): ?>
        <tr>
            <td><?=$row['title']?></td>
            <td><?=$row['author']?></td>
            <td><?=$row['summery']?></td>
            <td><?=$row['date_of_pub']?></td>
            <td><?=$row['user_name']?></td>
            <td><?=$row['copy_avl']?></td>
            <td><?=$row['published_at']?></td>
                <!-- ++++++++Image path change depends on save file+++++++++++++++++? -->
                <?php $img_add = ($row['book_cover'] !== "default.png") ? '../thumb/' . $row['book_cover'] : '../asset/' . $row['book_cover']?>
                <?php $img_show = ($row['book_cover'] !== "default.png") ? '../files/' . $row['book_cover'] : '../asset/' . $row['book_cover']?>
                <!-- ---------------------------------------------------------------------------- -->
            <td><a href="<?=$img_show?>"><img src="<?=$img_add?>" alt="cover Pic"></a></td>
            <td><a onclick="return confirm('Do you want to delete the book?')" href="../index.php?del=yes&id=<?=$row['id']?>" >Delete Book</a> / <a href="./update.html.php?update=yes&book_id=<?=$row['id']?>&usr_id=<?=$user_id?>">Update Book</a></td>
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
require_once __DIR__ . "/footer.html.php";
?>
