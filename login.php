<?php
session_start(); // Start a session

$servername = "localhost"; // Change to localhost
$username = "root"; // Default username
$password = ""; // Default password
$dbname = "user_management"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    
    // Execute the query
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($pass, $hashedPassword)) {
            // Successful login
            $_SESSION['username'] = $user; // Store username in session
            header("Location: welcome.php"); // Redirect to welcome page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
