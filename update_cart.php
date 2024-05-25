<?php
require 'includes/db_connection.php';
session_start();

if (!isset($_SESSION['user_PID'])) {
    echo 'Error: User not logged in.';
    exit();
}

$personId = $_SESSION['user_PID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if quantities are being updated
    if (isset($_POST['quantities'])) {
        $quantities = json_decode($_POST['quantities'], true);

        foreach ($quantities as $sid => $quantity) {
            $sid = intval($sid);
            $quantity = intval($quantity);

            if ($quantity > 0) {
                $sid = $conn->real_escape_string($sid);
                $quantity = $conn->real_escape_string($quantity);
                $personId = $conn->real_escape_string($personId);

                $sql = "UPDATE shopping_bag SET quantity = '$quantity' WHERE SID = '$sid' AND user = '$personId'";
                if ($conn->query($sql) !== TRUE) {
                    echo 'Error: ' . $conn->error;
                    exit();
                }
            }
        }
    }

    if (isset($_POST['remove_sid'])) {
        $sid = intval($_POST['remove_sid']);
        $sid = $conn->real_escape_string($sid);
        $personId = $conn->real_escape_string($personId);

        $sql = "DELETE FROM shopping_bag WHERE SID = '$sid' AND user = '$personId'";
        if ($conn->query($sql) !== TRUE) {
            echo 'Error: ' . $conn->error;
            exit();
        }
        echo 'Success';
    } else {
        echo 'Invalid request.';
    }
} else {
    echo 'Invalid request.';
}
?>