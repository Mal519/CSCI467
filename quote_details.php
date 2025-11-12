<?php

session_start();

print_r($_POST);

// Include config file
include 'config.php';

$conn1 = mysqli_init();
$conn2 = mysqli_init();

if (!mysqli_real_connect($conn1, $servername1, $username1, $password1, $dbname1, $port1, NULL, MYSQLI_CLIENT_SSL)) {
    die("<p> Legacy Database Connection failed: " . mysqli_connect_error() . "</p>");
}

$conn2 = mysqli_init();

if (!mysqli_real_connect($conn2, $servername2, $username2, $password2, $dbname2, $port2)) {
    die("<p> Sales Database Connection failed: " . mysqli_connect_error() . "</p>");
}


// fetch quotes

$quotes =[];
$lineitems=[];
$notes=[];


// edits line items
if(isset($_POST['edit'])){
  //  echo "The edit button was pushed";
    $sql = "UPDATE Lineitem SET price =" . $_POST['price'] 
            . ", description = '" . $_POST['description'] 
            . "' WHERE lineID=". $_POST['edit'];

    $update = mysqli_query($conn2, $sql);
}

// deletes line items
if(isset($_POST['delete'])){
  //  echo "The delete button was pushed";
    $sql = "DELETE FROM Lineitem WHERE lineID=". $_POST['delete'];

    $delete = mysqli_query($conn2, $sql);
}

// adds line items
if(isset($_POST['NewItem'])){
 //   echo "The New Item button was pushed";
    $sql = "INSERT INTO Lineitem (price, description, quoteID) 
            VALUES ('00', '', " . $_POST['QuoteID'] . ");";

    $delete = mysqli_query($conn2, $sql);
}

// edits notes
if(isset($_POST['EditNote'])){
  //  echo "The edit note button was pushed";
    $sql = "UPDATE Note SET text ='" . $_POST['noteText']
            . "' WHERE NoteID=". $_POST['EditNote'];

    $update = mysqli_query($conn2, $sql);
}

// deletes notes
if(isset($_POST['deleteNote'])){
  //  echo "The delete note button was pushed";
    $sql = "DELETE FROM Note WHERE NoteID=". $_POST['deleteNote'];

    $delete = mysqli_query($conn2, $sql);
}

// adds notes
if(isset($_POST['NewNote'])){
  //  echo "The add note button was pushed";
    $sql = "INSERT INTO Note (text, quoteID) 
            VALUES (' ', " . $_POST['QuoteID'] . ");";

    $delete = mysqli_query($conn2, $sql);
}

// changes discountAmt
if(isset($_POST['editDiscount'])){
  //  echo "The add note button was pushed";
    $sql = "Update Quote SET discountAmt = '" . $_POST['discount']
             . "' WHERE QuoteID="
             . $_POST['QuoteID'] . ";";

    $changeDiscount = mysqli_query($conn2, $sql);
}

//fixme
// changes discountPct
if(isset($_POST['editDiscountPCT'])){
  //  echo "The add note button was pushed";
    $sql = "Update Quote SET discountPct = '" . $_POST['discountPct']
             . "' WHERE QuoteID="
             . $_POST['QuoteID'] . ";";

    $changeDiscount = mysqli_query($conn2, $sql);
}

// grabs line items
$sql = "SELECT * FROM Lineitem WHERE quoteID = '{$_POST['QuoteID']}'";
$result = mysqli_query($conn2, $sql);

if ($result && mysqli_num_rows($result) > 0) {
     while ($row = mysqli_fetch_assoc($result)) {
        $lineitems[] = $row;

    }
} else {
  //  echo "<p>Line items not found.</p>";
}

// grabs notes
$sql = "SELECT * FROM Note WHERE quoteID = '{$_POST['QuoteID']}'";
$notesResult = mysqli_query($conn2, $sql);

if ($notesResult && mysqli_num_rows($notesResult) > 0) {
     while ($row = mysqli_fetch_assoc($notesResult)) {
        $notes[] = $row;

    }
//    print_r($notes);
} else {
  //  echo "<p>Notes not found.</p>";
}

// grabs discounts and status
$sql = "SELECT discountAmt, discountPct, Status FROM Quote WHERE quoteID = '{$_POST['QuoteID']}'";
$discount = mysqli_query($conn2, $sql);

if ($discount && mysqli_num_rows($discount) > 0) {
     while ($row = mysqli_fetch_assoc($discount)) {
        $discounts[] = $row;

    }
} else {
    echo "<p>Discounts not found.</p>";
}


print_r($discounts)
?>


<html>
    <header>
        <h1> Quote <?= $_POST['QuoteID']  ?> </h1>
        <h3><?= $_POST['CustomerName']  ?></h3>
    </header>
    <body>
        <table>
            <tr>
                <th>Price</th>
                <th>Description</th>
            </tr>
            
            <?php foreach ($lineitems as $line): ?>
            <tr>
                <form method="post" action="quote_details.php">
                    <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                    <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                    <td><input name='price' value="<?= number_format($line['price'], 2) ?>" >  </td>
                    <td><input name='description' value='<?= $line['description'] ?>'' ></td>
                    <td><button type='submit' name='edit' value='<?= $line['lineID'] ?>''>Edit</button></td>
                    <td><button type='submit' name='delete' value='<?= $line['lineID'] ?>''>Delete</button></td>
                </form>
            </tr>
            <?php endforeach; ?>
            <tr>
            </tr>
                <td></td>
                <td></td>
                <td></td>
                <form method="post" action="quote_details.php">
                    <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                    <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                    <td><button type='submit' name="NewItem" value="add">New Item</button></td>
                </form>
            <tr>
                <th>Discount</th>
            </tr>
            <tr>
                <form method="post" action="quote_details.php">
                    <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                    <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                    <td><input name='discount' value="<?= number_format($discounts[0]['discountAmt'], 2) ?>" >  </td>
                    <td><button type='submit' name='editDiscount'>Edit</button></td>
                </form>
            </tr>
            <tr>
                <th>Additional % Discount</th>
            </tr>
            <tr>
                <form method="post" action="quote_details.php">
                    <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                    <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                    <td><input name='discountPct' value="<?= number_format($discounts[0]['discountPct'], 2) ?>" >  </td>
                    <td><button type='submit' name='editDiscountPCT''>Edit</button></td>
                </form>
            </tr>
        </table>
        <h4>Notes</h4>
        <?php foreach ($notes as $note): ?>
            <br>
            <form method="post" action="quote_details.php">
                <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                <textarea name='noteText' rows="5" cols ='30'><?= $note['text'] ?></textarea>
                <button type='submit' name="EditNote" value='<?= $note['NoteID'] ?>'>Edit Note</button>
                <td><button type='submit' name='deleteNote' value='<?= $note['NoteID'] ?>'>Delete</button></td>
            </form>   
        <?php endforeach; ?>
            <form method="post" action="quote_details.php">
                <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                <button type='submit' name="NewNote" value="add">New Note</button>
            </form>
            <?php if($discounts[0]['Status'] == 'Pending'): ?>
            <form method="post" action="sales.php">
                <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                <button type='submit' name="FinalizeQuote" value="finalize">Finalize Quote</button>
                <button type='submit' name="DeleteQuote" value="delete">Delete Quote</button>
            </form>
            <?php endif; ?>
            <?php if($discounts[0]['Status'] == 'Finalized'): ?>
            <form method="post" action="admin.php">
                <input type="hidden" name="QuoteID" value="<?= $_POST['QuoteID'] ?>">
                <input type="hidden" name="CustomerName" value="<?= $_POST['CustomerName'] ?>">
                <button type='submit' name="SanctionQuote" value="Sanction">Sanction Quote</button>
                <button type='submit' name="DeleteQuote" value="delete">Delete Quote</button>
            </form>
            <?php endif; ?>
        <form method="post" action="index.php">
                <button type='submit' name="Logout" value="logout">Logout</button>
        </form>
    </body>
</html>
