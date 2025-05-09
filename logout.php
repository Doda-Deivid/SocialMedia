<?php
session_start();
session_unset();
session_destroy();

// Redirect back to login page
header("Location: loginforma.html");
exit();
?>