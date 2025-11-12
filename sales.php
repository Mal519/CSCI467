<?php

session_start();

// Include config file
include 'config.php';
//print_r($_SESSION);
//print_r($_POST);

// FINALIZE QUOTE
if(isset($_POST['FinalizeQuote'])){
    $conn2 = mysqli_init();

    if (!mysqli_real_connect($conn2, $servername2, $username2, $password2, $dbname2, $port2)) {
        die("<p> Sales Database Connection failed: " . mysqli_connect_error() . "</p>");
    }

    echo "Quote finalized";
    $sql = "UPDATE Quote SET Status = 'Finalized' WHERE QuoteID=". $_POST['QuoteID'];

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
        <h1>Quote System</h1>
    </header>
    <body>
        <form method="post" action="existing_quote.php">
            <button type='submit' name="ExistingQuote" value="ExistingQuote">Existing Quotes</button>
        </form>
        <form method="post" action="new_quote.php">
                <button type='submit' name="NewQuote" value="newquote">NewQuote</button>
        </form>
        <form method="post" action="index.php">
                <button type='submit' name="Logout" value="logout">Logout</button>
        </form>

    </body>