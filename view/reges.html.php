<?php 

require_once __DIR__."/header.html.php";
if(isset($_SESSION['admin'])){
    header("location:../");
    exit();
}
?>
<!-- ################################################# -->
<style>
    .block{
        display:block;
    }
</style>
<!-- ------------------------------------------------------------------------------------------------------- -->
<h1>This is the Registration Page</h1>
<form action="../registration.php" method="post">
<label class="block" for="name">Full Name
    <input id="name" type="text" value="" name="user_name">
</label>
<label class="block" for="address">User's address
    <input id="address" type="text" name="address">
</label>
<label class="block" for="email">User's Email
    <input type="email" name="email" value="" placeholder="EX @ email_name@example.com">
</label>
<label class="block" for="user_id">User ID
    <input type="text" name="user_id" value="" placeholder="At least 04 characters long">
</label>
<label class="block" for="password">User's Password
    <input type="password" name="password" value="" placeholder="At least 6 characters long">
</label>
<button class="block" type="submit" >Register</button>
</form>



<!-- ------------------------------------------------------------------------------------------------------ -->
<?php
require_once __DIR__."/footer.html.php";
?>