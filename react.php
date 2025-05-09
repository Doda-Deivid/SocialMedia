<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "social_platform";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    exit();
}

$postId = intval($_POST['post_id']);
$reaction = $_POST['reaction'];

$validReactions = ['likes', 'loves', 'hahas', 'skull', 'mad'];

if (!in_array($reaction, $validReactions)) {
    http_response_code(400);
    exit();
}

$sql = "UPDATE posts SET $reaction = $reaction + 1 WHERE id = $postId";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    http_response_code(500);
}

$conn->close();
?>
