<?php
session_start();

$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "social_platform";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user = mysqli_real_escape_string($conn, $_POST['username']);
$pass = $_POST['password'];

// Check if user exists
$sql = "SELECT * FROM users WHERE username='$user' OR email='$user' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($pass, $row['password'])) {
        // Password is correct
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect to your main social page (official.html or wherever you want)
        header("Location: official.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid username or password.";
}

$conn->close();
?>
