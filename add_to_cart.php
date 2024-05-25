<?php
session_start();
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    echo 'Error: User not logged in.';
    exit();
}

$userID = $_SESSION['user_PID'];

$wid = $_POST['wid'];

$sql = "SELECT w_product FROM wishlists WHERE WID = $wid AND people = $userID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pid = $row['w_product'];

    $sql = "INSERT INTO shopping_bag (quantity, user, s_product, date_added) VALUES (1, $userID, $pid, NOW())";
    if ($conn->query($sql) === TRUE) {
        echo 'Success';
    } else {
        echo 'Error: ' . $conn->error;
    }
} else {
    echo 'Error: Invalid wishlist item.';
}

$conn->close();
?>
