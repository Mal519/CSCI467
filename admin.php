<?php

session_start();

// Include config file
include 'config.php';
//print_r($_SESSION);
//print_r($_POST);

// FINALIZE QUOTE
if(isset($_POST['SanctionQuote'])){
    $conn2 = mysqli_init();

    if (!mysqli_real_connect($conn2, $servername2, $username2, $password2, $dbname2, $port2)) {
        die("<p> Sales Database Connection failed: " . mysqli_connect_error() . "</p>");
    }

    echo "Quote finalized";
    $sql = "UPDATE Quote SET Status = 'Sanctioned' WHERE QuoteID=". $_POST['QuoteID'];

    $finalize = mysqli_query($conn2, $sql);
}

// Delete QUOTE
if(isset($_POST['DeleteQuote'])){
    $conn2 = mysqli_init();

    if (!mysqli_real_connect($conn2, $servername2, $username2, $password2, $dbname2, $port2)) {
        die("<p> Sales Database Connection failed: " . mysqli_connect_error() . "</p>");
    }

    echo "Quote deleted";
    $sql = "DELETE FROM Lineitem WHERE QuoteID=". $_POST['QuoteID']
            . "; DELETE FROM Note WHERE QuoteID=". $_POST['QuoteID']
            . "; DELETE FROM Quote WHERE QuoteID=". $_POST['QuoteID'];

    $finalize = mysqli_multi_query($conn2, $sql);
}
?>



<html>
    <header>

    </header>
    <body>
        <a href="final.php">Finalized Quotes</a>
        <a href="sanction.php">Sanctioned Quotes</a>
        <a href="administrative.php">Administrative Interface</a>

    </body>
</html>