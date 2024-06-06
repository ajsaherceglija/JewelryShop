<?php
global $conn;
require 'includes/header_sessions.php';
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pid'])) {
        $product_id = intval($_POST['pid']);
        $personId = $_SESSION['user_PID'];
        $currentDate = date('Y-m-d');

        $sql_check = "SELECT WID 
                      FROM wishlists 
                      WHERE w_product = $product_id AND people = $personId 
                        AND (valid_until IS NULL OR valid_until > '$currentDate')";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            $sql = "INSERT INTO wishlists (date_added, w_product, people) 
                    VALUES ('$currentDate', $product_id, $personId)";
            if ($conn->query($sql) === TRUE) {
                header("Location: product.php?pid=$product_id&wishlist=added");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            header("Location: product.php?pid=$product_id&wishlist=exists");
            exit();
        }
    }
}

$conn->close();
?>
