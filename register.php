<?php
$servername = "localhost";
$username = "root"; // or your MySQL username
$password = ""; // or your MySQL password
$dbname = "social_platform";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$raw_password = $_POST['password'];
$bio = mysqli_real_escape_string($conn, $_POST['bio']);

// Handle file upload
$profile_picture_path = null;
if (!empty($_FILES['profile_picture']['name'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $profile_picture_path = $target_file;
    }
}

// Hash the password
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password, profile_picture, bio)
        VALUES ('$username', '$email', '$hashed_password', '$profile_picture_path', '$bio')";

if ($conn->query($sql) === TRUE) {
    echo "Profile created successfully! <a href='loginforma.html'>Login here</a>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
