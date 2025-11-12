<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include config file
include 'config.php';

// Connect to new database (adjust as needed)
$conn = mysqli_connect($servername2, $username2, $password2, $dbname2, $port2);

$sql = "
DROP TABLE IF EXISTS Note;
DROP TABLE IF EXISTS Lineitem;
DROP TABLE IF EXISTS Quote;
DROP TABLE IF EXISTS Associate;
DROP TABLE IF EXISTS Address;

CREATE TABLE Address (
    addressID INT AUTO_INCREMENT PRIMARY KEY,
    Street VARCHAR(100) NOT NULL,
    Street2 VARCHAR(100),
    City VARCHAR(50) NOT NULL,
    State CHAR(2) NOT NULL,
    Zip VARCHAR(10) NOT NULL
);

CREATE TABLE Associate (
    EmpID INT AUTO_INCREMENT PRIMARY KEY,
    FName VARCHAR(50) NOT NULL,
    LName VARCHAR(50) NOT NULL,
    commission DECIMAL(5,2) DEFAULT 0.00,
    password VARCHAR(255) NOT NULL,
    addressID INT,
    empType ENUM('Sales', 'Admin') DEFAULT 'Sales',
    FOREIGN KEY (addressID) REFERENCES Address(addressID)
);

CREATE TABLE Quote (
    QuoteID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    EmpID INT,
    Status ENUM('Pending', 'Finalized', 'Sanctioned', 'Processed') DEFAULT 'Pending',
    email VARCHAR(100),
    discountAmt DECIMAL(10,2) DEFAULT 0.00,
    discountPct DECIMAL(5,2) DEFAULT 0.00,
    processDate DATE,
    commissionRate DECIMAL(5,2) DEFAULT 0.00,
    FOREIGN KEY (EmpID) REFERENCES Associate(EmpID)
);

CREATE TABLE Lineitem (
    lineID INT AUTO_INCREMENT PRIMARY KEY,
    QuoteID INT,
    price DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY (QuoteID) REFERENCES Quote(QuoteID)
);

CREATE TABLE Note (
    NoteID INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    QuoteID INT,
    FOREIGN KEY (QuoteID) REFERENCES Quote(QuoteID)
);

INSERT INTO Address (Street, Street2, City, State, Zip) VALUES
('123 Main St', NULL, 'Springfield', 'IL', '62701'),
('456 Oak Ave', 'Apt 2B', 'Chicago', 'IL', '60616'),
('789 Pine Rd', NULL, 'Naperville', 'IL', '60540'),
('321 Maple Dr', 'Suite 100', 'Peoria', 'IL', '61614');

INSERT INTO Associate (FName, LName, commission, password, addressID, empType) VALUES
('Alice', 'Johnson', 0.00, 'password', 1, 'Sales'),
('Bob', 'Smith', 0.00, 'password', 2, 'Admin');

INSERT INTO Quote (CustomerID, EmpID, Status, email, discountAmt, discountPct) VALUES 
(101, 1, 'Pending', 'customer101@example.com', 50.00, 5.00);

INSERT INTO Quote (CustomerID, EmpID, Status, email, discountAmt, discountPct) VALUES 
(97, 1, 'Pending', 'customer97@example.com', 1, 1);

INSERT INTO Lineitem (QuoteID, price, description) VALUES
(1, 499.99, 'Widget'),
(1, 79.99, 'Labor'),
(1, 19.99, 'Delivery Fee');

INSERT INTO Note (text, QuoteID) VALUES 
('Wikipedia is the best thing ever.', 1);
";

// Execute multiple statements
if (mysqli_multi_query($conn, $sql)) {
    echo "<p>Database tables created successfully!</p>";
} else {
    echo "<p>Error creating tables: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>