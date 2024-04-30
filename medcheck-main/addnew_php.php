<?php
// Establish database connection (replace these values with your actual database credentials)
$host = "localhost";
$username = "root";
$password = "";
$database = "medtrack";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$pat_id = $_POST['pat_id'] ?? '';
$pat_name = $_POST['pat_name'] ?? '';
$age = $_POST['age'] ?? '';
$phno = $_POST['phno'] ?? '';
$address = $_POST['address'] ?? '';
$disease = $_POST['disease'] ?? '';
$doc_id = $_POST['doc_id'] ?? '';
$doc_name = $_POST['doc_name'] ?? '';
$tabletNames = $_POST['tabletName'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$intakeTimes = $_POST['intakeTime'] ?? [];
$times = $_POST['time'] ?? [];

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO add_new (pat_id, pat_name, age, phno, address, disease, doc_id, doc_name, tabletName, quantity, intakeTime, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississssssss", $pat_id, $pat_name, $age, $phno, $address, $disease, $doc_id, $doc_name, $tabletName, $quantity, $intakeTime, $time);

// Insert form data into database for each prescription
for ($i = 0; $i < count($tabletNames); $i++) {
    $tabletName = $tabletNames[$i];
    $quantity = $quantities[$i];
    
    // Split intake time into separate times
    $intakeTimesArray = explode(" ", $intakeTimes[$i]);
    $timesArray = explode(" ", $times[$i]);

    // Insert a record for each intake time
    for ($j = 0; $j < count($intakeTimesArray); $j++) {
        $intakeTime = $intakeTimesArray[$j];
        $time = $timesArray[$j];

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "Record added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Close statement and connection
$stmt->close();
$conn->close();
?>