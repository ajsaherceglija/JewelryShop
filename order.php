<?php
global $conn;
require 'includes/header_sessions.php';
require 'includes/db_connection.php';

if (!isset($_SESSION['user_PID'])) {
    header("Location: login.php");
    exit();
}

$personId = $_SESSION['user_PID'];

$sql = "SELECT p.f_name, p.l_name, a.a_address, a.city, a.country, a.postal_code
        FROM people p, addresses a
        WHERE p.PID = a.people AND p.PID = $personId
        ORDER BY a.AID DESC 
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $details = $result->fetch_assoc();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string(trim($_POST['first_name']));
    $lastName = $conn->real_escape_string(trim($_POST['last_name']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $city = $conn->real_escape_string(trim($_POST['city']));
    $country = $conn->real_escape_string(trim($_POST['country']));
    $postalCode = $conn->real_escape_string(trim($_POST['postal_code']));

    if (empty($firstName) || empty($lastName) || empty($address) || empty($city) || empty($country) || empty($postalCode)) {
        echo "Error: All fields are required.";
        exit();
    }

    $sql = "UPDATE people 
            SET f_name = '$firstName', l_name = '$lastName' 
            WHERE PID = $personId";

    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
        exit();
    }

    $sql = "SELECT a.AID
            FROM people p, addresses a
            WHERE a.people = $personId
            AND a.country = '$country'
            AND a.city = '$city'
            AND a.postal_code = '$postalCode'
            AND a.a_address = '$address'
            ORDER BY a.AID DESC";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO addresses (country, city, a_address, postal_code, people) 
                VALUES ('$country', '$city', '$address', '$postalCode', '$personId')";

        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
            exit();
        }
        $addressId = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $addressId = $row['AID'];
    }

    $sql = "SELECT od.o_product, od.quantity
            FROM order_details od, orders o
            WHERE o.OID = od.o_order
            AND o.status = 'processing'
            AND o.o_address IN (SELECT AID FROM addresses WHERE people = '$personId')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productId = $row['o_product'];
            $quantityOrdered = $row['quantity'];

            $sql = "UPDATE products 
                    SET quantity = quantity - $quantityOrdered 
                    WHERE pid = $productId";

            if (!$conn->query($sql)) {
                echo "Error: " . $conn->error;
                exit();
            }
        }
    }

    $sql = "UPDATE orders 
            SET o_address = '$addressId', status = 'finished', o_date = NOW() 
            WHERE o_address IN (SELECT AID FROM addresses WHERE people = '$personId') AND status = 'processing'";

    if (!$conn->query($sql)) {
        echo "Error: " . $conn->error;
        exit();
    }

    $orderSuccess = true;
} else {
    $sql = "SELECT od.OID, p.p_name, od.price, od.quantity, o.total_price
            FROM order_details od
            JOIN products p ON p.PID = od.o_product
            JOIN orders o ON o.OID = od.o_order
            JOIN addresses a ON a.AID = o.o_address
            WHERE o.status = 'processing' AND a.people = $personId";
    $result = $conn->query($sql);

    $shoppingBagItems = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $shoppingBagItems[] = $row;
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="order.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<?php require 'includes/header.php'; ?>
<div class="search-box" id="search-box">
    <input id="search-input" type="text" placeholder="Search...">
    <div id="search-results"></div>
</div>

<div class="checkout-container">
    <?php if (isset($orderSuccess) && $orderSuccess): ?>
        <h2>Order Successful</h2>
        <p>Thank you for your order! Your order has been placed successfully.</p>
        <p><a href="home_page.php">Continue Shopping</a></p>
    <?php else: ?>
        <h2>Checkout</h2>
        <form id="checkout-form" action="order.php" method="post">
            <div class="shipping-details">
                <h3>Shipping Details</h3>
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo isset($details['f_name']) ? $details['f_name'] : ''; ?>" required><br>
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $details['l_name'] ?? ''; ?>" required><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $details['a_address'] ?? ''; ?>" required><br>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $details['city'] ?? ''; ?>" required><br>
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo $details['country'] ?? ''; ?>" required><br>
                <label for="postal_code">ZIP/Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" value="<?php echo $details['postal_code'] ?? ''; ?>" required><br>
            </div>
            <div class="order-summary">
                <h3>Order Summary</h3>
                <table>
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($shoppingBagItems as $item): ?>
                        <tr>
                            <td><?php echo$item['p_name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tr>
                        <td colspan="2"></td>
                        <td style="text-align: center" colspan="2" class="total-price" id="total-price"
                            data-total-price="<?php echo $item['total_price']; ?>">
                            Total price: $<?php echo $item['total_price']; ?>
                        </td>
                    </tr>
                </table>
            <div class="place-order">
                <button type="submit" class="place-order-button">Place Order</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script src="includes/header.js"></script>
</body>
</html>
