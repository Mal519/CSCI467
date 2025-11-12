<?php

session_start();

// Include config file
include 'config.php';

// Create connection to local XAMPP database
$conn = new mysqli($servername2, $username2, $password2, $dbname2, $port2);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empID = $_POST['EmpID'];
    $pass = $_POST['Password'];
    $_SESSION['EmpID']  = $empID;

     $empID = mysqli_real_escape_string($conn, $empID);
     $sql = "SELECT * FROM Associate WHERE EmpID = '$empID'";
     $result = mysqli_query($conn, $sql);

     $row = mysqli_fetch_assoc($result);

    if ($row['password'] == $pass){
        if ($row['empType'] == 'Sales'){
            header("Location: sales.php");
            exit;
        }else{
            header("Location: admin.php");
            exit;
        }
    }else{
        header("Location: index.php");
exit;
    }
}
?>