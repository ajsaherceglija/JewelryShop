<?php
global $conn;
require 'includes/db_connection.php';
session_start();

if (!isset($_SESSION['user_PID'])) {
    echo 'Error: User not logged in.';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['oid'])) {
    if (isset($_POST['quantity'])) {
        $oids = $_POST['oid'];
        $quantities = $_POST['quantity'];
        if (count($oids) === count($quantities)) {
            $errors = [];
            $updatedItems = [];

            for ($i = 0; $i < count($oids); $i++) {
                $oid = $oids[$i];
                $quantity = $quantities[$i];

                $sql = "SELECT p.quantity as available_quantity, p.current_price, p.p_name, od.quantity as previous_quantity
                        FROM order_details od
                        JOIN products p ON p.pid = od.o_product
                        WHERE od.OID = $oid";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    if ($quantity > $row['available_quantity']) {
                        $name = $row['p_name'];
                        $errors[] = "Requested quantity for $name exceeds available stock.";

                        // Return the previous quantity in case of an error
                        echo json_encode(['success' => false, 'error' => implode(", ", $errors), 'oid' => $oid, 'previous_quantity' => $row['previous_quantity']]);
                        return;
                    } else {
                        $sql = "UPDATE order_details SET quantity = $quantity WHERE OID = $oid";

                        if ($conn->query($sql) === TRUE) {
                            $sql = "SELECT p.current_price, od.quantity
                                    FROM order_details od
                                    JOIN products p ON p.pid = od.o_product
                                    WHERE od.OID = $oid";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $totalItemPrice = $row['current_price'] * $row['quantity'];
                                $updatedItems[] = [
                                    'oid' => $oid,
                                    'total_item_price' => $totalItemPrice
                                ];
                            }
                        } else {
                            $errors[] = "Error updating OID $oid: " . $conn->error;
                        }
                    }
                }
            }

            $conn->close();

            if (empty($errors)) {
                echo json_encode(['success' => true, 'items' => $updatedItems]);
            } else {
                echo json_encode(['success' => false, 'error' => implode(", ", $errors)]);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Mismatched OID and quantity arrays.']);
        }
    } else {
        $oid = $_POST['oid'];
        $sql = "DELETE FROM order_details WHERE OID = $oid";

        if ($conn->query($sql) === TRUE) {
            echo "Success";
        } else {
            echo "Error removing item: " . $conn->error;
        }
        $conn->close();
    }

}
?>