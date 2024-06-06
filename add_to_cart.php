<?php
global $conn;
session_start();
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pid'])) {
        $product_id = intval($_POST['pid']);
        $userID = $_SESSION['user_PID'];

        $checkQuery = "SELECT OID 
                       FROM order_details 
                       WHERE o_product = $product_id 
                         AND o_order IN (SELECT OID FROM orders WHERE o_address IN (SELECT AID FROM addresses WHERE people = $userID) AND status = 'processing')";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            header("Location: shopping_bag.php?added_to_cart=false");
            exit();
        }

        $orderQuery = "SELECT OID 
                       FROM orders 
                       WHERE o_address IN (SELECT AID FROM addresses WHERE people = $userID) 
                         AND status = 'processing'";
        $orderResult = $conn->query($orderQuery);

        if ($orderResult && $orderResult->num_rows > 0) {
            $orderRow = $orderResult->fetch_assoc();
            $oid = $orderRow['OID'];
        } else {
            $addressQuery = "SELECT AID FROM addresses WHERE people = $userID LIMIT 1";
            $addressResult = $conn->query($addressQuery);

            if ($addressResult && $addressResult->num_rows > 0) {
                $addressRow = $addressResult->fetch_assoc();
                $addressID = $addressRow['AID'];
                $currentDate = date('Y-m-d');

                $createOrderQuery = "INSERT INTO orders (o_address, status, o_date) 
                                     VALUES ('$addressID', 'processing', '$currentDate')";
                if ($conn->query($createOrderQuery) === TRUE) {
                    $oid = $conn->insert_id;
                } else {
                    echo 'Error: ' . $conn->error;
                    $conn->close();
                    exit();
                }
            } else {
                echo 'Error: No address found for the user.';
                $conn->close();
                exit();
            }
        }

        $sql = "INSERT INTO order_details (quantity, price, o_order, o_product) 
                VALUES (1, 
                        (SELECT current_price FROM products WHERE PID = $product_id),
                        $oid,
                        $product_id)";
        if ($conn->query($sql) === TRUE) {
            header("Location: shopping_bag.php?added_to_cart=true");
            exit();
        } else {
            echo 'Error: ' . $conn->error;
        }
    }
}

$conn->close();
?>
