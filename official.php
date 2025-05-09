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

// Connect
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user info
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, profile_picture, bio FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$userData = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Socialinė platforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    :root {
        --bg-light: #fafafa;
        --bg-dark: #000;
        --text-light: #262626;
        --text-dark: #fafafa;
        --container-light: #fff;
        --container-dark: #1c1c1c;
        --border-light: #dbdbdb;
        --border-dark: #3a3b3c;
    }
    body {
        font-family: 'Arial', sans-serif;
        background-color: var(--bg-light);
        color: var(--text-light);
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all 0.3s ease-in-out;
    }
    body.dark-mode {
        background-color: var(--bg-dark);
        color: var(--text-dark);
    }
    .container {
        width: 100%;
        max-width: 1000px; /* ✅ Wider feed */
        background: var(--container-light);
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: all 0.3s ease-in-out;
    }
    body.dark-mode .container {
        background: var(--container-dark);
    }
    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    .post-box, .filter-box {
        width: 95%;
        padding: 10px;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        background: inherit;
        color: inherit;
        margin-bottom: 10px;
    }
    body.dark-mode .post-box, body.dark-mode .filter-box {
        border: 1px solid var(--border-dark);
    }
    button {
        background-color: #0095f6;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }
    .media-preview img, .media-preview video {
        width: 300px; /* ✅ Small preview */
        margin-top: 10px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .media-expanded {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        max-height: 90%;
        z-index: 1000;
        border-radius: 12px;
        box-shadow: 0 0 30px rgba(0,0,0,0.7);
    }
    #overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.8);
        display: none;
        z-index: 999;
    }
    .buttons {
        margin-top: 10px;
    }
    .buttons button {
        background: none;
        font-size: 22px;
        margin-right: 10px;
    }
	
	.buttons span {
    color: var(--text-light);
    transition: color 0.3s ease;
}

body.dark-mode .buttons span {
    color: var(--text-dark);
}

</style>

</head>
<body>
<div id="overlay" onclick="closeExpanded()"></div>

<a href="logout.php" style="position:absolute;top:10px;left:10px;color:#fff;background:red;padding:8px 12px;border-radius:6px;text-decoration:none;">Logout</a>

<button class="dark-mode-toggle" style="position:absolute;top:10px;right:10px;">🌙</button>

<div style="text-align:center; margin-top: 60px;">
    <img src="<?php echo htmlspecialchars($userData['profile_picture'] ?? 'default.jpg'); ?>" class="profile-pic">
    <h2><?php echo htmlspecialchars($userData['username']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($userData['bio'])); ?></p>
    <a href="edit_profile.php" style="color:#ff9900;">Edit Profile</a>
</div>

<div class="container">
    <form action="upload_post.php" method="POST" enctype="multipart/form-data">
        <textarea name="content" class="post-box" placeholder="Įrašykite tekstą..."></textarea><br>
        <input type="file" name="media" accept="image/*,video/*"><br><br>
        <button type="submit">Paskelbti</button>
    </form>
</div>

<div class="search-container container">
    <input type="text" id="searchKeyword" class="filter-box" placeholder="Ieškoti pagal raktinį žodį...">
</div>

<div id="feed" class="container">
<?php
$sql = "SELECT posts.*, users.username, users.profile_picture FROM posts 
        JOIN users ON posts.user_id = users.id
        ORDER BY created_at DESC";
$result = $conn->query($sql);

while($post = $result->fetch_assoc()):
?>
<div class="post" data-post-id="<?php echo $post['id']; ?>">
    <div class="post-header" style="display: flex; align-items: center;">
        <img src="<?php echo htmlspecialchars($post['profile_picture']); ?>" class="profile-pic">
        <strong><?php echo htmlspecialchars($post['username']); ?></strong>
    </div>

    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

    <?php if (!empty($post['media_path'])): ?>
        <?php if (strpos($post['media_type'], 'image') !== false): ?>
            <img src="<?php echo htmlspecialchars($post['media_path']); ?>" 
                 class="media-preview" 
                 onclick="expandMedia('<?php echo htmlspecialchars($post['media_path']); ?>', 'image')">
        <?php elseif (strpos($post['media_type'], 'video') !== false): ?>
            <video class="media-preview" onclick="expandMedia('<?php echo htmlspecialchars($post['media_path']); ?>', 'video')">
                <source src="<?php echo htmlspecialchars($post['media_path']); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
    <?php endif; ?>

    <div class="buttons">
        <button type="button" onclick="react(this)">👍 <span><?php echo $post['likes']; ?></span></button>
        <button type="button" onclick="react(this)">❤️ <span><?php echo $post['loves']; ?></span></button>
        <button type="button" onclick="react(this)">🤣 <span><?php echo $post['hahas']; ?></span></button>
        <button type="button" onclick="react(this)">💀 <span><?php echo $post['skull']; ?></span></button>
        <button type="button" onclick="react(this)">😡 <span><?php echo $post['mad']; ?></span></button>
    </div>
</div>
<?php endwhile; ?>

<script>
document.querySelector('.dark-mode-toggle').addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
});

document.getElementById('searchKeyword').addEventListener('input', function() {
    const keyword = this.value.trim().toLowerCase();
    const posts = document.querySelectorAll('.post');
    posts.forEach(post => {
        const content = post.innerText.toLowerCase();
        post.style.display = content.includes(keyword) ? "block" : "none";
    });
});

// Expand media
function expandMedia(src, type) {
    const overlay = document.getElementById('overlay');
    overlay.innerHTML = type.startsWith('image') 
        ? `<img src="${src}" class="media-expanded">`
        : `<video src="${src}" class="media-expanded" controls autoplay></video>`;
    overlay.style.display = 'block';
}

function closeExpanded() {
    const overlay = document.getElementById('overlay');
    overlay.style.display = 'none';
    overlay.innerHTML = '';
}

function react(button) {
    const span = button.querySelector('span');
    let count = parseInt(span.innerText);
    span.innerText = count + 1;

    const postElement = button.closest('.post');
    const postId = postElement.dataset.postId;
    const reactionType = button.innerText.trim().split(' ')[0]; // Get emoji

    let reactionKey = '';
    if (reactionType === '👍') reactionKey = 'likes';
    else if (reactionType === '❤️') reactionKey = 'loves';
    else if (reactionType === '🤣') reactionKey = 'hahas';
    else if (reactionType === '💀') reactionKey = 'skull';
    else if (reactionType === '😡') reactionKey = 'mad';

    fetch('react.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}&reaction=${reactionKey}`
    });
}
</script>


</body>
</html>
