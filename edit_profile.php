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

$user_id = $_SESSION['user_id'];

// Update profile if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $profile_picture_path = null;

    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture_path = $target_file;
        }
    }

    $sql = "UPDATE users SET username='$newUsername', bio='$bio'";
    if ($profile_picture_path) {
        $sql .= ", profile_picture='$profile_picture_path'";
    }
    $sql .= " WHERE id=$user_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $newUsername; // update session
        header("Location: official.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

// Fetch current user info
$sql = "SELECT username, bio, profile_picture FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body {
            background: black;
            color: white;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .edit-container {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            width: 320px;
        }
        input, textarea, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }
        button {
            background: linear-gradient(to right, red, yellow);
            color: black;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <textarea name="bio" rows="3"><?php echo htmlspecialchars($user['bio']); ?></textarea>
        <input type="file" name="profile_picture" accept="image/*">
        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
