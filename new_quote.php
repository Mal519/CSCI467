<?php

session_start();

//print_r($_SESSION);
//print_r($_POST);

// Include config file
include 'config.php';

// Connect to legacy database
$conn1 = mysqli_init();

if (!mysqli_real_connect($conn1, $servername1, $username1, $password1, $dbname1, $port1, NULL, MYSQLI_CLIENT_SSL)) {
    die("<p> Legacy Database Connection failed: " . mysqli_connect_error() . "</p>");
}

// --- Query all customers ---
$sql = "SELECT * FROM customers ORDER BY name";
$result = mysqli_query($conn1, $sql);

if ($result && mysqli_num_rows($result) > 0) {
     while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
} else {
    echo "<p>No customers found in the database.</p>";
}

mysqli_close($conn1);
?>

<html>
    <head>
        <title>Create New Quote</title>
    </head>
    <body>
        <h1>Create New Quote</h1>
        <form method="post" action="quote_creation.php">
        <select name="CustomerID" id="customerSelect" required>
            <option value="">-- Choose a customer --</option>
            <?php foreach ($customers as $c): ?>
                <option value="<?php echo ($c['id']); ?>" data-name="<?= $c['name'] ?>">
                    <?= ($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter email" required>
        <input type="hidden" name="CustomerName" id="customerName">
        <button type="submit">Create Quote</button>
        </form>

        <script>
        // Grab hidden input
        const customerSelect = document.getElementById('customerSelect');
        const customerNameInput = document.getElementById('customerName');

        // Update hidden field
        customerSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            customerNameInput.value = selectedOption.getAttribute('data-name') || '';
        });
    </script>
        <form method="post" action="existing_quote.php">
                <button type='submit' name="ExistingQuote" value="ExistingQuote">Existing Quotes</button>
        </form>
        <form method="post" action="index.php">
                <button type='submit' name="Logout" value="logout">Logout</button>
        </form>
    </body>
</html>