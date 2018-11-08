<?php
require_once __DIR__."/header.html.php";
if(isset($_SESSION['admin'])){
    header("location:..");
    exit();
}

?> 
<!-- ---------------------------------------------------------------------------------------------- -->
<!-- Show message when user name and password does not match -->
<?php if(isset($_SESSION['msg'])){
  echo "<br>".$_SESSION['msg']."<br><hr><br>";
  unset($_SESSION['msg']);
}?>
<!-- ----------------------------------------------------------------------------------------------------- -->
<form action="login.php" method="post">
    <label for="id">USER ID
    <input id="id" type="text" value="" name="userID" placeholder="Put the user ID">
    </label>
    <label for="pass"></label> Password
    <input id="pass" type="password" name="password" placeholder="Put the password">
    <button type="submit">SUBMIT</button>
</form>
<!-- ---------------------------------------------------------------------------------------------------- -->
<?php
require_once __DIR__."/footer.html.php";
?> 
