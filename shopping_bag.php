<?php
require 'includes/header_sessions.php';
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    header("Location: login.php");
    exit();
}

$personId = $_SESSION['user_PID'];
$currentDate = date('Y-m-d');

$sql = "SELECT s.SID, p.p_name, p.current_price, p.p_image, s.quantity AS bag_quantity, s.date_added
        FROM shopping_bag s
        JOIN products p ON p.pid = s.s_product
        WHERE s.user = $personId";
$result = $conn->query($sql);

$bagItems = [];
$totalPrice = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['total_price'] = $row['current_price'] * $row['bag_quantity'];
        $totalPrice += $row['total_price'];
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
                        <tr class="shopping-bag-item" data-sid="<?php echo $item['SID']; ?>">
                            <td class="item-image"><img src="images/<?php echo $item['p_image']; ?>" alt="<?php echo $item['p_name']; ?>"></td>
                            <td><?php echo $item['p_name']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($item['date_added'])); ?></td>
                            <td>
                                <input type="number" name="quantity" data-sid="<?php echo $item['SID']; ?>" data-price="<?php echo $item['current_price']; ?>" value="<?php echo $item['bag_quantity']; ?>" min="1">
                            </td>
                            <td class="item-total" data-price="<?php echo $item['current_price']; ?>">$<?php echo number_format($item['total_price'], 2); ?></td>
                            <td class="actions">
                                <button type="button" class="remove" onclick="removeFromBag(<?php echo $item['SID']; ?>)">X</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            <button type="submit" class="update-button">Update</button>
                        </td>
                        <td class="total-price" id="total-price">$<?php echo number_format($totalPrice, 2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
<script src="includes/header.js"></script>
<script src="shopping_bag.js"></script>
</html>