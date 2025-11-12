<?php

session_start();
//print_r($_POST);
//print_r($_SESSION);
// Include config file
include 'config.php';

// Connect to legacy database
$conn2 = mysqli_init();

if (!mysqli_real_connect($conn2, $servername2, $username2, $password2, $dbname2, $port2)) {
    die("<p> Sales Database Connection failed: " . mysqli_connect_error() . "</p>");
}

$sql = "INSERT INTO Quote (CustomerID, EmpID, email) VALUES ({$_POST['CustomerID']}, {$_SESSION['EmpID']}, '{$_POST['email']}')";
$result = mysqli_query($conn2, $sql);

// get quote id
$quoteID = mysqli_insert_id($conn2);

echo $quoteID;
?>

<html>
    <head>
        <form id="redirectForm" action="quote_details.php" method="post">
            <input type="hidden" name="QuoteID" value="<?= $quoteID ?>">
            <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
        </form>
        <script>
            document.getElementById('redirectForm').submit();
        </script>
    </head>
</html>