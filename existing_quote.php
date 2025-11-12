<?php

session_start();

// Include config file
include 'config.php';

print_r($_SESSION);

$conn1 = mysqli_init();

if (!mysqli_real_connect($conn1, $servername1, $username1, $password1, $dbname1, $port1, NULL, MYSQLI_CLIENT_SSL)) {
    die("<p> Legacy Database Connection failed: " . mysqli_connect_error() . "</p>");
}

$conn2 = mysqli_init();

if (!mysqli_real_connect($conn2, $servername2, $username2, $password2, $dbname2, $port2)) {
    die("<p> Sales Database Connection failed: " . mysqli_connect_error() . "</p>");
}


// fetch quotes

$quotes =[];

$sql = "SELECT CustomerID, QuoteID FROM Quote WHERE EmpID = '{$_SESSION['EmpID']}' AND Status = 'Pending' ORDER BY CustomerID";
$result = mysqli_query($conn2, $sql);

if ($result && mysqli_num_rows($result) > 0) {
     while ($row = mysqli_fetch_assoc($result)) {
        $quotes[] = $row;
    }
} else {
    echo "<p>No quotes found in the database for this user.</p>";
}


// get companies for customer ids

$companies =[];

if(!empty($quotes)){
    $customerIds = array_column($quotes, 'CustomerID');
    $idsString = implode(',', array_map('intval', $customerIds));

    $sql2 = ("SELECT id, name FROM customers WHERE id IN ($idsString)");
    $companyResult = mysqli_query($conn1, $sql2);

    if ($companyResult && mysqli_num_rows($companyResult) > 0) {
        while ($row = mysqli_fetch_assoc($companyResult)) {
            $companies[$row['id']] = $row['name'];
        }
    }
}


// sort stuff alphbetically
asort($companies);

usort($quotes, function($a, $b) use ($companies) {
    // Get the CustomerIDs
    $idA = $a['CustomerID'];
    $idB = $b['CustomerID'];

    // Compare their positions in $companies
    $keys = array_keys($companies);
    return array_search($idA, $keys) - array_search($idB, $keys);
});
?>

<html>
    <head>
        <title>Choose existing Quote</title>
    </head>
    <body>
        <h1>Choose existing Quote</h1>
        <table>
            <tr>
                <th>Customer Name</th>
                <th>Quote ID</th>
                <th></th>
            </tr>
            <?php foreach ($quotes as $q): ?>
                <tr>
                    <form method="post" action="quote_details.php">
                        <td><?= $companies[$q['CustomerID']] ?></td>
                        <td><?= $q['QuoteID'] ?></td>
                        <input type="hidden" name="QuoteID" value="<?= $q['QuoteID'] ?>">
                        <input type="hidden" name="CustomerID" value="<?= $q['CustomerID'] ?>">
                        <input type="hidden" name="CustomerName" value="<?= $companies[$q['CustomerID']] ?>">
                        <td><button type="submit">select</button></td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <br>
        <form method="post" action="new_quote.php">
                <button type='submit' name="NewQuote" value="newquote">NewQuote</button>
        </form>
        <form method="post" action="index.php">
                <button type='submit' name="Logout" value="logout">Logout</button>
        </form>
    </body>
</html>