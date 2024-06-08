<?php
global $conn;
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../includes/db_connection.php';

if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = $_GET['id'];

$sql_order_details = "SELECT od.*, p.p_name, p.current_price
                      FROM order_details od
                      INNER JOIN products p ON od.o_product = p.pid
                      WHERE od.o_order = $order_id";

$result_order_details = $conn->query($sql_order_details);

$sql_order_info = "SELECT o.*, a.*
                   FROM orders o
                   INNER JOIN addresses a ON o.o_address = a.AID
                   WHERE OID = $order_id";

$result_order_info = $conn->query($sql_order_info);

if ($result_order_info->num_rows == 0) {
    header("Location: manage_orders.php");
    exit();
}

$order_info = $result_order_info->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="view_order.css">
</head>
<body>
<header>
    <div class="name">
        <a href="../home_page.php">JEWELRY PORODICA KARALIĆ</a>
    </div>
</header>
<div class="container">
    <div class="order-detail">
        <h2>Order #<?= $order_id ?></h2>
        <p><strong>Status:</strong> <?= $order_info['status'] ?></p>
        <p><strong>Order Date:</strong> <?= $order_info['o_date'] ?></p>
        <p><strong>Total Price:</strong> <?= $order_info['total_price'] ?></p>

        <h3>Address Details</h3>
        <p><strong>Country:</strong> <?= $order_info['country'] ?></p>
        <p><strong>City:</strong> <?= $order_info['city'] ?></p>
        <p><strong>Postal Code:</strong> <?= $order_info['postal_code'] ?></p>
        <p><strong>Address:</strong> <?= $order_info['a_address'] ?></p>
    </div>
</div>
<div class="order-items">
    <div class="wrapper">
        <h3>Order Items</h3>
        <table>
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $result_order_details->fetch_assoc()) {
                $total = $row['price'] * $row['quantity'];
                ?>
                <tr>
                    <td><?= $row['p_name'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['current_price'] ?></td>
                    <td><?= $total ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

