<?php
require 'includes/db_connection.php';
session_start();

if (!isset($_SESSION['user_PID'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wid']) && isset($_POST['comment'])) {
    $wid = intval($_POST['wid']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $sql = "UPDATE wishlists SET w_comment = '$comment' WHERE WID = $wid";
    if ($conn->query($sql) === TRUE) {
        echo htmlspecialchars($comment);
    } else {
        echo "Error: " . $conn->error;
    }
}
?>