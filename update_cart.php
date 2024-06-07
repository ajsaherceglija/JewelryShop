<?php
global $conn;
require 'includes/db_connection.php';
session_start();

if (!isset($_SESSION['user_PID'])) {
    echo 'Error: User not logged in.';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['oid'])) {
    $oid = $_POST['oid'];
    if (isset($_POST['quantity'])) {
        $quantity = $_POST['quantity'];
        $sql = "UPDATE order_details SET quantity = $quantity WHERE OID = $oid";

        if ($conn->query($sql) === TRUE) {
            echo "Quantity updated successfully.";
        } else {
            echo "Error updating quantity: " . $conn->error;
        }
        $conn->close();
    } else {
        $sql = "DELETE FROM order_details WHERE OID = $oid";

        if ($conn->query($sql) === TRUE) {
            echo "Item removed successfully.";
        } else {
            echo "Error removing item: " . $conn->error;
        }
        $conn->close();
    }

}
?>