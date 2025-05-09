<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: loginforma.html');
    exit();
}

$servername = "localhost";
$dbusername = "root"; 
$dbpassword = "";
$dbname = "social_platform";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$content = mysqli_real_escape_string($conn, $_POST['content']);
$user_id = $_SESSION['user_id'];

$mediaPath = null;
$mediaType = null;

if (!empty($_FILES['media']['name'])) {
    $uploadsDir = 'uploads/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }
    $filename = time() . "_" . basename($_FILES['media']['name']);
    $targetPath = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {
        $mediaPath = $targetPath;
        $mediaType = mime_content_type($targetPath);
    }
}

$sql = "INSERT INTO posts (user_id, content, media_path, media_type) 
        VALUES ($user_id, '$content', " . ($mediaPath ? "'$mediaPath'" : "NULL") . ", " . ($mediaType ? "'$mediaType'" : "NULL") . ")";

if ($conn->query($sql) === TRUE) {
    header("Location: official.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
