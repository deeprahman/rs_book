<?php
include_once "../session.php";
if(!isset($_SESSION['admin'])){
    header("location:../");
    exit();
}

require_once __DIR__."/header.html.php";
?> 
<!-- ---------------------------------------------------------------------------------------------- -->
<style>
 .block{
     display: block;
 }
</style>
<?php 
?>
<h1>Enter info about the book</h1>
<!-- Book information -->
<form action="../index.php" method="post" enctype="multipart/form-data">
    <label class="block" for="title">Book Title:
        <input type="text" name="title" value="" required>
    </label>
    <label class="block"  for="author">Author name
        <input type="text" name="author" value="" required>
    </label>
    
    <label class="block"  class="block"  for="date_pub">Date of publication:
        <input id="date_pub" type="date" name="date_pub" value="" placeholder="EX@ 2018-11-04" required>
    </label>
       <label class="block"  for="avl_copy">Number of available copy of book:
        <input id="avl_copy" type="text" name="avl_copy" required>
    </label>
    <textarea class="block"  name="summery" id="" cols="30" rows="10">Add Book Description</textarea>
    <label class="block" for="file"> 
        <input id="file" type="file" name="image">
    </label>
    <button type="submit" name="add_book" value="submit">ADD THE BOOK</button>
</form>
<!-- ---------------------------------------------------------------------------------------------- -->
<?php
require_once __DIR__."/footer.html.php";
?> 
