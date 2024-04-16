<?php
// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "medcheck";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    $check_username_sql = "SELECT username FROM signup WHERE username = ?";
    $check_stmt = $conn->prepare($check_username_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Username already exists, display a message
        echo "Username already exists. Please choose a different username.";
    } else {
        // Username is available, proceed with insertion
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert data into the database
        $sql = "INSERT INTO signup (username, password) VALUES (?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to login page
            header("Location: login.html");
            exit(); // Make sure no further code execution happens after redirection
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close statement
    $check_stmt->close();
}

// Close connection
$conn->close();
?>