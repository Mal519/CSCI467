<?php
session_start();
if(isset($_POST['Logout'])){
    session_destroy();
}
//print_r($_POST);
?>

<html>
    <body>
        <form method="POST" action=login.php>
            <label> Empoyee Number </label>
            <input type="text" name="EmpID">
            <label> Password </label>
            <input type="password" name="Password">
            <button type="submit">Login</button>
        </form>
    </body>
</html>