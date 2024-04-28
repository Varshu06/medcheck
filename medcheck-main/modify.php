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
$pat_id = $_POST['pat_id'];
$tabletNames = $_POST['tabletName'];
$quantities = $_POST['quantity'];
$intakeTimes = $_POST['intakeTime'];


// Prepare and bind the SQL statement
$stmt = $conn->prepare("UPDATE add_new 
                        SET tabletName=?, quantity=?, intakeTime=? 
                        WHERE pat_id=?");
$stmt->bind_param("sssi", $tabletName, $quantity, $intakeTime, $pat_id);


// Update prescription details in the database
for ($i = 0; $i < count($tabletNames); $i++) {
    $tabletName = $tabletNames[$i];
    $quantity = $quantities[$i];
    $intakeTime = $intakeTimes[$i];
    
    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Prescription details updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
