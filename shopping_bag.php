<?php
global $conn;
require 'includes/header_sessions.php';
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    header("Location: login.php");
    exit();
}

$personId = $_SESSION['user_PID'];
$currentDate = date('Y-m-d');

$sql = "SELECT od.OID, p.p_name, p.current_price, p.p_image, od.quantity, o.o_date, o.total_price
        FROM order_details od
        JOIN products p ON p.pid = od.o_product
        JOIN orders o ON o.OID = od.o_order
        JOIN addresses a ON a.AID = o.o_address
        WHERE o.status = 'processing' AND a.people = $personId";
$result = $conn->query($sql);

$bagItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalItemPrice = $row['current_price'] * $row['quantity'];
        $row['total_item_price'] = $totalItemPrice;
        $bagItems[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping Bag</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="shopping_bag.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="home">
<?php require 'includes/header.php'?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>
<div class="shopping-bag-container">
    <h2>Shopping Bag</h2>
    <div id="bag-box">
        <?php if (empty($bagItems)): ?>
            <p>Your shopping bag is empty.</p>
        <?php else: ?>
            <form id="update-bag-form">
                <table>
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Date Added</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bagItems as $item): ?>
                        <tr class="bag-item" data-oid="<?php echo $item['OID']; ?>">
                            <td class="item-image"><img src="images/<?php echo $item['p_image']; ?>" alt="<?php echo $item['p_name']; ?>"></td>
                            <td class="item-info">
                                <p><?php echo $item['p_name']; ?></p>
                            </td>
                            <td class="item-info">
                                <p><?php echo date('Y-m-d', strtotime($item['o_date'])); ?></p>
                            </td>
                            <td class="item-info">
                                <input type="number" name="quantity" data-oid="<?php echo $item['OID']; ?>" data-price="<?php echo $item['current_price']; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td class="item-info">
                                <p class="item-total" id="total-item-price">$<?php echo number_format($item['total_item_price'], 2); ?></p>
                            </td>
                            <td class="actions">
                                <button type="button" class="remove" onclick="removeFromBag(<?php echo $item['OID']; ?>)">X</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            <button type="submit" class="update-button">Update Quantity</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>
        <div class="checkout-container">
            <button type="button" class="checkout-button" onclick="window.location.href='order.php'">Checkout</button>
        </div>
    </div>
</div>

</body>
<script src="includes/header.js"></script>
<script src="shopping_bag.js"></script>
</html>